<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ronda;

class ListadoRondas extends Component
{
    use WithPagination;

    public $cliente;
    public $nuevo_nombre = '';
    public $nuevo_descripcion = '';
    public $nuevo_estado = 1;
    public $editMode = false;
    public $ronda_id_editar = null;
    public $showModal = false;

    protected $paginationTheme = 'bootstrap';

    public function mount($id)
    {
        $this->cliente = Cliente::findOrFail($id);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showModalCrear()
    {
        $this->resetValidation();
        $this->editMode = false;
        $this->nuevo_nombre = '';
        $this->nuevo_descripcion = '';
        $this->nuevo_estado = 1;
        $this->ronda_id_editar = null;
        $this->dispatchBrowserEvent('abrir-modal-crear');
    }

    public function showModalEditar($id)
    {
        $this->resetValidation();
        $ronda = \App\Models\Ronda::findOrFail($id);
        $this->editMode = true;
        $this->ronda_id_editar = $ronda->id;
        $this->nuevo_nombre = $ronda->nombre;
        $this->nuevo_descripcion = $ronda->descripcion;
        $this->nuevo_estado = $ronda->estado;
        $this->dispatchBrowserEvent('abrir-modal-crear');
    }

    public function crearRonda()
    {
        $this->validate([
            'nuevo_nombre' => 'required|string|max:255',
            'nuevo_descripcion' => 'nullable|string',
            'nuevo_estado' => 'required|boolean',
        ]);

        \App\Models\Ronda::create([
            'cliente_id' => $this->cliente->id,
            'nombre' => $this->nuevo_nombre,
            'descripcion' => $this->nuevo_descripcion,
            'estado' => $this->nuevo_estado,
        ]);

        $this->dispatchBrowserEvent('cerrar-modal-crear');
        $this->emitSelf('$refresh');
    }

    public function actualizarRonda()
    {
        $this->validate([
            'nuevo_nombre' => 'required|string|max:255',
            'nuevo_descripcion' => 'nullable|string',
            'nuevo_estado' => 'required|boolean',
        ]);

        $ronda = \App\Models\Ronda::findOrFail($this->ronda_id_editar);
        $ronda->update([
            'nombre' => $this->nuevo_nombre,
            'descripcion' => $this->nuevo_descripcion,
            'estado' => $this->nuevo_estado,
        ]);

        $this->dispatchBrowserEvent('cerrar-modal-crear');
        $this->emitSelf('$refresh');
    }

    public function render()
    {
        $rondas = Ronda::where('cliente_id', $this->cliente->id)
            ->get();

        return view('livewire.admin.listado-rondas', compact('rondas'))->extends('adminlte::page');
    }

    protected $listeners = ['eliminarRonda'];

    public function eliminarRonda($id)
    {
        $ronda = Ronda::find($id);
        $ronda->delete();
        $this->emit('success', 'Ronda eliminada correctamente');
    }

}
