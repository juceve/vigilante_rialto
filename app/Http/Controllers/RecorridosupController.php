<?php

namespace App\Http\Controllers;

use App\Models\Recorridosup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RecorridosupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitud' => 'required',
            'longitud' => 'required',
        ]);
        $designacion_super = Session::get('designacion-super');
        Recorridosup::create([
            'designacionsupervisor_id' => $designacion_super,
            'fecha_hora' => now(),
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);
        if ($request->latitud == 0 || $request->longitud == 0) {
            return response()->json(['ok' => false]);
        } else {
            return response()->json(['ok' => true]);
        }
    }

    public function mapa(Request $request)
    {
        $fecha = $request->fecha ?? date('Y-m-d');

        $recorridos = Recorridosup::whereDate('fecha_hora', $fecha)
            ->orderBy('fecha_hora')
            ->get();

        return view('recorridos.mapa', compact('recorridos', 'fecha'));
    }
}
