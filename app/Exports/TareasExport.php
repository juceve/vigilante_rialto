<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Vwtarea;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TareasExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $parametros = Session::get('param-tarea');
        $cliente = Cliente::find($parametros[0]);
        $resultados = "";
        if ($parametros[1] == "") {
            $resultados = Vwtarea::where(
                [
                    ["fecha", ">=", $parametros[2]],
                    ["fecha", "<=", $parametros[3]],
                    ["cliente_id", $parametros[0]],
                    ['nombreempleado', 'LIKE', '%' . $parametros[4] . '%']
                ]
            )
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $resultados = Vwtarea::where([
                ["fecha", ">=", $parametros[2]],
                ["fecha", "<=", $parametros[3]],
                ["cliente_id", $parametros[0]],
                ['nombreempleado', 'LIKE', '%' . $parametros[4] . '%'],
                ["estado", $parametros[1]],
            ])
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('excels.tareas', compact('resultados', 'parametros', 'cliente'));
    }
}
