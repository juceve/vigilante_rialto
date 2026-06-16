<?php

namespace App\Http\Controllers;

use App\Models\Rondaejecutada;
use Illuminate\Http\Request;

class RecorridoRondaController extends Controller
{
    public function recorrido($rondaejecutada_id)
    {
        $rondaejecutada = Rondaejecutada::find($rondaejecutada_id);

        return view('admin.recorrido-ronda', compact('rondaejecutada') );
    }
}
