<?php

namespace App\Http\Livewire\Admin;

use App\Models\Asistencia;
use App\Models\Designacione;
use App\Models\Cliente;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Registroasistencias extends Component
{
    use WithPagination;

    public $cliente_id = '';
    public $fechaInicio;
    public $fechaFin;
    public $empleado = '';

    public $clientes;
    public $dias = [];

    // Marcado manual
    public $marcadoEmpleado = null;
    public $marcadoTipo = null; // 'ingreso' o 'salida'
    public $marcadoHora = '';
    public $marcadoDesignacione = null;
    public $tipo_designacion = '';
    public $tipos = [];
    // Paginación
    public $perPage = 5;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->clientes = Cliente::all();
        $hoy = Carbon::now()->toDateString();
        $this->fechaInicio = $hoy;
        $this->fechaFin = $hoy;
        $this->tipos = Designacione::getEnumValues('tipo_designacion');
    }

    public function updating($field)
    {
        if (in_array($field, ['cliente_id', 'empleado', 'fechaInicio', 'fechaFin', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function loadData()
    {
        $this->dias = $this->getDiasRango($this->fechaInicio, $this->fechaFin);

        $query = Designacione::with(['empleado', 'turno.cliente', 'dialibres'])
            ->where('designaciones.estado', 1)

            ->whereDate('fechaInicio', '<=', $this->fechaFin)
            ->whereDate('fechaFin', '>=', $this->fechaInicio);

        if ($this->tipo_designacion) {
            $query->where('tipo_designacion', $this->tipo_designacion);
        }

        if ($this->cliente_id) {
            $query->whereHas('turno', function ($q) {
                $q->where('cliente_id', $this->cliente_id);
            });
        }

        if ($this->empleado) {
            $query->whereHas('empleado', function ($q) {
                $q->where('nombres', 'like', '%' . $this->empleado . '%')
                    ->orWhere('apellidos', 'like', '%' . $this->empleado . '%');
            });
        }
        //         $query = Designacione::with(['empleado', 'turno.cliente', 'dialibres'])
        //             ->where('designaciones.estado', 1)
        //             ->where('designaciones.empleado_id', '!=', null)
        //             ->whereDate('fechaInicio', '<=', $this->fechaFin)
        //             ->whereDate('fechaFin', '>=', $this->fechaInicio)
        // // ->where('turno_id', '!=', null)
        //             // ->whereHas('turno', function ($q) {
        //             //     $q->whereNotNull('cliente_id');
        //             // })
        //             ;
        //

        //         if ($this->cliente_id) {
        //             $query->whereHas('turno', function ($q) {
        //                 $q->where('cliente_id', $this->cliente_id);
        //             });
        //         }

        //         if ($this->empleado) {
        //             $query->whereHas('empleado', function ($q) {
        //                 $q->where('nombres', 'like', '%' . $this->empleado . '%')
        //                     ->orWhere('apellidos', 'like', '%' . $this->empleado . '%');
        //             });
        //         }


        return $query->paginate($this->perPage);
    }

    private function getDiasRango($start, $end)
    {
        $fechas = [];
        $inicio = Carbon::parse($start);
        $fin = Carbon::parse($end);

        while ($inicio <= $fin) {
            $fechas[] = $inicio->copy();
            $inicio->addDay();
        }

        return $fechas;
    }

    // MARCADO MANUAL
    public function abrirMarcadoManual($desigId, $tipo)
    {
        $this->marcadoDesignacione = Designacione::find($desigId);
        $this->marcadoEmpleado = $this->marcadoDesignacione->empleado;
        $this->marcadoTipo = $tipo;
        $this->marcadoHora = now()->format('H:i');

        $this->dispatchBrowserEvent('mostrar-modal');
    }

    public function guardarMarcadoManual()
    {
        if (!$this->marcadoDesignacione || !$this->marcadoTipo) return;

        $hoy = now()->toDateString();

        $asistencia = Asistencia::firstOrCreate(
            ['designacione_id' => $this->marcadoDesignacione->id, 'fecha' => $hoy],
            ['ingreso' => null, 'salida' => null]
        );

        $horaCompleta = $hoy . ' ' . $this->marcadoHora . ':00';

        if ($this->marcadoTipo === 'ingreso') {
            $asistencia->ingreso = $horaCompleta;
        } elseif ($this->marcadoTipo === 'salida') {
            $asistencia->salida = $horaCompleta;
        }

        $asistencia->save();

        session()->flash('success', 'Marcado guardado correctamente.');
        $this->dispatchBrowserEvent('cerrar-modal');
    }

    public function puedeMarcarSalida($desig, $asis)
    {
        if (!$asis || !$asis->ingreso) return false;

        $horaIngreso = Carbon::parse($asis->ingreso);
        $horaSalidaTurno = $desig->turno && $desig->turno->horafin ? Carbon::parse($desig->turno->horafin) : null;
        $ahora = now();

        if (!$horaSalidaTurno) return true;

        if ($horaSalidaTurno->lt(Carbon::parse($desig->turno->horainicio))) {
            $horaSalidaTurno->addDay();
        }

        return $ahora->gte($horaSalidaTurno);
    }

    public function render()
    {
        $designaciones = $this->loadData();
        return view('livewire.admin.registroasistencias', compact('designaciones'))
            ->extends('adminlte::page');
    }

    public function getAllData()
    {
        $dias = $this->getDiasRango($this->fechaInicio, $this->fechaFin);

        $query = Designacione::with(['empleado', 'turno.cliente', 'dialibres'])
            ->where('estado', true)
            ->whereDate('fechaInicio', '<=', $this->fechaFin)
            ->whereDate('fechaFin', '>=', $this->fechaInicio);

        if ($this->cliente_id) {
            $query->whereHas('turno', function ($q) {
                $q->where('cliente_id', $this->cliente_id);
            });
        }

        if ($this->empleado) {
            $query->whereHas('empleado', function ($q) {
                $q->where('nombres', 'like', '%' . $this->empleado . '%')
                    ->orWhere('apellidos', 'like', '%' . $this->empleado . '%');
            });
        }

        $designaciones = $query->get();

        $data = [];

        foreach ($designaciones as $desig) {
            $empleadoData = [
                'empleado' => $desig->empleado->nombres . ' ' . $desig->empleado->apellidos,
                'turno' => $desig->turno->nombre ?? '',
                'horario' => ($desig->turno->horainicio ?? '') . ' - ' . ($desig->turno->horafin ?? ''),
                'empresa' => $desig->turno->cliente->nombre ?? 'Sin empresa',
                'asistencias' => []
            ];

            foreach ($dias as $dia) {
                $asis = Asistencia::where('designacione_id', $desig->id)
                    ->whereDate('fecha', $dia->toDateString())
                    ->first();

                // Verificar si el día es libre
                $diaLibre = $desig->dialibres->firstWhere('fecha', $dia->toDateString());

                // Si fuera del rango de la designación
                $fueraRango = $dia->lt(Carbon::parse($desig->fechaInicio)) || $dia->gt(Carbon::parse($desig->fechaFin));

                $empleadoData['asistencias'][] = [
                    'fecha' => $dia->toDateString(),
                    'ingreso' => $asis && $asis->ingreso ? Carbon::parse($asis->ingreso)->format('H:i') : null,
                    'salida' => $asis && $asis->salida ? Carbon::parse($asis->salida)->format('H:i') : null,
                    'sin_marcacion' => !$asis && !$diaLibre && !$fueraRango,
                    'fuera_rango' => $fueraRango,
                    'dia_libre' => $diaLibre ? true : false,
                    'observaciones_libre' => $diaLibre->observaciones ?? ''
                ];
            }

            $data[] = $empleadoData;
        }

        return $data;
    }

    public function pdf()
    {
        $datos = $this->getAllData();
        request()->session()->put('data-asistencias', $datos);
        $this->emit('renderizarpdf');
    }
}
