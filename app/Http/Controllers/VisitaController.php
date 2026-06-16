<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Visita;
use App\Models\Vwvisita;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class VisitaController
 * @package App\Http\Controllers
 */
class VisitaController extends Controller
{
    public function pdfVisitas()
    {
        $parametros = Session::get('param-visitas');
        $cliente = Cliente::find($parametros[0]);
        $resultados = "";
        if ($parametros[1] == "") {
            $resultados = Vwvisita::where([
                ["fechaingreso", ">=", $parametros[2]],
                ["fechaingreso", "<=", $parametros[3]],
                ["cliente_id", ">=", $parametros[0]],
                ['visitante', 'LIKE', '%' . $parametros[4] . '%']
            ])->orWhere(
                [
                    ["fechaingreso", ">=", $parametros[2]],
                    ["fechaingreso", "<=", $parametros[3]],
                    ["cliente_id", ">=", $parametros[0]],
                    ['residente', 'LIKE', '%' . $parametros[4] . '%']
                ]
            )->orWhere([
                ["fechaingreso", ">=", $parametros[2]],
                ["fechaingreso", "<=", $parametros[3]],
                ["cliente_id", ">=", $parametros[0]],
                ['docidentidad', 'LIKE', '%' . $parametros[4] . '%']
            ])
                ->orderBy('id', 'ASC')
                ->get();
        } else {
            $resultados = Vwvisita::where([
                ["fechaingreso", ">=", $parametros[2]],
                ["fechaingreso", "<=", $parametros[3]],
                ["cliente_id", ">=", $parametros[0]],
                ['visitante', 'LIKE', '%' . $parametros[4] . '%'],
                ["estado", $parametros[1]],
            ])
                ->orWhere([
                    ["fechaingreso", ">=", $parametros[2]],
                    ["fechaingreso", "<=", $parametros[3]],
                    ["cliente_id", ">=", $parametros[0]],
                    ['residente', 'LIKE', '%' . $parametros[4] . '%'],
                    ["estado", $parametros[1]],
                ])
                ->orWhere([
                    ["fechaingreso", ">=", $parametros[2]],
                    ["fechaingreso", "<=", $parametros[3]],
                    ["cliente_id", ">=", $parametros[0]],
                    ['docidentidad', 'LIKE', '%' . $parametros[4] . '%'],
                    ["estado", $parametros[1]],
                ])
                ->orderBy('id', 'ASC')

                ->get();
        }
        // return view('pdfs.pdfmarcaciones', compact('resultados', 'parametros', 'cliente'));
        $i = 1;
        $pdf = Pdf::loadView('pdfs.pdfmarcaciones', compact('resultados', 'parametros', 'cliente', 'i'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
