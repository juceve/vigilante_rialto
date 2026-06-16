<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Vwnovedade;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NovedadesExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $parametros = Session::get('param-novedades');
        $cliente = Cliente::find($parametros[0]);
        $resultados = "";
        if ($parametros[4] == "") {
            $resultados = Vwnovedade::where([
                ["fecha", ">=", $parametros[1]],
                ["fecha", "<=", $parametros[2]],
                ["cliente_id", $parametros[0]],
                ['empleado', 'LIKE', '%' . $parametros[3] . '%']
            ])->orWhere(
                [
                    ["fecha", ">=", $parametros[1]],
                    ["fecha", "<=", $parametros[2]],
                    ["cliente_id", $parametros[0]],
                    ['turno', 'LIKE', '%' . $parametros[3] . '%'],
                ]
            )->orderBy('fecha', 'DESC')
                ->get();
        } else {
            $resultados = Vwnovedade::where([
                ["fecha", ">=", $parametros[1]],
                ["fecha", "<=", $parametros[2]],
                ["cliente_id", $parametros[0]],
                ['empleado_id', $parametros[4]],
                ['turno', 'LIKE', '%' . $parametros[3] . '%'],
            ])->orWhere(
                [
                    ["fecha", ">=", $parametros[1]],
                    ["fecha", "<=", $parametros[2]],
                    ["cliente_id", $parametros[0]],
                    ['empleado_id', $parametros[4]],
                    ['turno', 'LIKE', '%' . $parametros[3] . '%'],
                ]
            )
                ->orderBy('fecha', 'DESC')
                ->get();
        }



        return view('excels.novedades', compact('resultados', 'parametros', 'cliente'));
    }
}
