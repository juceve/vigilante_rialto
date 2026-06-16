<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Designacione;
use App\Models\Inspeccion;
use App\Models\Rondaejecutada;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class CtrlRondas extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";

    public $inspeccionActiva, $selRonda,$modalUrl='';
    public $searchEmpleado = '', $fecha = '';

    public $procesando = false;

    public function mount($inspeccion_id)
    {
        $this->fecha = date('Y-m-d');
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);

    }
    public function render()
    {
        $rondas = Rondaejecutada::where('cliente_id', $this->inspeccionActiva->cliente_id)->whereDate('inicio', $this->fecha)->paginate(5);
        return view('livewire.supervisores.ctrl-rondas', compact('rondas'))->extends('layouts.app');
    }

    public function verInfo($rondaejecutada_id)
    {
        $this->selRonda = Rondaejecutada::find($rondaejecutada_id);
         $this->modalUrl = route('admin.recorrido_ronda', $rondaejecutada_id);
        $this->emit('openModal');
    }

    public function updatedFecha(){
        $this->resetPage();
    }

}
