<?php

namespace App\Http\Controllers;

use App\Models\Citeinforme;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CiteinformeController extends Controller
{
    public function previsualizacion($data = NULL)
    {
        if (is_null($data) || $data == "undefined") {
            $data = Session::get('data-citeinforme');
        }

        $pdf = Pdf::loadView('tempdocs.informe', compact('data'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
