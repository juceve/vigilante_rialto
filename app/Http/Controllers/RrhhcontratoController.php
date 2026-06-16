<?php

namespace App\Http\Controllers;

use App\Models\Rrhhcontrato;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RrhhcontratoController extends Controller
{
    public function contratoPdf($fecha, $referencia)
    {
        $designacione = Session::get('designacione_data');
        $contrato = Session::get('contrato_data');
        $empleado = $contrato->empleado;


        switch ($designacione->tipo_designacion) {
            case 'GUARDIA': {
                    $cliente = $designacione->turno->cliente->nombre;
                    $pdf = Pdf::loadView('pdfs.contrato', compact('designacione', 'contrato', 'empleado', 'fecha', 'cliente', 'referencia'))
                        ->setPaper([0, 0, 609.4488, 935.433], 'portrait');
                    return $pdf->stream('Contrato_' . $contrato->id . '_' . date('YmdHis') . '.pdf');
                }
                break;
            case 'SUPERVISOR': {
                    $pdf = Pdf::loadView('pdfs.contratoSupervisor', compact('designacione', 'contrato', 'empleado', 'fecha', 'referencia'))
                        ->setPaper([0, 0, 609.4488, 935.433], 'portrait');
                    return $pdf->stream('Contrato_' . $contrato->id . '_' . date('YmdHis') . '.pdf');
                }
                break;

                // default:

                //     break;
        }


        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();

        // // Posición: centrado abajo
        // $width = $canvas->get_width();
        // $height = $canvas->get_height();
        // $canvas->page_text($width / 2, $height - 30, "{PAGE_NUM}", null, 10, [0, 0, 0]);


    }
}
