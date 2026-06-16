<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Rrhhadelanto;
use App\Models\Rrhhcargo;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhestadodotacion;
use App\Models\Rrhhtipobono;
use App\Models\Rrhhtipocontrato;
use App\Models\Rrhhtipopermiso;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RrhhKardexController extends Controller
{
    public function kardex($empleado_id)
    {
        $empleado = Empleado::find($empleado_id);

        $hoy = Carbon::now();
        $contratoActivo = RRHHContrato::where('empleado_id', $empleado_id)
            ->where('fecha_inicio', '<=', $hoy)
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', $hoy);
            })
            ->where('activo', true) // Si decidís usar esto como flag de validez
            ->orderBy('fecha_inicio', 'asc') // más antiguo primero si hay solapamiento
            ->first();
        $tipopermisos = Rrhhtipopermiso::all();
        $optionsAdelantos = Rrhhadelanto::estadoOptions();
        $tipobonos = Rrhhtipobono::all();
        $estadoDots = Rrhhestadodotacion::all();
        return view('admin.kardex.kardex', compact('empleado',  'contratoActivo', 'tipopermisos', 'optionsAdelantos', 'tipobonos', 'estadoDots'));
    }
}
