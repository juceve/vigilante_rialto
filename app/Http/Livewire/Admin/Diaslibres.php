<?php

namespace App\Http\Livewire\Admin;

use App\Models\Designacione;
use App\Models\Dialibre;
use Livewire\Component;

class Diaslibres extends Component
{
    public $designacione = null, $fecha = "", $observaciones;

    public function mount($id)
    {
        $this->designacione = Designacione::find($id);
    }

    public function render()
    {
        $dias = Dialibre::where('designacione_id', $this->designacione->id)->get();

        return view('livewire.admin.diaslibres', compact('dias'))->with('i', 1)->extends('adminlte::page');
    }
    protected $rules = [
        "fecha" => 'required',
    ];

    public function agregar()
    {
        $this->validate();

        $dia = Dialibre::create([
            'fecha' => $this->fecha,
            'observaciones' => $this->observaciones,
            'designacione_id' => $this->designacione->id,
        ]);
    }

    public function eliminar($id)
    {
        $dia = Dialibre::find($id)->delete();
    }
}
