<?php

namespace App\Http\Controllers;

use App\Models\Cldotacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class CldotacionController
 * @package App\Http\Controllers
 */
class CldotacionController extends Controller
{
    public function acta()
    {
        $dotacion = Session::get('dotacion_acta');
        if ($dotacion) {
            $pdf = Pdf::loadView('pdfs.acta-dotacion-cliente', compact('dotacion'))
                ->setPaper('letter', 'portrait');

            return $pdf->stream('Acta_Dotacion_Cliente_' . date('Ymd_His') . '.pdf');
        } else {
            return redirect()->back()->with('error', 'Dotación no encontrada');
        }
    }
}
