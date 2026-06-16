<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Vwronda;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RondasExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $parametros = Session::get('param-ronda');
        $cliente = Cliente::find($parametros[0]);
        $resultados = "";
        $resultados = Vwronda::where([
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

        return view('excels.rondas', compact('resultados', 'parametros', 'cliente'));
    }
}
