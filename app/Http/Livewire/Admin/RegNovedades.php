<?php

namespace App\Http\Livewire\Admin;

use App\Models\Designacione;
use App\Models\Novedade;
use Livewire\Component;

class RegNovedades extends Component
{
    public $designacione = null, $lat = "", $lng = "", $contenido = "", $fecha = "", $hora = "", $imagenes = null;

    public function mount($id)
    {
        $this->designacione = Designacione::find($id);
    }

    public function render()
    {
        return view('livewire.admin.reg-novedades')->with('i', 1)->extends('adminlte::page');
    }

    public function cargaDatos($id){
        $novedad = Novedade::find($id);
        $this->fecha = $novedad->fecha;
        $this->hora = $novedad->hora;
        $this->contenido = $novedad->contenido;
        $this->lat = $novedad->lat;
        $this->lng = $novedad->lng;

        $this->imagenes = $novedad->imgnovedades;
    }
}
