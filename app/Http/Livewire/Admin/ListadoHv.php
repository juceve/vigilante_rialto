<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Hombrevivo;
use App\Models\Intervalo;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HombreVivoExport;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListadoHv extends Component
{
    use AuthorizesRequests;
    public $cliente_id = '';
    public $empleado_id = '';
    public $fecha_inicio;
    public $fecha_fin;
    public $clientes = [];
    public $empleados = [];
    public $resultados = [];
    public $mostrarResultados = false;

    public function mount()
    {
        $this->authorize('admin.hombre_vivo');
        Carbon::setLocale('es');

        $this->clientes = Cliente::where('status', 1)
            ->orderBy('nombre')
            ->get();

        $this->fecha_inicio = Carbon::now()->subDays(6)->format('Y-m-d');
        $this->fecha_fin = Carbon::now()->format('Y-m-d');
    }

    public function updatedClienteId()
    {
        if ($this->cliente_id) {
            $this->empleados = Empleado::whereHas('designaciones', function ($query) {
                $query->whereHas('turno', function ($subQuery) {
                    $subQuery->where('cliente_id', $this->cliente_id);
                })->where('estado', true);
            })->orderBy('nombres')->get();
        } else {
            $this->empleados = [];
        }
        $this->empleado_id = '';
    }

    public function generarReporte()
    {
        $this->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $this->resultados = [];
        $this->mostrarResultados = true;

        $fechaInicio = Carbon::parse($this->fecha_inicio);
        $fechaFin = Carbon::parse($this->fecha_fin);

        // Construir query de empleados que tengan designaciones dentro del rango
        // y cuyo turno tenga un cliente asociado (cliente_id not null)
        $empleadosQuery = Empleado::whereHas('designaciones', function ($q) use ($fechaInicio, $fechaFin) {
            $q->where('estado', true)
                ->where('fechaInicio', '<=', $fechaFin->format('Y-m-d'))
                ->where('fechaFin', '>=', $fechaInicio->format('Y-m-d'));

            if ($this->cliente_id) {
                $q->whereHas('turno', function ($sub) {
                    $sub->where('cliente_id', $this->cliente_id);
                });
            } else {
                // Excluir designaciones cuyo turno no tenga cliente
                $q->whereHas('turno', function ($sub) {
                    $sub->whereNotNull('cliente_id');
                });
            }
        });

        if ($this->empleado_id) {
            $empleadosQuery->where('id', $this->empleado_id);
        }

        $empleados = $empleadosQuery->with(['designaciones.turno', 'designaciones.intervalos'])->orderBy('nombres')->get();

        $resultados = [];

        foreach ($empleados as $empleado) {
            $dias = [];

            foreach (CarbonPeriod::create($fechaInicio, $fechaFin) as $dia) {
                $fechaStr = $dia->format('Y-m-d');


                // Simplificado: tomar los intervalos de las designaciones activas
                // para este empleado en la fecha y contar Hombrevivo por intervalo_id.
                $desigs = $empleado->designaciones->filter(function ($d) use ($fechaStr) {
                    $ok = $d->estado
                        && $d->fechaInicio <= $fechaStr
                        && $d->fechaFin >= $fechaStr
                        && isset($d->turno)
                        && $d->turno->cliente_id !== null;
                    if (!$ok) return false;
                    if ($this->cliente_id) {
                        return $d->turno->cliente_id == $this->cliente_id;
                    }
                    return true;
                });

                $intervalos = $desigs->flatMap(function ($d) {
                    return $d->intervalos->pluck('id');
                })->unique()->values()->all();

                $esperadas = count($intervalos);

                if ($esperadas === 0) {
                    $marcaciones = [];
                    $cantReg = 0;
                } else {
                    $registros = Hombrevivo::whereDate('fecha', $fechaStr)
                        ->whereIn('intervalo_id', $intervalos)
                        ->get();

                    $cantReg = $registros->count();
                    $marcaciones = $registros->map(function ($r) {
                        $f = \Carbon\Carbon::parse($r->fecha)->format('Y-m-d');
                        $h = isset($r->hora) && $r->hora ? $r->hora : \Carbon\Carbon::parse($r->fecha)->format('H:i:s');
                        return $f . ' ' . $h;
                    })->values()->all();
                }

                // Determinar si debemos analizar cumplimiento para este día
                $diaCarbon = \Carbon\Carbon::parse($fechaStr);
                $now = \Carbon\Carbon::now();
                $analyze = false;

                if ($diaCarbon->isToday()) {
                    $analyze = false; // no analizar hoy
                } elseif ($diaCarbon->isYesterday()) {
                    // analizar solo si el/los turnos para ese día ya terminaron
                    foreach ($desigs as $d) {
                        if (!isset($d->turno) || !$d->turno) continue;
                        $t = $d->turno;
                        if (!$t->horafin) continue;
                        try {
                            $hInicio = $t->horainicio ? \Carbon\Carbon::parse($t->horainicio) : null;
                            $hFin = \Carbon\Carbon::parse($t->horafin);
                        } catch (\Exception $e) {
                            continue;
                        }

                        // si inicio > fin, es nocturno -> fin es el dia siguiente
                        if ($hInicio && $hInicio->gt($hFin)) {
                            $end = $diaCarbon->copy()->addDay()->setTimeFrom($hFin);
                        } else {
                            $end = $diaCarbon->copy()->setTimeFrom($hFin);
                        }

                        if ($now->greaterThan($end)) {
                            $analyze = true;
                            break;
                        }
                    }
                } else {
                    // días pasados: siempre analizar
                    $analyze = $diaCarbon->lt($now);
                }

                // Estado según la nueva regla:
                // 0/N -> danger, (0<r<N) -> warning, N/N -> success
                $status = 'neutral';
                if ($esperadas > 0 && $analyze) {
                    if ($cantReg == 0) {
                        $status = 'danger';
                    } elseif ($cantReg < $esperadas) {
                        $status = 'warning';
                    } else {
                        $status = 'success';
                    }
                } else {
                    $status = 'neutral';
                }

                $dias[] = [
                    'fecha' => $fechaStr,
                    'esperadas' => $esperadas,
                    'marcaciones_count' => $cantReg,
                    'marcaciones' => $marcaciones,
                    'status' => $status,
                ];
            }

            $total = collect($dias)->sum('marcaciones_count');
            $totalEsperadas = collect($dias)->sum('esperadas');
            $cumpl = 0;
            if ($totalEsperadas > 0) {
                $cumpl = round(($total / $totalEsperadas) * 100, 1);
                if ($cumpl > 100) $cumpl = 100;
            }

            // Determinar intervalo_hv representativo para el empleado (primer valor activo en el rango)
            $desigsRango = $empleado->designaciones->filter(function ($d) use ($fechaInicio, $fechaFin) {
                return $d->estado
                    && $d->fechaInicio <= $fechaFin->format('Y-m-d')
                    && $d->fechaFin >= $fechaInicio->format('Y-m-d')
                    && isset($d->turno)
                    && $d->turno->cliente_id !== null;
            })->sortBy('fechaInicio')->values();

            // Elegir el primer intervalo_hv no nulo de las designaciones ordenadas por fechaInicio
            $intervaloHv = $desigsRango->pluck('intervalo_hv')->filter()->first() ?? null;

            $resultados[] = [
                'empleado_id' => $empleado->id,
                'empleado_nombre' => $empleado->nombres . ' ' . $empleado->apellidos,
                'intervalo_hv' => $intervaloHv,
                'dias' => $dias,
                'total_marcaciones' => $total,
                'total_esperadas' => $totalEsperadas,
                'cumplimiento' => $cumpl,
            ];
        }

        $this->resultados = $resultados;
    }

    public function limpiar()
    {
        $this->cliente_id = '';
        $this->empleado_id = '';
        $this->fecha_inicio = Carbon::now()->subDays(6)->format('Y-m-d');
        $this->fecha_fin = Carbon::now()->format('Y-m-d');
        $this->resultados = [];
        $this->empleados = [];
        $this->mostrarResultados = false;
    }

    public function exportarExcel()
    {
        if (empty($this->resultados)) {
            session()->flash('warning', 'No hay datos para exportar.');
            return;
        }

        $nombreCliente = $this->cliente_id
            ? Cliente::find($this->cliente_id)->nombre
            : 'Todos';

        $filename = 'reporte_hombre_vivo_' . str_replace(' ', '_', $nombreCliente) . '_' . date('Y-m-d') . '.xlsx';

        $nombreClienteFiltro = $this->cliente_id ? (Cliente::find($this->cliente_id)->nombre ?? 'N/A') : 'Todos';
        if ($this->empleado_id) {
            $empObj = Empleado::find($this->empleado_id);
            $nombreEmpleadoFiltro = $empObj ? ($empObj->nombres . ' ' . $empObj->apellidos) : 'N/A';
        } else {
            $nombreEmpleadoFiltro = 'Todos';
        }

        $filters = [
            'cliente' => $nombreClienteFiltro,
            'empleado' => $nombreEmpleadoFiltro,
        ];

        return Excel::download(
            new HombreVivoExport($this->resultados, $this->fecha_inicio, $this->fecha_fin, $filters),
            $filename
        );
    }

    public function exportarPdf()
    {
        if (empty($this->resultados)) {
            session()->flash('warning', 'No hay datos para exportar.');
            return;
        }
        Carbon::setLocale('es');
        $fechaI = Carbon::parse($this->fecha_inicio);
        $fechaF = Carbon::parse($this->fecha_fin);
        $dias = [];
        $current = $fechaI->copy();

        while ($current <= $fechaF) {
            $dias[] = [
                'fecha' => $current->format('Y-m-d'),
                'dia' => $current->format('d'),
                'mes' => mb_substr($current->translatedFormat('M'), 0, 3),
                'diaNombre' => mb_substr($current->translatedFormat('l'), 0, 3)
            ];
            $current->addDay();
        }

        $nombreCliente = $this->cliente_id
            ? (Cliente::find($this->cliente_id)->nombre ?? 'Todos los clientes')
            : 'Todos los clientes';

        $nombreEmpleado = $this->empleado_id
            ? (Empleado::find($this->empleado_id) ? (Empleado::find($this->empleado_id)->nombres . ' ' . Empleado::find($this->empleado_id)->apellidos) : 'N/A')
            : 'Todos los empleados';

        $pdf = PDF::loadView('reports.hombre-vivo-pdf', [
            'resultados' => $this->resultados,
            'dias' => $dias,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'cliente' => $nombreCliente,
            'empleado' => $nombreEmpleado,
            'fecha_reporte' => Carbon::now()->format('d/m/Y H:i')
        ])->setPaper('a4', 'landscape');

        $filename = 'reporte_hombre_vivo_' . str_replace(' ', '_', $nombreCliente) . '_' . date('Y-m-d_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.admin.listado-hv')->extends('adminlte::page');
    }
}
