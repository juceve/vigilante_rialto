<?php

namespace App\Http\Livewire\Admin;

use App\Models\Rrhhbono;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Rrhhsueldo;
use App\Models\Rrhhsueldoempleado;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManageSueldos extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $filterGestion = '';
    public $filterMes = '';
    public $filterEstado = '';

    // Para crear/editar
    public $editMode = false;
    public $sueldo_id;
    public $gestion;
    public $titulo;
    public $mes;
    public $fecha;
    public $hora;
    public $user_id;
    public $estado = 'CREADO';

    public $sortField = 'id';
    public $sortDirection = 'desc';

    protected $listeners = ['deleteSueldo'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'gestion' => 'required|integer',
        'mes' => 'required|integer|min:1|max:12',
        // 'fecha' => 'required|date',
        // 'hora' => 'required',
        // 'user_id' => 'required|exists:users,id', // No se valida porque se asigna automáticamente
        // 'estado' => 'required|in:CREADO,PROCESADO,ANULADO',
    ];

    public function mount()
    {
        $this->gestion = date('Y');
    }

    public function updatingFilterGestion()
    {
        $this->resetPage();
    }
    public function updatingFilterMes()
    {
        $this->resetPage();
    }
    public function updatingFilterEstado()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['sueldo_id', 'gestion', 'mes', 'fecha', 'hora', 'user_id', 'estado', 'editMode']);
        $this->estado = 'CREADO';
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->user_id = Auth::id();
        $this->dispatchBrowserEvent('show-modal-sueldo');
    }

    public function openEditModal($id)
    {
        $sueldo = Rrhhsueldo::findOrFail($id);
        $this->sueldo_id = $sueldo->id;
        $this->gestion = $sueldo->gestion;
        $this->mes = $sueldo->mes;
        $this->titulo = $sueldo->titulo;
        $this->fecha = $sueldo->fecha;
        $this->hora = $sueldo->hora;
        $this->user_id = $sueldo->user_id;
        $this->estado = $sueldo->estado;
        $this->editMode = true;
        $this->dispatchBrowserEvent('show-modal-sueldo');
    }

    public function closeModal()
    {
        $this->editMode = false;
        $this->resetForm();
        $this->gestion = date('Y');
    }

    public function saveSueldo()
    {
        $this->validate();
        if ($this->editMode && $this->sueldo_id) {
            $sueldo = Rrhhsueldo::findOrFail($this->sueldo_id);
            $sueldo->estado = $this->estado;
            if (!is_null($this->titulo)) {
                $sueldo->titulo = $this->titulo;
            }


            // restablecer los bonos pagados si el sueldo se anula
            if ($this->estado === 'ANULADO') {
                $sueldoempleados = Rrhhsueldoempleado::where('rrhhsueldo_id', $this->sueldo_id)->get();
                foreach ($sueldoempleados as $sueldoempleado) {
                    if ($sueldoempleado->ids_bonos != NULL) {
                        $ids_bonos = explode('|', $sueldoempleado->ids_bonos);
                        Rrhhbono::whereIn('id', $ids_bonos)->update(['pagado' => false]);
                    }
                }
                // Elimina todos los registros de Rrhhsueldoempleado relacionados con este sueldo
                $sueldo->rrhhsueldoempleados()->delete();
            }
        } else {
            $sueldo = new Rrhhsueldo();
            $sueldo->user_id = Auth::id();
            $sueldo->fecha = date('Y-m-d');
            $sueldo->hora = date('H:i:s');
        }
        $sueldo->gestion = $this->gestion;
        $sueldo->mes = $this->mes;

        // $sueldo->user_id = $this->user_id; // Solo editable en creación

        $sueldo->save();
        $this->dispatchBrowserEvent('hide-modal-sueldo');
        $this->resetForm();
        $this->emit('success', $this->editMode ? 'Sueldo actualizado correctamente.' : 'Sueldo creado correctamente.');
    }

    public $deleteId = null;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatchBrowserEvent('swal:confirm-delete');
    }

    public function deleteSueldo()
    {
        if ($this->deleteId) {
            \App\Models\Rrhhsueldo::find($this->deleteId)?->delete();
            $this->deleteId = null;
            $this->emit('success', 'Registro eliminado correctamente.');
        }
    }

    public function render()
    {
        $query = Rrhhsueldo::query();
        if ($this->filterGestion !== '') {
            $query->where('gestion', $this->filterGestion);
        }
        if ($this->filterMes !== '') {
            $query->where('mes', $this->filterMes);
        }
        if ($this->filterEstado !== '') {
            $query->where('estado', $this->filterEstado);
        }
        $sueldos = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
        $gestiones = Rrhhsueldo::select('gestion')->distinct()->orderBy('gestion', 'desc')->pluck('gestion');
        $meses = Rrhhsueldo::select('mes')->distinct()->orderBy('mes')->pluck('mes');
        $usuarios = User::orderBy('name')->get();
        return view('livewire.admin.manage-sueldos', [
            'sueldos' => $sueldos,
            'gestiones' => $gestiones,
            'meses' => $meses,
        ])->extends('adminlte::page');

        // return view('mantenimiento  ')->extends('adminlte::page');
    }
}
