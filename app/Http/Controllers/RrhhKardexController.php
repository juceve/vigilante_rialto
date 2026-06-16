<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Rrhhadelanto;
use App\Models\Rrhhcargo;
use App\Models\Rrhhcontrato;
use App\Models\Rrhhdescuento;
use App\Models\Rrhhestadodotacion;
use App\Models\Rrhhtipobono;
use App\Models\Rrhhtipocontrato;
use App\Models\Rrhhtipodescuento;
use App\Models\Rrhhtipopermiso;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RrhhKardexController extends Controller
{
    public function kardex($empleado_id)
    {
        $empleado = Empleado::find($empleado_id);

        $hoy = Carbon::now()->toDateString();

        // 1. Actualiza contratos vencidos a estado 0
        Rrhhcontrato::where('empleado_id', $empleado_id)
            ->whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '<', $hoy)
            ->where('activo', true)
            ->update(['activo' => false]);

        // 2. Busca el contrato activo
        $contratoActivo = Rrhhcontrato::where('empleado_id', $empleado_id)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', $hoy);
            })
            ->where('activo', true)
            ->orderBy('fecha_inicio', 'asc')
            ->first();
            

        $tipopermisos = Rrhhtipopermiso::all();
        $optionsAdelantos = Rrhhadelanto::estadoOptions();
        $tipobonos = Rrhhtipobono::all();
        $tipodescuentos = Rrhhtipodescuento::all();
        $estadoDots = Rrhhestadodotacion::all();

        return view('admin.kardex.kardex', compact('empleado',  'contratoActivo', 'tipopermisos', 'optionsAdelantos', 'tipobonos', 'estadoDots','tipodescuentos'));
    }
}
