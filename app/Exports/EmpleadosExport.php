<?php

namespace App\Exports;

use App\Models\Empleado;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmpleadosExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $parametros = Session::get('param-empleados');
        if ($parametros != "") {
            $resultados = Empleado::join('areas', 'areas.id', '=', 'empleados.area_id')
                ->select('empleados.*', 'areas.nombre')
                ->where('empleados.nombres', 'LIKE', '%' . $parametros . '%')
                ->orWhere('empleados.apellidos', 'LIKE', '%' . $parametros . '%')
                ->orWhere('areas.nombre', 'LIKE', '%' . $parametros . '%')
                ->orderBy('id', 'ASC')
                ->get();
        } else {
            $resultados = Empleado::join('areas', 'areas.id', '=', 'empleados.area_id')
                ->select('empleados.*', 'areas.nombre')
                ->orderBy('id', 'ASC')
                ->get();
        }


        return view('excels.empleados', compact('resultados'));
    }
}
