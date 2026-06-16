<?php

namespace App\Http\Livewire\Admin;

use App\Models\Rrhhcontrato;
use Carbon\Carbon;
use Livewire\Component;

class ListadoVencimientoContratos extends Component
{


    public function render()
    {
        $hoy = Carbon::today();
        $limite = Carbon::today()->addDays(30);
        $contratos = Rrhhcontrato::whereNotNull('fecha_fin') // solo los que tienen fecha fin
            ->whereBetween('fecha_fin', [$hoy, $limite])
            ->where('activo', true) // opcional: solo contratos activos
            ->with(['empleado', 'rrhhtipocontrato', 'rrhhcargo']) // si tienes relaciones definidas
            ->get();
        return view('livewire.admin.listado-vencimiento-contratos',compact('contratos'));
    }
}
