<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Asistencia;
use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Rrhhcontrato;
use Carbon\Carbon;
use Livewire\Component;

class Asistencias extends Component
{
    public $meses, $mesSel, $gestionSel;
    public $gestiones = [], $resultados = null;
    public $designacione, $empleado, $contratoActivo;
    public $diasMes = [];

    public function mount()
    {
        $this->meses = array(
            array('id' => 1, 'nombre' => 'Enero'),
            array('id' => 2, 'nombre' => 'Febrero'),
            array('id' => 3, 'nombre' => 'Marzo'),
            array('id' => 4, 'nombre' => 'Abril'),
            array('id' => 5, 'nombre' => 'Mayo'),
            array('id' => 6, 'nombre' => 'Junio'),
            array('id' => 7, 'nombre' => 'Julio'),
            array('id' => 8, 'nombre' => 'Agosto'),
            array('id' => 9, 'nombre' => 'Septiembre'),
            array('id' => 10, 'nombre' => 'Octubre'),
            array('id' => 11, 'nombre' => 'Noviembre'),
            array('id' => 12, 'nombre' => 'Diciembre'),
        );
        $this->gestiones = range(date('Y'), date('Y') - 1);
        $this->mesSel = date('n');
        $this->gestionSel = date('Y');

        $this->generaDiasMes();

        $hoy = Carbon::now()->toDateString();

        $this->designacione = Designacione::find(session('designacion-oper'));
        $empleado_id = $this->designacione ? $this->designacione->empleado_id : null;
        $this->empleado = Empleado::find($empleado_id);

        $this->contratoActivo = Rrhhcontrato::where('empleado_id', $this->empleado->id)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $hoy);
            })
            ->where('activo', true)
            ->orderBy('fecha_inicio', 'asc')
            ->first();
    }

    public function render()
    {
        return view('livewire.vigilancia.asistencias')->extends('layouts.app');
    }

    public function buscar()
    {
        $this->resultados = Asistencia::where('designacione_id', $this->designacione->id)
            ->whereYear('fecha', $this->gestionSel)
            ->whereMonth('fecha', $this->mesSel)
            ->orderBy('fecha', 'asc')
            ->get();
    }

    public function generaDiasMes(){
        $cantDias = cal_days_in_month(CAL_GREGORIAN, $this->mesSel, $this->gestionSel);
        $rangos = range(1, $cantDias);
        foreach ($rangos as $rango) {
            $this->diasMes[] = Carbon::createFromDate($this->gestionSel, $this->mesSel, $rango)->format('Y-m-d');
        }
    }

    public function updatedMesSel()
    {
        $this->generaDiasMes();
        $this->reset('resultados','diasMes');
        $this->generaDiasMes();
    }
    public function updatedGestionSel()
    {
        $this->generaDiasMes();
        $this->reset('resultados','diasMes');
        $this->generaDiasMes();
    }
}
