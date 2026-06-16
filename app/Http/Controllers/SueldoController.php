<?php

namespace App\Http\Controllers;

use App\Models\Rrhhsueldo;
use App\Models\Rrhhsueldoempleado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SueldoController extends Controller
{
    public function previsualizacion($id)
    {
       $rrhhsueldo = Rrhhsueldo::find($id);
        $sueldos = $rrhhsueldo->rrhhsueldoempleados;
        $pdf = Pdf::loadView('tempdocs.sueldos-resumen', compact('rrhhsueldo', 'sueldos'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream();
    }

    public function boletas($id)
    {
                $rrhhsueldo = Rrhhsueldo::findOrFail($id);
        $boletas = Rrhhsueldoempleado::where('rrhhsueldo_id', $id)->get();

        $appName = env('APP_NAME', 'Sistema');

        $pdf = Pdf::loadView('tempdocs.boletas-sueldo', compact('rrhhsueldo', 'boletas', 'appName'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
