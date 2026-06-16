<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Turno;
use App\Models\Cliente;
use App\Models\Ctrlpunto;

class PuntosControlV2 extends Component
{
    public $turno;
    public $cliente;
    public $puntos = [];
    public $pnts = "";
    
    // Campos del formulario
    public $nombre = "";
    public $hora = "";
    public $latitud = "";
    public $longitud = "";
    
    // Estado del formulario
    public $showForm = false;
    public $editingId = null;
    
    protected $rules = [
        'nombre' => 'required|string|max:255',
        'hora' => 'required',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre del punto es obligatorio',
        'hora.required' => 'La hora es obligatoria',
        'latitud.required' => 'Debe seleccionar una ubicación en el mapa',
        'longitud.required' => 'Debe seleccionar una ubicación en el mapa',
    ];

    public function mount($turnoId)
    {
        $this->turno = Turno::with('cliente')->findOrFail($turnoId);
        $this->cliente = $this->turno->cliente;
        $this->loadPuntos();
    }

    public function loadPuntos()
    {
        $this->puntos = Ctrlpunto::where('turno_id', $this->turno->id)
            ->orderBy('hora')
            ->get();
        
        $this->generatePntsString();
    }

    public function generatePntsString()
    {
        $pntsArray = [];
        foreach ($this->puntos as $punto) {
            $pntsArray[] = "{$punto->nombre}|{$punto->latitud}|{$punto->longitud}|{$punto->hora}";
        }
        $this->pnts = implode('$', $pntsArray);
    }

    public function setLocation($lat, $lng)
    {
        // CORREGIDO: Convertir a float para asegurar que sean números
        $this->latitud = (float) $lat;
        $this->longitud = (float) $lng;
        $this->showForm = true;
    }

    public function registrarPunto()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                // Editar punto existente
                $punto = Ctrlpunto::findOrFail($this->editingId);
                $punto->update([
                    'nombre' => $this->nombre,
                    'hora' => $this->hora,
                    'latitud' => (float) $this->latitud, // CORREGIDO: Convertir a float
                    'longitud' => (float) $this->longitud, // CORREGIDO: Convertir a float
                ]);
                $this->emit('puntoActualizado');
                session()->flash('message', 'Punto actualizado correctamente');
            } else {
                // Crear nuevo punto
                Ctrlpunto::create([
                    'turno_id' => $this->turno->id,
                    'nombre' => $this->nombre,
                    'hora' => $this->hora,
                    'latitud' => (float) $this->latitud, // CORREGIDO: Convertir a float
                    'longitud' => (float) $this->longitud, // CORREGIDO: Convertir a float
                ]);
                $this->emit('puntoRegistrado');
                session()->flash('message', 'Punto registrado correctamente');
            }

            // NO llamar resetForm() ni loadPuntos() porque recargaremos la página
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el punto: ' . $e->getMessage());
        }
    }

    public function editarPunto($id)
    {
        $punto = Ctrlpunto::findOrFail($id);
        
        $this->editingId = $id;
        $this->nombre = $punto->nombre;
        $this->hora = $punto->hora;
        // CORREGIDO: Asegurar que sean números flotantes
        $this->latitud = (float) $punto->latitud;
        $this->longitud = (float) $punto->longitud;
        $this->showForm = true;
        
        // CORREGIDO: Pasar números flotantes al JavaScript
        $this->emit('editarPuntoEnMapa', (float) $punto->latitud, (float) $punto->longitud);
    }

    public function eliminarPunto($id)
    {
        try {
            Ctrlpunto::findOrFail($id)->delete();
            $this->emit('puntoEliminado');
            session()->flash('message', 'Punto eliminado correctamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el punto');
        }
    }

    public function resetForm()
    {
        $this->nombre = "";
        $this->hora = "";
        $this->latitud = "";
        $this->longitud = "";
        $this->showForm = false;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function cancelar()
    {
        $this->resetForm();
        $this->emit('cancelarEdicion');
    }

    public function render()
    {
        return view('livewire.admin.puntos-control-v2')
            ->extends('adminlte::page')
            ->section('content');
    }
}