<?php

namespace App\Http\Livewire\Admin;

use App\Models\Designacione;
use App\Models\Regronda;
use Livewire\Component;

class Inforonda extends Component
{
    public $designacione_id,$fecha="", $hora="", $anotaciones="";
    public $punto = null,$imgrondas=null;


    public function render()
    {
        $designacione = Designacione::find($this->designacione_id);
        $rondas = tablaRondas($designacione->id);
        return view('livewire.admin.inforonda', compact('designacione', 'rondas'));
    }

    public function cargaPunto($punto_id)
    {
        $this->reset(['punto','imgrondas','fecha','hora','anotaciones']);
        $this->punto = Regronda::find($punto_id);        
        $this->fecha = $this->punto->fecha;
        $this->hora = $this->punto->hora;
        $this->anotaciones = $this->punto->anotaciones;
        $this->imgrondas = $this->punto->imgrondas;
    }
}
