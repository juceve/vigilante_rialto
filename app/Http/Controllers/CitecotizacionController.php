<?php

namespace App\Http\Controllers;

use App\Models\Citecotizacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CitecotizacionController extends Controller
{
    public function previsualizacion($data = NULL)
    {

        if (is_null($data) || $data == "undefined") {
            $data = Session::get('data-citecotizacion');
            $data_detalles = Session::get('data-detalles');
             $pdf = Pdf::loadView('tempdocs.cotizacion', compact('data','data_detalles'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
        }else {
             $pdf = Pdf::loadView('tempdocs.cotizacion', compact('data'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream('Cotizacion.pdf');
        }

    }
}
