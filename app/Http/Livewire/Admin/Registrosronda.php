<?php

namespace App\Http\Livewire\Admin;

use App\Exports\RondasExport;
use App\Models\Cliente;
use App\Models\Rondaejecutada;
use App\Models\User; // <-- añadir import
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Registrosronda extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $clientes, $cliente_id = "",  $inicio, $final, $search = "";
    public $rondaejecutada;

    // Propiedad protegida: Livewire no intentará serializar objetos/colecciones protegidas
    protected $resultadosPDF = null;

    public $modalUrl = '';

    // Nuevas propiedades para filtro de empleados
    public $empleados = [];
    public $empleado_id = '';

    public function openModal($id)
    {
        $this->modalUrl = route('admin.recorrido_ronda', $id);

        // Disparar evento JS para abrir modal
        $this->dispatchBrowserEvent('openModal');
    }

    public function closeModal()
    {
        $this->modalUrl = '';
    }

    public function mount()
    {
        $rondaejecutada = null;
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
    }

    public function render()
    {
        $this->emit('showLoading');

        // Construir consulta base aplicando filtros por cliente/fechas/busqueda
        $baseQuery = Rondaejecutada::with('ronda', 'cliente', 'user')
            ->when($this->cliente_id, function ($query) {
                $query->where('cliente_id', $this->cliente_id);
            })
            ->when($this->inicio, function ($query) {
                $query->whereDate('inicio', '>=', $this->inicio);
            })
            ->when($this->final, function ($query) {
                $query->whereDate('inicio', '<=', $this->final);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('ronda', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            });

        // Obtener IDs de usuarios presentes en la consulta base (sin paginar)
        $userIds = (clone $baseQuery)->pluck('user_id')->unique()->filter()->toArray();
        // Cargar la lista de empleados para el select (id => name)
        $this->empleados = User::whereIn('id', $userIds)->orderBy('name')->pluck('name', 'id');

        // Aplicar filtro por empleado si está seleccionado
        if ($this->empleado_id) {
            $baseQuery->where('user_id', $this->empleado_id);
        }

        $resultados = $baseQuery
            ->orderBy('id', 'DESC')
            ->paginate(10);
        // No asignar paginadores/colecciones a propiedades públicas aquí.
        // Use cargarResultadosExport() cuando necesite preparar los datos para exportar.

        $this->emit('hideLoading');
        return view('livewire.admin.registrosronda', compact('resultados'))->extends('adminlte::page');
    }

    /**
     * Cargar en memoria (sin paginar) los mismos resultados que se muestran,
     * aplicando los mismos filtros actuales. Solo asigna la colección a $resultadosPDF.
     */
    public function cargarResultadosExport()
    {
        // Recrear la misma consulta base que en render()
        $baseQuery = Rondaejecutada::with('ronda', 'cliente', 'user')
            ->when($this->cliente_id, function ($query) {
                $query->where('cliente_id', $this->cliente_id);
            })
            ->when($this->inicio, function ($query) {
                $query->whereDate('inicio', '>=', $this->inicio);
            })
            ->when($this->final, function ($query) {
                $query->whereDate('inicio', '<=', $this->final);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('ronda', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            });

        if ($this->empleado_id) {
            $baseQuery->where('user_id', $this->empleado_id);
        }

        // Obtener colección completa y asignarla (sin paginar)
        $this->resultadosPDF = $baseQuery->orderBy('id', 'DESC')->get();
    }

    public function verInfo($id)
    {
        // $this->rondaejecutada = Rondaejecutada::find($id);
        return redirect()->route('admin.recorrido_ronda', $id);
    }

    public function exporExcel()
    {
        $cliente = Cliente::find($this->cliente_id);
        return Excel::download(new RondasExport(), 'Rondas_' . $cliente->nombre . '_' . date('His') . '.xlsx');
    }

    public function exportarPDF()
    {
        // Asegurar que la colección completa esté cargada (sin paginar)
        $this->cargarResultadosExport();

        // Convertir a array para pasar a la vista/PDF (tipos serializables)
        Session::put('rondas_ejecutadas', $this->resultadosPDF ? $this->resultadosPDF : NULL);
        Session::put('fechas_rondas', [$this->inicio, $this->final]);

        $this->emit('renderizarpdf');
    }

    public function updatedCliente_id()
    {
        $this->resetPage();
    }
    public function updatedEmpleado_id()
    {
        $this->resetPage();
    }
    public function updatedInicio()
    {
        $this->resetPage();
    }
    public function updatedFinal()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
