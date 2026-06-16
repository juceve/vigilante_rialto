<?php

namespace App\Http\Livewire\Admin;

use App\Exports\SancionesExport;
use App\Exports\TareasExport;
use App\Models\Cliente;
use App\Models\Rrhhdescuento;
use App\Models\Tarea;
use App\Models\Vwtarea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class RegistrosSanciones extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $estado = "", $inicio = '', $final = '', $search = "";
    public $fecha = "", $cliente_id = "", $empleado_id = "", $contenido = "", $imgs = [];

    public function mount()
    {
        $this->inicio = date('Y-m-d');
        $this->final = date('Y-m-d');
    }

    public function render()
    {
        // DB::enableQueryLog();
        $resultados = NULL;
        $sql = "";
        $estado = $this->estado;
        $search = $this->search;
        $resultados = Rrhhdescuento::with('empleado')
            ->where('fecha', '>=', $this->inicio)
            ->where('fecha', '<=', $this->final)

            ->when($search != "", function ($q) use ($search) {
                $q->whereHas('empleado', function ($sub) use ($search) {
                    $sub->where('nombres', 'like', "%$search%")
                        ->orWhere('apellidos', 'like', "%$search%");
                });
            })
            ->when($estado != '', function ($q) use ($estado) {
                $q->where('estado', $estado);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $parametros = array($this->estado, $this->inicio, $this->final, $this->search);
        Session::put('param-descuentos', $parametros);

        return view('livewire.admin.registros-sanciones', compact('resultados'))->extends('adminlte::page');
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
        return Excel::download(new SancionesExport(), 'Descuentos_Sanciones' . '_' . date('His') . '.xlsx');
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
