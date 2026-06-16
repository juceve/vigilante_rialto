<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Flujopase;
use App\Models\Residencia;
use Livewire\Component;

class ControlFlujos extends Component
{
    public $inicio, $final, $designacione_id, $search = '', $residencia;

    public function mount()
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
    }

    public function render()
    {
        $designacione = Designacione::find($this->designacione_id);
        $flujopases = Flujopase::select(
            'flujopases.*',
            'pi.nombre as pase_nombre',
            'pi.residencia_id',
            'pro.nombre as propietario_nombre',
            'tp.nombre as motivo_nombre',
        )
            ->join('paseingresos as pi', 'pi.id', '=', 'flujopases.paseingreso_id')
            ->join('residencias as re', 're.id', '=', 'pi.residencia_id')
            ->join('propietarios as pro', 'pro.id', '=', 're.propietario_id')
            ->join('motivos as tp', 'tp.id', '=', 'pi.motivo_id')
            ->where('pi.nombre', 'LIKE', '%' . $this->search . '%')
            ->where('re.cliente_id', $designacione->turno->cliente->id)
            ->whereBetween('flujopases.fecha', [$this->inicio, $this->final]) // <-- filtro de fecha
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.vigilancia.control-flujos', compact('flujopases'))->with('i', 0)->extends('layouts.app');
    }

    public function detalleResidencia($residencia_id)
    {
        $this->residencia = Residencia::find($residencia_id);
        if ($this->residencia) {
            $this->emit('abrirModal');
        } else {
            $this->emit('error', 'Ha ocurrido un error');
        }
    }
}
