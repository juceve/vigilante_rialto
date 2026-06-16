<?php

namespace App\Http\Livewire\Customer;

use App\Models\Cliente;
use App\Models\Residencia;
use App\Models\Usercliente;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoSolicitudes extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $cliente_id, $cliente;
    public $search = '';
    public $perPage = 5;
    public $sortField = 'nombre'; // valor por defecto
    public $sortDirection = 'asc';
    public $perPageOptions = [5, 10, 15, 25, 50];
    public $linkSolicitud = '';


    public function mount()
    {
        $usercliente = Usercliente::where('user_id', auth()->user()->id)->first();
        $this->cliente_id = $usercliente->cliente_id;
        $this->cliente = Cliente::findOrFail($this->cliente_id);
        $encryptedId = Crypt::encryptString($this->cliente_id);
        $this->linkSolicitud = route('regpropietario', $encryptedId);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Residencia::select(
            'residencias.cedula_propietario',
            DB::raw('COALESCE(propietarios.id, "0") as propietario_id'),
            DB::raw('COALESCE(propietarios.nombre, "Sin vínculo") as nombre'),
            DB::raw('COUNT(residencias.id) as cantidad')
        )
            ->leftJoin('propietarios', 'propietarios.id', '=', 'residencias.propietario_id')
            ->where('residencias.cliente_id', $this->cliente->id)
            ->where('residencias.estado', 'CREADO')
            ->groupBy('residencias.cedula_propietario', 'propietarios.id', 'propietarios.nombre');

        // filtro por estado
        if (!empty($this->filtro_estado)) {
            $query->where('residencias.estado', $this->filtro_estado);
        }

        // búsqueda
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('residencias.cedula_propietario', 'like', '%' . $this->search . '%')
                    ->orWhere('propietarios.nombre', 'like', '%' . $this->search . '%');
            });
        }

        // ordenamiento dinámico
        if ($this->sortField === 'cantidad') {
            $query->orderByRaw("COUNT(residencias.id) {$this->sortDirection}");
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $residencias = $query->paginate($this->perPage);

        return view('livewire.customer.listado-solicitudes', compact('residencias'))
            ->extends('layouts.clientes');
    }
}
