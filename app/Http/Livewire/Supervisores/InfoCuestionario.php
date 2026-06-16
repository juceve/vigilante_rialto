<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\ChklEjecucione;
use App\Models\Inspeccion;
use Livewire\Component;

class InfoCuestionario extends Component
{
    public $cuestionarioEjecutado;
    public function mount($ejecucion_id,$inspeccion_id)
    {
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);
        $this->cuestionarioEjecutado = ChklEjecucione::find($ejecucion_id);
    }
    public function render()
    {
        return view('livewire.supervisores.info-cuestionario')->extends('layouts.app');
    }
}
