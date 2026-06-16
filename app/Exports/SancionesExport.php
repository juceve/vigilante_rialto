<?php

namespace App\Exports;

use App\Models\Rrhhdescuento;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SancionesExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
   public function view(): View
    {
        $parametros = Session::get('param-descuentos');
        $search = $parametros[3];
        $estado = $parametros[0];
        $inicio = $parametros[1];
        $final = $parametros[2];
        $resultados = Rrhhdescuento::with('empleado')
            ->where('fecha', '>=', $inicio)
            ->where('fecha', '<=', $final)

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
            ->get();
        return view('excels.descuentos', compact('resultados', 'parametros'));
    }
}
