<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Vwasistencia;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AsistenciasExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $parametros = Session::get('param-asistencias');
        $cliente = Cliente::find($parametros[0]);
        $empleado = "";
        if ($parametros[4] == "") {
            $empleado = "TODOS";
        } else {
            $result = Empleado::find($parametros[4]);
            $empleado = $result->nombres . " " . $result->apellidos;
        }

        $resultados = "";
        if ($parametros[4] == "") {
            $resultados = Vwasistencia::where([
                ["fecha", ">=", $parametros[1]],
                ["fecha", "<=", $parametros[2]],
                ["cliente_id", $parametros[0]],
                ['empleado', 'LIKE', '%' . $parametros[3] . '%']
            ])->orWhere(
                [
                    ["fecha", ">=", $parametros[1]],
                    ["fecha", "<=", $parametros[2]],
                    ["cliente_id", $parametros[0]],
                    ['turno', 'LIKE', '%' . $parametros[3] . '%']
                ]
            )
                ->orderBy('empleado_id', 'ASC')
                ->orderBy('fecha', 'ASC')
                ->get();
        } else {
            $resultados = Vwasistencia::where([
                ["fecha", ">=", $parametros[1]],
                ["fecha", "<=", $parametros[2]],
                ["cliente_id", $parametros[0]],
                ['empleado_id', $parametros[4]]
            ])->orWhere(
                [
                    ["fecha", ">=", $parametros[1]],
                    ["fecha", "<=", $parametros[2]],
                    ["cliente_id", $parametros[0]],
                    ['turno', 'LIKE', '%' . $parametros[3] . '%'],
                    ['empleado_id', $parametros[4]]
                ]
            )
                ->orderBy('empleado_id', 'ASC')
                ->orderBy('fecha', 'ASC')
                ->get();
        }

        return view('excels.asistencias', compact('resultados', 'parametros', 'cliente', 'empleado'));
    }
}
