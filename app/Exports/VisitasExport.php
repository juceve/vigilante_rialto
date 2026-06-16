<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Vwvisita;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitasExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $parametros = Session::get('param-visitas');
        $cliente = Cliente::find($parametros[0]);
        $resultados = "";
        if ($parametros[1] == "") {
            $resultados = Vwvisita::where([
                ["fechaingreso", ">=", $parametros[2]],
                ["fechaingreso", "<=", $parametros[3]],
                ["cliente_id",  $parametros[0]],
                ['visitante', 'LIKE', '%' . $parametros[4] . '%']
            ])->orWhere(
                [
                    ["fechaingreso", ">=", $parametros[2]],
                    ["fechaingreso", "<=", $parametros[3]],
                    ["cliente_id",  $parametros[0]],
                    ['residente', 'LIKE', '%' . $parametros[4] . '%']
                ]
            )->orWhere([
                ["fechaingreso", ">=", $parametros[2]],
                ["fechaingreso", "<=", $parametros[3]],
                ["cliente_id",  $parametros[0]],
                ['docidentidad', 'LIKE', '%' . $parametros[4] . '%']
            ])
                ->orderBy('id', 'ASC')
                ->get();
        } else {
            $resultados = Vwvisita::where([
                ["fechaingreso", ">=", $parametros[2]],
                ["fechaingreso", "<=", $parametros[3]],
                ["cliente_id",  $parametros[0]],
                ['visitante', 'LIKE', '%' . $parametros[4] . '%'],
                ["estado", $parametros[1]],
            ])
                ->orWhere([
                    ["fechaingreso", ">=", $parametros[2]],
                    ["fechaingreso", "<=", $parametros[3]],
                    ["cliente_id",  $parametros[0]],
                    ['residente', 'LIKE', '%' . $parametros[4] . '%'],
                    ["estado", $parametros[1]],
                ])
                ->orWhere([
                    ["fechaingreso", ">=", $parametros[2]],
                    ["fechaingreso", "<=", $parametros[3]],
                    ["cliente_id",  $parametros[0]],
                    ['docidentidad', 'LIKE', '%' . $parametros[4] . '%'],
                    ["estado", $parametros[1]],
                ])
                ->orderBy('id', 'ASC')

                ->get();
        }
        return view('excels.visitas', compact('resultados', 'parametros', 'cliente'));
    }
}
