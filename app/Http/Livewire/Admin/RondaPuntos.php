<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Rondapunto;
use App\Models\Ronda;

class RondaPuntos extends Component
{
    public $ronda_id;
    public $descripcion;
    public $latitud;
    public $longitud;
    public $puntos = [];
    public $cliente_latitud;
    public $cliente_longitud;
    public $cliente_id, $cliente;

    protected $rules = [
        'descripcion' => 'required|string',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
    ];

    protected $listeners = ['eliminarPunto'];

    public function mount($ronda_id)
    {
        $this->ronda_id = $ronda_id;
        $this->cargarPuntos();

        // Obtener coordenadas del cliente
        $ronda = Ronda::with('cliente')->find($ronda_id);
        if ($ronda && $ronda->cliente) {
            $this->cliente_latitud = $ronda->cliente->latitud;
            $this->cliente_longitud = $ronda->cliente->longitud;
            $this->cliente_id = $ronda->cliente->id;
            $this->cliente = $ronda->cliente;
        }
    }

    public function cargarPuntos()
    {
        $this->puntos = Rondapunto::where('ronda_id', $this->ronda_id)->get();
    }

    public function guardarPunto()
    {
        $this->validate();

        $punto = Rondapunto::create([
            'ronda_id' => $this->ronda_id,
            'descripcion' => $this->descripcion,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud
        ]);

        $this->reset(['descripcion', 'latitud', 'longitud']);
        $this->cargarPuntos();
        $this->dispatchBrowserEvent('punto-guardado', ['punto' => $punto]);
    }

    public function eliminarPunto($puntoId)
    {
        $punto = Rondapunto::find($puntoId);
        if ($punto) {
            $punto->delete();
            $this->cargarPuntos();
            $this->dispatchBrowserEvent('punto-eliminado', ['puntoId' => $puntoId]);
        }
    }

    public function render()
    {
        return view('livewire.admin.ronda-puntos')
            ->extends('adminlte::page');
    }
}
