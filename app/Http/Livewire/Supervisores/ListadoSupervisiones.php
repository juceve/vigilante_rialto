<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\ChklEjecucione;
use App\Models\Inspeccion;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoSupervisiones extends Component
{
    use WithPagination;    
    protected $paginationTheme = "bootstrap";
    public $inspeccionActiva, $fechaInicio, $fechaFin;

    public function mount($inspeccion_id)
    {
        $this->fechaInicio = date('Y-m-d');
        $this->fechaFin = date('Y-m-d');
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);

        if (is_null($this->inspeccionActiva)) {
            return redirect()->route('home')->with('error', 'No existe una Inspección activa');
        }
    }

    public function render()
    {
        $cliente_id = $this->inspeccionActiva->cliente_id;
        $ejecuciones = ChklEjecucione::whereHas('ChklListaschequeo', function ($q) use ($cliente_id) {
            $q->where('cliente_id', $cliente_id);
        })
            ->whereBetween('fecha', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay(),
            ])
            ->orderBy('id','desc')
            ->paginate(3);
        return view('livewire.supervisores.listado-supervisiones', compact('ejecuciones'))->extends('layouts.app');
    }

    public function iniciarCuestionario()
    {
        $cuestioinarios = $this->inspeccionActiva->cliente->cuestionarios;
        $cuestionarios = $cuestioinarios->toArray();
        if ($cuestioinarios->count() == 0) {
            $this->emit('warning', 'El cliente no tiene cuestionarios asignados.');
            return;
        } else {
            $this->emit('openPreguntaCuestionario', $cuestioinarios);
        }
    }

    public function updatedFechaInicio()
    {
        if ($this->fechaInicio > $this->fechaFin) {
            $this->fechaFin = $this->fechaInicio;
        }
        $this->resetPage();
    }
    public function updatedFechaFin()
    {
        if ($this->fechaFin < $this->fechaInicio) {
            $this->fechaInicio = $this->fechaFin;
        }
        $this->resetPage();
    }
}
