<?php

namespace App\Http\Livewire\Vigilancia;

use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Rrhhcontrato;
use App\Models\SupBoleta;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Boletas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $designacione, $empleado, $contratoActivo, $procesando = false, $selBoleta = null;
    public $monto = '', $motivo = '', $file;

    protected $listeners = ['store', 'view', 'anular'];

    public function mount()
    {
        $hoy = Carbon::now()->toDateString();

        $this->designacione = Designacione::find(session('designacion-oper'));
        $empleado_id = $this->designacione ? $this->designacione->empleado_id : null;
        $this->empleado = Empleado::find($empleado_id);

        $this->contratoActivo = Rrhhcontrato::where('empleado_id', $this->empleado->id)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $hoy);
            })
            ->where('activo', true)
            ->orderBy('fecha_inicio', 'asc')
            ->first();
    }

    public function render()
    {
        $boletas = SupBoleta::where('empleado_id', $this->empleado->id)
        ->whereDate('fechahora','>=', $this->designacione->fechaInicio)
        ->whereDate('fechahora','<=', $this->designacione->fechaFin)
        ->orderBy('id','Desc')
        ->paginate(10);
        
        return view('livewire.vigilancia.boletas', compact('boletas'))->extends('layouts.app');
    }

    public function verInfo($boleta_id) {
        $this->selBoleta = SupBoleta::find($boleta_id);
        $this->emit('openModal');
    }
}
