<?php

namespace App\Http\Livewire\Admin;

use App\Exports\TareasExport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\VisitasExport;
use App\Models\Cliente;
use App\Models\Tarea;
use App\Models\Vwtarea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class Registrostareas extends Component
{


    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $clientes, $selCliente = "", $guardias, $estado = "", $inicio, $final, $search = "";
    public $tarea = null, $fecha = "", $cliente_id = "", $empleado_id = "", $contenido = "", $imgs = [];

    public function mount()
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
        $this->clientes = Cliente::all()->pluck('nombre', 'id');
        $this->guardias;
        $this->tarea = new Tarea();
    }

    public function render()
    {
        // DB::enableQueryLog();
        $resultados = NULL;
        $sql = "";
        if ($this->selCliente != "") {
            if ($this->estado == "") {
                $resultados = Vwtarea::where([
                    ["fecha", ">=", $this->inicio],
                    ["fecha", "<=", $this->final],
                    ["cliente_id", $this->selCliente],
                    ['nombreempleado', 'LIKE', '%' . $this->search . '%']
                ])
                    ->orderBy('id', 'DESC')
                    ->paginate(10);
            } else {
                $resultados = Vwtarea::where([
                    ["fecha", ">=", $this->inicio],
                    ["fecha", "<=", $this->final],
                    ["cliente_id", $this->selCliente],
                    ['nombreempleado', 'LIKE', '%' . $this->search . '%'],
                    ["estado", $this->estado],
                ])
                    ->orderBy('id', 'DESC')
                    ->paginate(10);
            }
            // $this->resetPage();
            $parametros = array($this->selCliente, $this->estado, $this->inicio, $this->final, $this->search);
            Session::put('param-tarea', $parametros);
        }

        if ($this->cliente_id != "") {
            $sql = "SELECT DISTINCT(e.id) id, CONCAT(e.nombres,' ',e.apellidos) empleado FROM designaciones d
            INNER JOIN turnos t ON t.id=d.turno_id
            INNER JOIN empleados e ON e.id=d.empleado_id
            WHERE d.fechaInicio <= '" . date('Y-m-d') . "'
            AND d.fechaFin >= '" . date('Y-m-d') . "'
            AND d.estado = 1
            AND t.cliente_id = " . $this->cliente_id;

            $this->guardias = DB::select($sql);
        }

        return view('livewire.admin.registrostareas', compact('resultados'))->extends('adminlte::page');
    }

    protected $rules = [
        "fecha" => "required",
        "cliente_id" => "required",
        "empleado_id" => "required",
        "contenido" => "required",
    ];

    public function limpiarControles()
    {
        $this->reset(["fecha", "cliente_id", "empleado_id", "contenido", "guardias"]);
    }

    protected $listeners = ["destroy"];

    public function registrar()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $tarea = Tarea::create([
                "fecha" => $this->fecha,
                "cliente_id" => $this->cliente_id,
                "empleado_id" => $this->empleado_id,
                "contenido" => $this->contenido,
            ]);

            DB::commit();
            $this->limpiarControles();
            $this->emit('cerrarModalNuevo');
            return $this->emit('success', 'Tarea registrada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->emit('error', 'Ha ocurrido un error.');
        }
    }

    public function verInfo($id)
    {
        $this->reset('imgs');
        $this->tarea = Vwtarea::find($id);
        if ($this->tarea->imgs) {
            $this->imgs = explode('|', $this->tarea->imgs);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $tarea = Tarea::find($id);
            $tarea->delete();
            DB::commit();

            return $this->emit('success', 'Tarea eliminada correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->emit('error', 'Ha ocurrido un error.');
        }
    }

    public function exporExcel()
    {
        $cliente = Cliente::find($this->selCliente);
        return Excel::download(new TareasExport(), 'Tareas_' . $cliente->nombre . '_' . date('His') . '.xlsx');
    }

    public function updatedCliente_id()
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
