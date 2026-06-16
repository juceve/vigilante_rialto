<?php

namespace App\Http\Controllers;

use App\Models\Designacione;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VigilanciaController extends Controller
{
    public function profile()
    {
        $designacione_id = Session::get('designacion-oper');
        $designacione = Designacione::find($designacione_id);
        $empleado = $designacione ? $designacione->empleado : null;
        return view('vigilancia.profile', compact('empleado'));
    }
}
