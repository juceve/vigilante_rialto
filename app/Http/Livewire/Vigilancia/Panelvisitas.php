<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Visita;
use App\Models\Vwvisita;
use Livewire\Component;

class Panelvisitas extends Component
{
    public $designacion, $cliente, $visitas;
    public function mount($designacion)
    {
        $this->designacion = Designacione::find($designacion);
        $this->cliente = $this->designacion->turno->cliente;
        $this->visitas = Vwvisita::where([
            ['fechaingreso', date('Y-m-d')],
            ['cliente_id', $this->cliente->id],
        ])->get();
    }

    public function render()
    {
        return view('livewire.vigilancia.panelvisitas')->extends('layouts.app');
    }
}
