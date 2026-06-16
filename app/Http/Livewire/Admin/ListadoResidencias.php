<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cliente;
use App\Models\Propietario;
use App\Models\Residencia;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoResidencias extends Component
{
    use WithPagination;

    public $cliente_id, $cliente;
    public $search = '';
    public $perPage = 5;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPageOptions = [5, 10, 15, 25, 50];
    public $filtro_estado = ''; // Nuevo filtro de estado

    public $residencia, $numeropuerta = "", $piso = "", $calle = "", $nrolote = "", $manzano = "", $cedula_propietario = "", $nombre_propietario = "", $estado = '';

    public $mode = 'create', $procesando = false;

    protected $paginationTheme = 'bootstrap';

    public function mount($cliente_id = null)
    {
        $this->cliente_id = $cliente_id;
        $this->cliente = $cliente_id ? Cliente::find($cliente_id) : null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingfiltro_estado()
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
        $residencias = Residencia::with(['cliente', 'propietario'])
            ->when($this->cliente_id, fn($q) => $q->where('cliente_id', $this->cliente_id))
            ->when($this->filtro_estado, fn($q) => $q->where('estado', $this->filtro_estado))
            ->where(function ($q) {
                $q->where('numeropuerta', 'like', "%{$this->search}%")
                    ->orWhere('piso', 'like', "%{$this->search}%")
                    ->orWhere('calle', 'like', "%{$this->search}%")
                    ->orWhere('nrolote', 'like', "%{$this->search}%")
                    ->orWhere('manzano', 'like', "%{$this->search}%")
                    ->orWhere('cedula_propietario', 'like', "%{$this->search}%")
                    ->orWhereHas('cliente', function ($q2) {
                        $q2->where('nombre', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('propietario', function ($q3) {
                        $q3->where('nombre', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.listado-residencias', compact('residencias'))
            ->with('i', 0)
            ->extends('adminlte::page');
    }

    public function resetAll()
    {
        $this->reset('numeropuerta', 'piso', 'calle', 'nrolote', 'manzano', 'cedula_propietario', 'estado', 'mode', 'procesando');
    }

    public function edit($residencia_id, $mode)
    {
        $this->resetAll();

        $this->mode = $mode;
        $residencia = Residencia::find($residencia_id);
        $this->residencia = $residencia;
        $this->numeropuerta = $residencia->numeropuerta;
        $this->piso = $residencia->piso;
        $this->calle = $residencia->calle;
        $this->nrolote = $residencia->nrolote;
        $this->manzano = $residencia->manzano;
        $this->estado = $residencia->estado;

        $this->emit('openModal');
    }

    public function update()
    {
        $this->validate([
            'numeropuerta' => 'nullable|string|max:255',
            'piso' => 'nullable|string|max:255',
            'calle' => 'nullable|string|max:255',
            'nrolote' => 'nullable|string|max:255',
            'manzano' => 'nullable|string|max:255',
        ]);

        if ($this->procesando) {
            return;
        }
        $this->procesando = true;
        DB::beginTransaction();
        try {

            DB::commit();
            $this->residencia->update([
                'numeropuerta' => $this->numeropuerta,
                'piso' => $this->piso,
                'calle' => $this->calle,
                'nrolote' => $this->nrolote,
                'manzano' => $this->manzano,
                'estado' => $this->estado,
            ]);

            $this->resetAll();
            $this->emit('closeModal');
            $this->emit('success', 'Residencia actualizada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Error al actualizar la residencia');
        }
    }

    public function create()
    {
        $this->validate([
            'numeropuerta' => 'nullable|string|max:255',
            'piso' => 'nullable|string|max:255',
            'calle' => 'nullable|string|max:255',
            'nrolote' => 'nullable|string|max:255',
            'manzano' => 'nullable|string|max:255',
        ]);

        // Validación adicional: al menos uno lleno
        if (
            ($this->numeropuerta === "") &&
            ($this->piso === "") &&
            ($this->calle === "") &&
            ($this->nrolote === "") &&
            ($this->manzano === "")
        ) {
            $this->emit('error', 'Debe llenar al menos un dato (N° puerta, piso, calle, lote o manzano).');
            return;
        }

        if ($this->procesando) {
            return;
        }

        DB::beginTransaction();
        try {
            $residencia = Residencia::create([
                'cliente_id'  => $this->cliente_id,
                'numeropuerta' => $this->numeropuerta,
                'piso'         => $this->piso,
                'calle'        => $this->calle,
                'nrolote'      => $this->nrolote,
                'manzano'      => $this->manzano,
            ]);

            DB::commit();

            // 🔥 Solo si se guardó bien se resetea y se cierra modal
            $this->resetAll();
            $this->emit('closeModal');
            $this->emit('success', 'Residencia creada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Error al crear la residencia');
        }
    }

    public $searchCedula = "", $propietario = NULL, $residenciaSel = "";

    public function updatedSearchCedula()
    {
        $this->buscarPropietario();
    }

    public function buscarPropietario()
    {
        $this->validate([
            'searchCedula' => 'required|string|max:255',
        ]);
        $this->propietario = Propietario::where('cedula', $this->searchCedula)
            ->where('activo', 1)
            ->first();
        if (!$this->propietario) {
            $this->emit('toast-error', 'No se encontró el propietario con la cédula ingresada');
        }
    }

    public function resetPropietario()
    {
        $this->reset('searchCedula', 'propietario', 'residenciaSel');
    }

    protected $listeners = ['reasignarPropietario'];

    public function reasignarPropietario()
    {
        if ($this->propietario) {
            DB::beginTransaction();
            try {
                $residencia = Residencia::find($this->residenciaSel);
                $residencia->propietario_id = $this->propietario->id;
                $residencia->cedula_propietario = $this->propietario->cedula;
                $residencia->save();
                DB::commit();

                $this->emit('success', 'Propietario reasignado correctamente');
                $this->emit('cerrarReasignacion');
                $this->resetPropietario();
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit('error', $th->getMessage());
            }
        } else {
            $this->emit('error', 'No se encontró el propietario');
        }
    }
}
