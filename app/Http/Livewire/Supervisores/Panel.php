<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Inspeccion;
use Illuminate\Support\Facades\Session;
use Livewire\Component;


class Panel extends Component
{
    public $inspeccionActiva;
    public $procesando = false;

    public function mount($inspeccion_id)
    {
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);

        if (is_null($this->inspeccionActiva)) {
            return redirect()->route('home')->with('error', 'No existe una Inspección activa');
        }
    }

    public function render()
    {
        return view('livewire.supervisores.panel')->extends('layouts.app');
    }

    protected $listeners = ['finalizar' => 'finalizarInspeccionActiva'];

    public function finalizarInspeccionActiva()
    {
        if ($this->inspeccionActiva) {
            $this->procesando = true;
            $this->inspeccionActiva->fin = date('Y-m-d H:i:s');
            $this->inspeccionActiva->status = false;
            $this->inspeccionActiva->save();

            Session::put('inspeccion_activa', null);
            Session::forget('inspeccion_activa');

            return redirect()->route('home')->with('success', 'Inspección finalizada correctamente!');
        } else {
            $this->emit('warning', 'No existen inspecciones activas.');
        }
    }

    public function iniciarCuestionario()
    {
        $cuestioinarios = $this->inspeccionActiva->cliente->cuestionarios;
        $cuestionarios = $cuestioinarios->toArray();
        if ($cuestioinarios->count() == 0) {
            $this->emit('warning', 'El cliente no tiene cuestionarios asignados.');
            return;
        } else {
            $this->emit('openPreguntaCuestionario',$cuestioinarios);
        }
    }
}
