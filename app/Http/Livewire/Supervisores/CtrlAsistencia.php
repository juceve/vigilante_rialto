<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Inspeccion;
use Carbon\Carbon;
use Livewire\Component;

class CtrlAsistencia extends Component
{
    public $inspeccionActiva, $designaciones, $selEmpleado;
    public $searchEmpleado = '';
    public $selTipoBoletaId = '', $selTipoBoleta, $detalles;
    public $procesando = false;

    public function mount($inspeccion_id)
    {
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);
        $this->designaciones = $this->traeEmpleados($this->inspeccionActiva->cliente_id);
    }
    public function render()
    {
        return view('livewire.supervisores.ctrl-asistencia')->extends('layouts.app');
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
            ->get();

        return $designaciones;
    }

    public function selectEmpleado($id) {
        $this->selEmpleado = Designacione::find($id);
        $this->emit('openModal');
    }

}
