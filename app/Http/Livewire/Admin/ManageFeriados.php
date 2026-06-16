<?php

namespace App\Http\Livewire\Admin;

use App\Models\Rrhhferiado;
use Livewire\Component;
use Livewire\WithPagination;

class ManageFeriados extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $dia_id, $nombre, $fecha, $fecha_inicio, $fecha_fin, $recurrente = false, $factor = 1, $activo = true;

    public $busqueda = '';
    public $perPage = 5; // filas por defecto
    public $perPageOptions = [5, 10, 25, 50];

    protected $updatesQueryString = ['busqueda', 'perPage'];
    protected $rules = [
        'nombre' => 'required|string|max:100',
        'fecha' => 'nullable|date',
        'fecha_inicio' => 'nullable|date',
        'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        'factor' => 'required|numeric|min:0',
        'activo' => 'boolean',
        'recurrente' => 'boolean',
    ];



    public function updatingBusqueda()
    {
        $this->resetPage(); // para no quedarte en páginas vacías
    }

    public function render()
    {
        $dias = Rrhhferiado::query()
            ->where('nombre', 'like', "%{$this->busqueda}%")
            ->orderBy('fecha', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.manage-feriados', compact('dias'))->extends('adminlte::page');
    }

    public function resetInput()
    {
        $this->reset(['dia_id', 'nombre', 'fecha', 'fecha_inicio', 'fecha_fin', 'recurrente', 'factor', 'activo']);
    }

    public function store()
    {
        $this->validate();

        if ($this->fecha && ($this->fecha_inicio || $this->fecha_fin)) {
            $this->addError('fecha', 'No puede llenar fecha y rango al mismo tiempo.');
            return;
        }

        if (!$this->fecha && !$this->fecha_inicio) {
            $this->addError('fecha_inicio', 'Debe indicar una fecha o un rango.');
            return;
        }

        // Validar que si escogió rango, estén ambos campos llenos
        if (!$this->fecha && ($this->fecha_inicio xor $this->fecha_fin)) {
            $this->addError('fecha_fin', 'Debe completar tanto la fecha inicio como la fecha fin del rango.');
            return;
        }
        $fecha = $this->fecha ?: NULL;
        $fecha_inicio = $this->fecha_inicio ?: NULL;
        $fecha_fin = $this->fecha_fin ?: NULL;
        Rrhhferiado::create([
            'nombre' => $this->nombre,
            'fecha' => $fecha,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'recurrente' => $this->recurrente,
            'factor' => $this->factor,
            'activo' => $this->activo,
        ]);

        $this->emit('success', 'Feriado guardado correctamente.');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $dia = RrhhFeriado::findOrFail($id);
        $this->dia_id = $dia->id;
        $this->nombre = $dia->nombre;
        $this->fecha = $dia->fecha;
        $this->fecha_inicio = $dia->fecha_inicio;
        $this->fecha_fin = $dia->fecha_fin;
        $this->recurrente = $dia->recurrente;
        $this->factor = $dia->factor;
        $this->activo = $dia->activo;
    }

    public function update()
    {
        $this->validate();

        if ($this->fecha && ($this->fecha_inicio || $this->fecha_fin)) {
            $this->addError('fecha', 'No puede llenar fecha y rango al mismo tiempo.');
            return;
        }

        if (!$this->fecha && !$this->fecha_inicio) {
            $this->addError('fecha', 'Debe indicar una fecha o un rango.');
            return;
        }
        $fecha = $this->fecha ?: null;
        $fecha_inicio = $this->fecha_inicio ?: null;
        $fecha_fin = $this->fecha_fin ?: null;

        $dia = RrhhFeriado::findOrFail($this->dia_id);
        $dia->update([
            'nombre' => $this->nombre,
            'fecha' => $fecha,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'recurrente' => $this->recurrente,
            'factor' => $this->factor,
            'activo' => $this->activo,
        ]);

        session()->flash('message', 'Día especial actualizado correctamente.');
        $this->resetInput();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete($id)
    {
        RrhhFeriado::find($id)?->delete();
        session()->flash('message', 'Día especial eliminado correctamente.');
    }
}
