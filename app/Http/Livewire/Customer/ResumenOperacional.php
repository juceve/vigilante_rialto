<?php

namespace App\Http\Livewire\Customer;

use App\Models\Flujopase;
use App\Models\Hombrevivo;
use App\Models\Novedade;
use App\Models\Registroguardia;
use App\Models\Rondaejecutada;
use App\Models\Tarea;
use App\Models\Visita;
use Livewire\Component;

class ResumenOperacional extends Component
{
    public $cliente_id, $fechaInicio, $fechaFin;
    public function mount($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        $this->fechaInicio = date('Y-m-01');
        $this->fechaFin = date('Y-m-t');
    }
    public function render()
    {

        $resultados = [];
        $rondas = Rondaejecutada::whereDate('inicio', '>=', $this->fechaInicio)
            ->whereDate('inicio', '<=', $this->fechaFin)
            ->where('cliente_id', $this->cliente_id)
            ->count();

        $visitas = Visita::whereDate('created_at', '>=', $this->fechaInicio)
            ->whereDate('created_at', '<=', $this->fechaFin)
            ->whereHas('designacione.turno.cliente', function ($query) {
                $query->where('id', $this->cliente_id);
            })
            ->count();

        $flujopases = Flujopase::whereDate('fecha', '>=', $this->fechaInicio)
            ->whereDate('fecha', '<=', $this->fechaFin)
            ->where('tipo', 'INGRESO')
            ->whereHas('paseingreso.residencia.cliente', function ($query) {
                $query->where('id', $this->cliente_id);
            })
            ->count();

        $panicos = Registroguardia::whereDate('created_at', '>=', $this->fechaInicio)
            ->whereDate('created_at', '<=', $this->fechaFin)
            ->where('cliente_id', $this->cliente_id)
            ->count();
            
        $tareas = Tarea::whereDate('fecha', '>=', $this->fechaInicio)
            ->whereDate('fecha', '<=', $this->fechaFin)
            ->where('cliente_id', $this->cliente_id) // Filtro directo por cliente_id
            ->count();

        $novedades = Novedade::whereDate('fecha', '>=', $this->fechaInicio)
            ->whereDate('fecha', '<=', $this->fechaFin)
            ->whereHas('designacione.turno.cliente', function ($query) {
                $query->where('id', $this->cliente_id); // Asegurarse de usar el ID correcto
            })
            ->count();

        $hombrevivos = Hombrevivo::whereDate('fecha', '>=', $this->fechaInicio)
            ->whereDate('fecha', '<=', $this->fechaFin)
            ->whereHas('intervalo.designacione.turno.cliente', function ($query) {
                $query->where('id', $this->cliente_id); // Asegurarse de usar el ID correcto
            })
            ->count();

        $resultados[] = [
            'rondas' => $rondas,
            'visitas' => $visitas,
            'flujopases' => $flujopases,
            'panicos' => $panicos,
            'tareas' => $tareas,
            'novedades' => $novedades,
            'hombrevivos' => $hombrevivos,
        ];
        return view('livewire.customer.resumen-operacional', compact('resultados'));
    }
}
