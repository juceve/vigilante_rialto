<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Visita;
use App\Models\Vwvisita;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;


class RegSalida extends Component
{
    public  $designacion, $visitas, $search = '', $visita;
    public $docidentidad = '', $nombrevisitante = '', $residente = '', $nrovivienda = '', $motivo = '', $otros = '', $observaciones = '', $created_at = '', $img = '';


    public function mount($designacion)
    {
        $this->designacion = Designacione::find($designacion);
        $this->visitas = Vwvisita::where('estado', 1)->where('cliente_id', $this->designacion->turno->cliente->id)->orderBy('id', 'DESC')->get();
    }

    public function updatedSearch()
    {
        $this->visitas = Vwvisita::where([
            ['estado', 1],
            ['cliente_id', $this->designacion->turno->cliente->id],
            ['visitante', 'LIKE', '%' . $this->search . '%'],
        ])
            ->orWhere([
                ['estado', 1],
                ['cliente_id', $this->designacion->turno->cliente->id],
                ['docidentidad', 'LIKE', '%' . $this->search . '%'],

            ])
            ->orderBy('id', 'DESC')->get();
    }

    public function render()
    {
        return view('livewire.vigilancia.reg-salida')->extends('layouts.app');
    }
}
