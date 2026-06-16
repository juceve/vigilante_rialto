<?php

namespace App\Http\Livewire\Propietarios;

use App\Models\Motivo;
use App\Models\Paseingreso;
use App\Models\Tipopase;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Pases extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $nombre = "", $cedula = "", $tipopase_id = "", $fecha_inicio = "", $fecha_fin = "", $detalles = "", $residencia_id = "";
    public $search = '', $paseingreso, $residencia;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        
        $motivos = Motivo::all();
        $paseingresos = \App\Models\Paseingreso::whereHas('residencia', function ($query) {
            $query->where('estado', 'VERIFICADO')
                ->where('propietario_id', auth()->user()->propietario->id);
        })
            ->where('nombre', 'LIKE', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(7);
        return view('livewire.propietarios.pases', compact('paseingresos', 'motivos'))
            ->extends('layouts.propietarios');
    }

    public function verDetalles($paseId)
    {
        $this->paseingreso = Paseingreso::find($paseId);
        $this->residencia = $this->paseingreso->residencia;
        $this->emit('openModal');
    }

    protected $listeners = ['deshabilitar'];

    public function deshabilitar($paseId)
    {

        $paseingreso = Paseingreso::find($paseId);
        if (!$paseingreso->estado) {
            return;
        }
        $paseingreso->estado = false;
        $paseingreso->save();
        $this->emit('success', 'Pase deshabilitado con exito!');
    }
}
