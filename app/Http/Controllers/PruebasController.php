<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class PruebasController extends Controller
{
    public function index()
    {
        return view('pruebas');
    }

    public function generarPDF()
    {
        // return view('admin.designacione.pdfRonda', compact('designacione','rondas'));
        $pdf = Pdf::loadView('tempdocs.informe')
        ->setPaper('letter', 'portrait');
        return $pdf->stream('Informe.pdf');
    }
}
