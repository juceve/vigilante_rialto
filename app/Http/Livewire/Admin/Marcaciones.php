<?php

namespace App\Http\Livewire\Admin;

use App\Models\Asistencia;
use App\Models\Designacione;
use App\Models\Marcacione;
use Livewire\Component;

class Marcaciones extends Component
{
    public $designacione_id, $fecha = "", $hora = "", $lat = "", $lng = "";
    public $marcado = null;

    public function render()
    {
        $designacione = Designacione::find($this->designacione_id);
        // $marcaciones = tablaMarcaciones($designacione->id);
        $marcaciones = Asistencia::where([
            ['designacione_id', $designacione->id],
        ])->get();

        return view('livewire.admin.marcaciones', compact('designacione', 'marcaciones'));
    }

    public function cargar($asistencia_id, $tipo)
    {
        $this->reset(['marcado', 'lat', 'fecha', 'hora', 'lng']);
        $this->marcado = Asistencia::find($asistencia_id);
        $this->fecha = $this->marcado->fecha;
        // $this->hora = substr($this->marcado->hora, 11, 5);
        if ($tipo == 1) {
            $this->hora = substr($this->marcado->ingreso, 11, 5);
            $this->lat = $this->marcado->latingreso;
            $this->lng = $this->marcado->lngingreso;
        } else {
            $this->hora = substr($this->marcado->salida, 11, 5);
            $this->lat = $this->marcado->latsalida;
            $this->lng = $this->marcado->lngsalida;
        }
    }
}
