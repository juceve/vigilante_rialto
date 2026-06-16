<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Turnoguardia;

class ManageTurnoguardias extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['confirmDelete' => 'delete'];

    public $turn_id, $nombre, $horainicio, $horafin;

    public $busqueda = '';
    public $perPage = 5;
    public $perPageOptions = [5, 10, 25, 50];

    protected $updatesQueryString = ['busqueda', 'perPage'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'horainicio' => 'required',
        'horafin' => 'required',
    ];

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function render()
    {
        $turnos = Turnoguardia::query()
            ->where('nombre', 'like', "%{$this->busqueda}%")
            ->orderBy('nombre', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.manage-turnoguardias', compact('turnos'))->extends('adminlte::page');
    }

    public function resetInput()
    {
        $this->reset(['turn_id', 'nombre', 'horainicio', 'horafin']);
    }

    public function store()
    {
        $this->validate();

        Turnoguardia::create([
            'nombre' => $this->nombre,
            'horainicio' => $this->horainicio,
            'horafin' => $this->horafin,
        ]);

        $this->emit('success', 'Turno guardia creado correctamente.');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $turno = Turnoguardia::findOrFail($id);
        $this->turn_id = $turno->id;
        $this->nombre = $turno->nombre;
        $this->horainicio = $turno->horainicio;
        $this->horafin = $turno->horafin;
    }

    public function update()
    {
        $this->validate();

        $turno = Turnoguardia::findOrFail($this->turn_id);
        $turno->update([
            'nombre' => $this->nombre,
            'horainicio' => $this->horainicio,
            'horafin' => $this->horafin,
        ]);

        session()->flash('message', 'Turno actualizado correctamente.');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete($id)
    {
        Turnoguardia::find($id)?->delete();
        $this->emit('success', 'Turno eliminado correctamente.');
    }
}
