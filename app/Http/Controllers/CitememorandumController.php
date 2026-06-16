<?php

namespace App\Http\Controllers;

use App\Models\Citememorandum;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class CitememorandumController
 * @package App\Http\Controllers
 */
class CitememorandumController extends Controller
{
    public function previsualizacion($data = NULL)
    {
        if (is_null($data) || $data == "undefined") {
            $data = Session::get('data-citememorandum');
        }

        $pdf = Pdf::loadView('tempdocs.memorandum', compact('data'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
