<?php

namespace App\Http\Livewire\Admin;

use App\Exports\NovedadesExport;
use App\Models\Cliente;
use App\Models\Novedade;
use App\Models\Vwnovedade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Registrosnovedades extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $clientes, $cliente_id = "",  $inicio, $final, $search = "", $empleado_id = "", $auxcliente = "";
    public $novedade = null;

    public function mount()
    {
        $this->inicio = date('Y-m-01'); // Primer día del mes actual
        $this->final = date('Y-m-d');   // Día actual
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        // Agregar opción de Todos los registros al inicio
        $this->clientes = $this->clientes->prepend('Todos los registros', 'todos');
        // Agregar opción de Supervisores
        $this->clientes = $this->clientes->prepend('SUPERVISORES', 'supervisores');
        $this->novedade = new Novedade();
    }
    public function render()
    {
        $resultados = NULL;
        $sql = "";
        $empleados = [];

        if ($this->cliente_id != "") {
            // Filtrar todos los registros
            if ($this->cliente_id == "todos") {
                $empleados = DB::select("SELECT empleado_id as id, empleado as nombre FROM vwnovedades GROUP BY empleado_id, empleado ORDER BY empleado");
                if ($this->auxcliente != $this->cliente_id) {
                    $this->auxcliente = $this->cliente_id;
                    $this->empleado_id = "";
                }

                if ($this->empleado_id == "") {
                    $resultados = Vwnovedade::whereBetween('fecha', [$this->inicio, $this->final]);

                    if ($this->search != "") {
                        $resultados = $resultados->where(function ($query) {
                            $query->where('empleado', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('cliente', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('contenido', 'LIKE', '%' . $this->search . '%');
                        });
                    }

                    $resultados = $resultados->orderBy('fecha', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
                } else {
                    $resultados = Vwnovedade::whereBetween('fecha', [$this->inicio, $this->final])
                        ->where('empleado_id', $this->empleado_id);

                    if ($this->search != "") {
                        $resultados = $resultados->where(function ($query) {
                            $query->where('empleado', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('cliente', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('contenido', 'LIKE', '%' . $this->search . '%');
                        });
                    }

                    $resultados = $resultados->orderBy('fecha', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
                }

                $parametros = array('todos', $this->inicio, $this->final, $this->search, $this->empleado_id);
                Session::put('param-novedades', $parametros);
            } elseif ($this->cliente_id == "supervisores") {
                $empleados = DB::select("SELECT d.empleado_id as id, CONCAT(e.nombres,' ',e.apellidos) as nombre FROM novedades n
                    INNER JOIN designaciones d ON d.id = n.designacione_id
                    INNER JOIN empleados e ON e.id = d.empleado_id
                    LEFT JOIN turnos t ON t.id = d.turno_id
                    WHERE (t.cliente_id IS NULL OR t.cliente_id = 0)
                    GROUP BY d.empleado_id, e.nombres, e.apellidos
                    ORDER BY e.nombres");

                if ($this->auxcliente != $this->cliente_id) {
                    $this->auxcliente = $this->cliente_id;
                    $this->empleado_id = "";
                }

                if ($this->empleado_id == "") {
                    $resultados = Vwnovedade::whereBetween('fecha', [$this->inicio, $this->final])
                        ->where(function ($query) {
                            $query->whereNull('cliente_id')
                                ->orWhere('cliente_id', 0);
                        });

                    if ($this->search != "") {
                        $resultados = $resultados->where(function ($query) {
                            $query->where('empleado', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('contenido', 'LIKE', '%' . $this->search . '%');
                        });
                    }

                    $resultados = $resultados->orderBy('fecha', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
                } else {
                    $resultados = Vwnovedade::whereBetween('fecha', [$this->inicio, $this->final])
                        ->where(function ($query) {
                            $query->whereNull('cliente_id')
                                ->orWhere('cliente_id', 0);
                        })
                        ->where('empleado_id', $this->empleado_id);

                    if ($this->search != "") {
                        $resultados = $resultados->where(function ($query) {
                            $query->where('empleado', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('contenido', 'LIKE', '%' . $this->search . '%');
                        });
                    }

                    $resultados = $resultados->orderBy('fecha', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
                }

                // Procesar resultados para extraer cliente del contenido (formato: CLIENTE: contenido)
                if ($resultados) {
                    $resultados->getCollection()->transform(function ($item) {
                        if (strpos($item->contenido, ':') !== false) {
                            $parts = explode(':', $item->contenido, 2);
                            $item->cliente_from_content = trim($parts[0]);
                        } else {
                            $item->cliente_from_content = null;
                        }
                        return $item;
                    });
                }

                $parametros = array('supervisores', $this->inicio, $this->final, $this->search, $this->empleado_id);
                Session::put('param-novedades', $parametros);
            } else {
                // Filtrar por cliente
                $empleados = DB::select("SELECT empleado_id as id, empleado as nombre FROM vwnovedades WHERE cliente_id = " . (int)$this->cliente_id . " GROUP BY empleado_id, empleado ORDER BY empleado");
                if ($this->auxcliente != $this->cliente_id) {
                    $this->auxcliente = $this->cliente_id;
                    $this->empleado_id = "";
                }

                if ($this->empleado_id == "") {
                    $resultados = Vwnovedade::whereBetween('fecha', [$this->inicio, $this->final])
                        ->where('cliente_id', (int)$this->cliente_id);

                    if ($this->search != "") {
                        $resultados = $resultados->where(function ($query) {
                            $query->where('empleado', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('turno', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('contenido', 'LIKE', '%' . $this->search . '%');
                        });
                    }

                    $resultados = $resultados->orderBy('fecha', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
                } else {
                    $resultados = Vwnovedade::whereBetween('fecha', [$this->inicio, $this->final])
                        ->where('cliente_id', (int)$this->cliente_id)
                        ->where('empleado_id', $this->empleado_id);

                    if ($this->search != "") {
                        $resultados = $resultados->where(function ($query) {
                            $query->where('empleado', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('turno', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('contenido', 'LIKE', '%' . $this->search . '%');
                        });
                    }

                    $resultados = $resultados->orderBy('fecha', 'DESC')
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
                }

                $parametros = array($this->cliente_id, $this->inicio, $this->final, $this->search, $this->empleado_id);
                Session::put('param-novedades', $parametros);
            }
        }

                // Procesar resultados para extraer cliente del contenido (formato: CLIENTE: contenido) solo para supervisores
                if ($resultados) {
                    $resultados->getCollection()->transform(function ($item) {
                        if (is_null($item->cliente_id) || $item->cliente_id == 0) {
                            if (strpos($item->contenido, ':') !== false) {
                                $parts = explode(':', $item->contenido, 2);
                                $item->cliente_from_content = trim($parts[0]);
                                $item->contenido = trim($parts[1]); // Limpiar contenido
                            } else {
                                $item->cliente_from_content = null;
                            }
                        } else {
                            $item->cliente_from_content = null;
                        }
                        return $item;
                    });
                }

        return view('livewire.admin.registrosnovedades', compact('resultados', 'empleados'))->extends('adminlte::page');
    }

    public function verInfo($id)
    {
        $this->novedade = Vwnovedade::find($id);

        // Extraer cliente del contenido si tiene el formato CLIENTE: contenido y es supervisor
        if ($this->novedade && (is_null($this->novedade->cliente_id) || $this->novedade->cliente_id == 0) && strpos($this->novedade->contenido, ':') !== false) {
            $parts = explode(':', $this->novedade->contenido, 2);
            $this->novedade->cliente_from_content = trim($parts[0]);
            // Actualizar contenido sin el nombre del cliente
            $this->novedade->contenido = trim($parts[1]);
        }
    }

    public function exporExcel()
    {
        if ($this->cliente_id == 'todos') {
            return Excel::download(new NovedadesExport(), 'Novedades_Todos_' . date('His') . '.xlsx');
        } else {
            $cliente = Cliente::find($this->cliente_id);
            return Excel::download(new NovedadesExport(), 'Novedades_' . $cliente->nombre . '_' . date('His') . '.xlsx');
        }
    }

    public function updatedClienteId()
    {
        $this->resetPage();
    }
    public function updatedEstado()
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
