<?php

namespace App\Http\Livewire\Propietarios;

use App\Models\Flujopase;
use Livewire\Component;
use Livewire\WithPagination;

class Flujopases extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '', $inicio, $final, $flujo = '';

    public function mount()
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
    }

    public function render()
    {
        $flujopases = Flujopase::select(
            'flujopases.*',
            'pi.nombre as pase_nombre',
            'cl.nombre as cliente_nombre',
            'pi.residencia_id',
            'pro.nombre as propietario_nombre',
            'tp.nombre as motivo_nombre',
            're.numeropuerta as numeropuerta',
            're.nrolote as nrolote',
            're.piso as piso',
            're.calle as calle',
            're.manzano as manzano',
        )
            ->join('paseingresos as pi', 'pi.id', '=', 'flujopases.paseingreso_id')
            ->join('residencias as re', 're.id', '=', 'pi.residencia_id')
            ->join('clientes as cl', 'cl.id', '=', 're.cliente_id')
            ->join('propietarios as pro', 'pro.id', '=', 're.propietario_id')
            ->join('motivos as tp', 'tp.id', '=', 'pi.motivo_id')
            ->where('pi.nombre', 'LIKE', '%' . $this->search . '%')
            ->where('flujopases.tipo', 'LIKE', '%' . $this->flujo . '%')
            ->where('pro.id', auth()->user()->propietario->id)
            ->whereBetween('flujopases.fecha', [$this->inicio, $this->final]) // <-- filtro de fecha
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.propietarios.flujopases', compact('flujopases'))->with('i', 0);
    }
}
