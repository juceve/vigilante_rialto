<?php

namespace App\Http\Controllers;

use App\Models\Rrhhasistencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class RrhhasistenciaController
 * @package App\Http\Controllers
 */
class RrhhasistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhasistencias = Rrhhasistencia::paginate();

        return view('rrhhasistencia.index', compact('rrhhasistencias'))
            ->with('i', (request()->input('page', 1) - 1) * $rrhhasistencias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhasistencia = new Rrhhasistencia();
        return view('rrhhasistencia.create', compact('rrhhasistencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhasistencia::$rules);

        $rrhhasistencia = Rrhhasistencia::create($request->all());

        return redirect()->route('rrhhasistencias.index')
            ->with('success', 'Rrhhasistencia created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhasistencia = Rrhhasistencia::find($id);

        return view('rrhhasistencia.show', compact('rrhhasistencia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhasistencia = Rrhhasistencia::find($id);

        return view('rrhhasistencia.edit', compact('rrhhasistencia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhasistencia $rrhhasistencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrhhasistencia $rrhhasistencia)
    {
        request()->validate(Rrhhasistencia::$rules);

        $rrhhasistencia->update($request->all());

        return redirect()->route('rrhhasistencias.index')
            ->with('success', 'Rrhhasistencia updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhasistencia = Rrhhasistencia::find($id)->delete();

        return redirect()->route('rrhhasistencias.index')
            ->with('success', 'Rrhhasistencia deleted successfully');
    }

    public function reporteAjax(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        // Generar rango de fechas
        $fechas = collect();
        $currentDate = Carbon::parse($fecha_inicio);
        $fechaFin = Carbon::parse($fecha_fin);

        while ($currentDate->lte($fechaFin)) {
            $fechas->push($currentDate->toDateString());
            $currentDate->addDay();
        }

        // Obtener empleados con designaciones activas en el rango
        $empleados = DB::table('designaciones as d')
            ->join('empleados as e', 'e.id', '=', 'd.empleado_id')
            ->select(
                'd.empleado_id',
                DB::raw("CONCAT(e.nombres, ' ', e.apellidos) as empleado"),
                'd.fechaInicio',
                'd.fechaFin'
            )
            ->where(function ($q) use ($fecha_inicio, $fecha_fin) {
                $q->where('d.fechaInicio', '<=', $fecha_fin)
                    ->where('d.fechaFin', '>=', $fecha_inicio);
            })
            ->groupBy('d.empleado_id', 'e.nombres', 'e.apellidos', 'd.fechaInicio', 'd.fechaFin')
            ->get();

        // Obtener asistencias en el rango
        $asistencias = DB::table('vwasistencias')
            ->whereDate('fecha', '>=', $fecha_inicio)
            ->whereDate('fecha', '<=', $fecha_fin)
            ->get()
            ->groupBy(function ($item) {
                return $item->empleado_id . '|' . \Carbon\Carbon::parse($item->fecha)->toDateString();
            });
        // Obtener asistencias de hoy desde rrhhasistencias
        $asistenciasHoy = DB::table('rrhhasistencias as a')
            ->join('rrhhestados as e', 'a.rrhhestado_id', '=', 'e.id')
            ->select('a.empleado_id', 'a.fecha', 'a.ingreso', 'e.nombre_corto', 'e.color')
            ->whereDate('a.fecha', Carbon::today())
            ->get()
            ->keyBy(function ($item) {
                return $item->empleado_id . '|' . \Carbon\Carbon::parse($item->fecha)->toDateString();
            });

        $resultado = [];

        $fechaHoy = Carbon::today()->toDateString();

        foreach ($empleados as $empleado) {
            $fila = ['Empleado' => $empleado->empleado];

            foreach ($fechas as $fecha) {
                $tieneDesignacion = ($fecha >= $empleado->fechaInicio && $fecha <= $empleado->fechaFin);

                if (!$tieneDesignacion) {
                    $fila[$fecha] = 'S/D~' . $empleado->empleado_id;
                    continue;
                }

                $clave = $empleado->empleado_id . '|' . $fecha;

                if ($fecha === $fechaHoy) {
                    if ($asistenciasHoy->has($clave)) {
                        $asistenciaHoy = $asistenciasHoy[$clave];
                        $fila[$fecha] = $asistenciaHoy->nombre_corto . '~' . $empleado->empleado_id . '~' . $asistenciaHoy->color . '~0';
                        continue;
                    }
                }

                if ($asistencias->has($clave)) {
                    $asistencia = $asistencias[$clave]->first();
                    $fila[$fecha] = substr($asistencia->ingreso, -8) . '~' . $empleado->empleado_id . '~0~0';
                } else {
                    $fila[$fecha] = 'S/M~' . $empleado->empleado_id . '~0~1';
                }
            }

            $resultado[] = $fila;
        }

        return response()->json([
            'fechas' => $fechas,
            'datos' => $resultado,
        ]);
    }

    public function guardar(Request $request)
    {


        $request->validate([
            'empleado_id' => 'required|integer|exists:empleados,id',
            'rrhhestado_id' => 'required|integer|exists:rrhhestados,id',
            'ingreso' => 'required',
        ]);

        try {
            $asistencia = Rrhhasistencia::create([
                "empleado_id" => $request->empleado_id,
                "fecha" => date('Y-m-d'),
                "ingreso" => $request->ingreso,
                "rrhhestado_id" => $request->rrhhestado_id,
            ]);

            return response()->json(['success' => true, 'message' => 'Asistencia guardada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar asistencia', 'error' => $e->getMessage()], 500);
        }
    }
}
