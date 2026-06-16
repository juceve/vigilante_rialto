<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Designacione;
use App\Models\Inspeccion;
use App\Models\SupBoleta;
use App\Models\Tipoboleta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class EmitirBoleta extends Component
{


    public $inspeccionActiva, $empleados, $empleadosSeleccionados = [];
    public $searchEmpleado = '';
    public $selTipoBoletaId = '', $selTipoBoleta, $detalles = '';
    public $procesando = false;

    public function mount($inspeccion_id)
    {
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);
        $this->empleados = $this->traeEmpleados($this->inspeccionActiva->cliente_id)->toArray();
    }


    // Agrega un empleado al array
    public function seleccionar($id)
    {
        if (!in_array($id, $this->empleadosSeleccionados)) {
            $this->empleadosSeleccionados[] = $id;
        }
        $this->searchEmpleado = ''; // limpia búsqueda
    }

    // Quita un empleado del array
    public function deseleccionar($id)
    {
        $this->empleadosSeleccionados = array_filter($this->empleadosSeleccionados, fn($e) => $e != $id);
    }
    public function render()
    {
        $tipos = Tipoboleta::all();
        return view('livewire.supervisores.emitir-boleta', compact('tipos'))->extends('layouts.app');
    }

    public function updatedSelTipoBoletaId()
    {
        $this->selTipoBoleta = Tipoboleta::find($this->selTipoBoletaId);
    }

    public function traeEmpleados($cliente_id)
    {
        $hoy = Carbon::today();
        $diaSemanaNum = $hoy->dayOfWeek; // 0 = domingo, 6 = sábado
        $mapDia = [
            0 => 'domingo',
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
        ];
        $diaColumna = $mapDia[$diaSemanaNum];

        $designaciones = Designacione::with(['empleado', 'designaciondias', 'turno'])
            ->where('estado', true)
            ->whereDate('fechaInicio', '<=', $hoy)
            ->whereDate('fechaFin', '>=', $hoy)
            ->whereHas('designaciondias', function ($q) use ($diaColumna) {
                $q->where($diaColumna, true);
            })
            ->whereHas('turno', function ($q) use ($cliente_id) {
                $q->where('cliente_id', $cliente_id);
            })
            ->whereDoesntHave('dialibres', function ($q) use ($hoy) {
                $q->whereDate('fecha', $hoy);
            })
            ->whereDoesntHave('empleado.rrhhpermisos', function ($q) use ($hoy) {
                $q->where('activo', true)
                    ->whereDate('fecha_inicio', '<=', $hoy)
                    ->whereDate('fecha_fin', '>=', $hoy);
            })
            ->get()
            ->map(function ($designacione) {
                return [
                    'id' => $designacione->empleado->id,
                    'nombre' => $designacione->empleado->nombres . ' ' . $designacione->empleado->apellidos,
                ];
            });
        return $designaciones;
    }

    protected $rules = [
        'empleadosSeleccionados' => 'required|array|min:1',
        'selTipoBoletaId' => 'required',
    ];

    protected $listeners = ['emitirBoleta'];

    public function emitirBoleta()
    {
        $this->validate($this->rules);

        if ($this->procesando) {
            return;
        }

        $this->procesando = true;

        DB::beginTransaction();

        try {
            foreach ($this->empleadosSeleccionados as $empleado_id) {
                $tipoboleta = Tipoboleta::find($this->selTipoBoletaId);
                $boleta = SupBoleta::create([
                    'fechahora' => date('Y-m-d H:i:s'),
                    'cliente_id' => $this->inspeccionActiva->cliente_id,
                    'empleado_id' => $empleado_id,
                    'tipoboleta_id' => $this->selTipoBoletaId,
                    'supervisor_id' => $this->inspeccionActiva->designacionsupervisor->empleado_id,
                    'detalles' => $this->detalles ?? 'N/A',
                    'descuento' => $tipoboleta->monto_descuento,
                ]);
            }
            DB::commit();
            return redirect()->route('supervisores.listadoboletas', $this->inspeccionActiva->id)->with('success', 'Boleta(s) emitida(s) correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->procesando = false;
            $this->emit('error', $th->getMessage());
        }
    }
}
