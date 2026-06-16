<?php

namespace App\Http\Controllers;

use App\Models\Citecobro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CitecobroController extends Controller
{
    public function previsualizacion($data = NULL)
    {
        if (is_null($data) || $data == "undefined") {
            $data = Session::get('data-citecobro');
        }

        $pdf = Pdf::loadView('tempdocs.cobro', compact('data'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
