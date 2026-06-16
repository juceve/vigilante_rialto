<?php

namespace App\Exports;

use App\Models\Cliente;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromView;

class ResumenOperacionalExport implements FromView
{
    public function view(): View
    {
        // Obtener los datos de la sesión
        $resultados = Session::get('resumen_operacional_data', []);
        $parametros = Session::get('resumen_operacional_parametros', [null, null, null]);
        $cliente = Cliente::find($parametros[0]);
        // Retornar la vista que se usará para generar el archivo Excel
        return view('exports.resumen-operacional', compact('resultados','parametros','cliente'));
    }
}
