<?php

namespace App\Http\Livewire\Admin;

use App\Exports\EmpleadosExport;
use App\Models\Empleado;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ListadoEmpleados extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $busqueda = "", $filas = 10;

    protected $data;

    public function render()
    {
        // $empleados = Empleado::join('areas', 'areas.id', '=', 'empleados.area_id')
        //     ->select('empleados.*', 'areas.nombre')
        //     ->where('empleados.nombres', 'LIKE', '%' . $this->busqueda . '%')
        //     ->orWhere('empleados.apellidos', 'LIKE', '%' . $this->busqueda . '%')
        //     ->orWhere('areas.nombre', 'LIKE', '%' . $this->busqueda . '%')
        //     ->paginate($this->filas);
        $empleados = Empleado::join('areas', 'areas.id', '=', 'empleados.area_id')
            ->leftJoin('users', 'users.id', '=', 'empleados.user_id')
            ->select(
                'empleados.*',
                'areas.nombre as area_nombre',
                'users.status as user_status'
            )
            ->where(function ($q) {
                $q->where('empleados.nombres', 'LIKE', '%' . $this->busqueda . '%')
                    ->orWhere('empleados.apellidos', 'LIKE', '%' . $this->busqueda . '%')
                    ->orWhere('areas.nombre', 'LIKE', '%' . $this->busqueda . '%');
            })
            ->orderByDesc('users.status') // 👑 status = 1 primero
            ->orderByDesc('empleados.id')
            ->paginate($this->filas);

        $parametros = $this->busqueda;
        Session::put('param-empleados', $parametros);

        return view('livewire.admin.listado-empleados', compact('empleados'));
    }

    public function exporExcel()
    {
        return Excel::download(new EmpleadosExport(), 'Listado_Empleados_' . date('His') . '.xlsx');
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }
    public function updatedFilas()
    {
        $this->resetPage();
    }
}
