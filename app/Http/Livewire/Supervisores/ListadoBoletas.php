<?php

namespace App\Http\Livewire\Supervisores;

use App\Models\Inspeccion;
use App\Models\SupBoleta;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoBoletas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $inspeccionActiva, $fechaInicio, $fechaFin, $search = '', $selBoleta;

    public function mount($inspeccion_id)
    {
        $this->fechaInicio = date('Y-m-d');
        $this->fechaFin = date('Y-m-d');
        $this->inspeccionActiva = Inspeccion::find($inspeccion_id);
    }
    public function render()
    {
        $resultados = SupBoleta::query()
            ->where('cliente_id', $this->inspeccionActiva->cliente_id)
            ->when($this->fechaInicio && $this->fechaFin, function ($q) {
                $q->whereBetween('fechahora', [
                    Carbon::parse($this->fechaInicio)->startOfDay(),
                    Carbon::parse($this->fechaFin)->endOfDay(),
                ]);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('empleado', function ($q) {
                    $q->where(function ($q) {
                        $q->where('nombres', 'like', "%{$this->search}%")
                            ->orWhere('apellidos', 'like', "%{$this->search}%");
                    });
                });
            })
            ->orderBy('id','desc')
            ->paginate(5);

        return view('livewire.supervisores.listado-boletas', compact('resultados'))->extends('layouts.app');
    }

    public function view($supboleta_id)
    {
        $this->selBoleta = SupBoleta::find($supboleta_id);
        $this->emit('openModal');
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

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
;