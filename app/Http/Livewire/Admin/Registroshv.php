<?php

namespace App\Http\Livewire\Admin;

use App\Models\Designacione;
use App\Models\Hombrevivo;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class Registroshv extends Component
{
    public $designacione = null;
    public $registros = null, $selMarca = null, $fecha = "", $hora = "", $anotaciones = "", $lat = "", $lng = "";

    public function mount($id)
    {
        $this->designacione = Designacione::find($id);
        $intervalos = $this->designacione->intervalos->toArray();
        $this->registros = registrosHV($this->designacione->id);
        // dd($registros);
    }

    public function render()
    {
        return view('livewire.admin.registroshv')->extends('adminlte::page');
    }

    public function cargaPunto($punto_id)
    {
        $this->selMarca = Hombrevivo::find($punto_id);
        $this->fecha = $this->selMarca->fecha;
        $this->hora = $this->selMarca->hora;
        $this->anotaciones = $this->selMarca->anotaciones;
        $this->lat = $this->selMarca->lat;
        $this->lng = $this->selMarca->lng;
        
    }

    public function pdfHV()
    {
        $registros = $this->registros;
        $designacione = $this->designacione;
        
        $pdf = Pdf::loadView('admin.designacione.pdfHV', compact('registros','designacione'));
        return $pdf->stream();
    }
}
