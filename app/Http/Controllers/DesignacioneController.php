<?php

namespace App\Http\Controllers;

use App\Exports\HistorialGuardiasExport;
use App\Models\Designaciondia;
use App\Models\Designacione;
use App\Models\Empleado;
use App\Models\Vwdesignacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class DesignacioneController
 * @package App\Http\Controllers
 */
class DesignacioneController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:designaciones.index')->only(
            'index',
            'designacioneguardia',
            'seleccionarEmpleado'
        );
        $this->middleware('can:designaciones.create')->only('create', 'store');
        $this->middleware('can:designaciones.edit')->only('edit', 'update');
        $this->middleware('can:designaciones.destroy')->only('destroy');
        $this->middleware('can:admin.registros.asistencia')->only('marcaciones');
        $this->middleware('can:admin.registros.rondas')->only('show');
    }

    public function index()
    {
        $designaciones = Designacione::all();

        return view('admin.designacione.index', compact('designaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designacione = new Designacione();
        return view('admin.designacione.create', compact('designacione'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Designacione::$rules);

        $designacione = Designacione::create($request->all());

        return redirect()->route('designaciones.index')
            ->with('success', 'Designacione created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $designacione = Designacione::find($id);
        // $rondas = tablaRondas($designacione->id);
        // dd($rondas);
        return view('admin.designacione.show', compact('designacione'));
    }

    public function marcaciones($id)
    {
        $designacione = Designacione::find($id);
        return view('admin.designacione.marcaciones', compact('designacione'));
    }

    public function pdfRondas($id)
    {
        $designacione = Designacione::find($id);
        $rondas = tablaRondas($id);
        // return view('admin.designacione.pdfRonda', compact('designacione','rondas'));
        $pdf = Pdf::loadView('admin.designacione.pdfRonda', compact('designacione', 'rondas'));
        return $pdf->stream();
    }
    public function pdfMarcaciones($id)
    {
        $designacione = Designacione::find($id);
        $marcaciones = tablaMarcaciones($id);
        // return view('admin.designacione.pdfRonda', compact('designacione','rondas'));
        $pdf = Pdf::loadView('admin.designacione.pdfMarcaciones', compact('designacione', 'marcaciones'));
        return $pdf->stream();
    }
    public function pdfNovedades($id)
    {
        $designacione = Designacione::find($id);
        $novedades = $designacione->novedades;
        // return view('admin.designacione.pdfRonda', compact('designacione','rondas'));
        $pdf = Pdf::loadView('admin.designacione.pdfNovedades', compact('designacione', 'novedades'));
        return $pdf->stream();
    }

    public function pdfCronogramaMensual()
    {
        $data = Session::get('cronograma_data');
        $empleados = $data['employees'];
        $daysInMonth = $data['daysInMonth'];
        $year = $data['year'];
        $month = $data['month'];
        $pdf = Pdf::loadView('pdfs.cronograma-mensual', compact('empleados', 'daysInMonth', 'year', 'month'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream("Cronograma_Mensual_{$month}_{$year}_" . date('YmdHis') . ".pdf");
    }

    public function edit($id)
    {
        $designacione = Designacione::find($id);
        $designaciondia = $designacione->designaciondias->first();
        // dd($designaciondia);
        return view('admin.designacione.edit', compact('designacione', 'designaciondia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Designacione $designacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designacione $designacione)
    {
        request()->validate([
            "fechaInicio" => "required",
            "fechaFin" => "required",
        ]);
        DB::beginTransaction();
        try {
            $designacione->update([
                "fechaInicio" => $request->fechaInicio,
                "fechaFin" => $request->fechaFin,
                "intervalo_hv" => $request->intervalo_hv,
                "observaciones" => $request->observaciones,
            ]);

            $designaciondia = Designaciondia::where('designacione_id', $designacione->id)->first();
            $designaciondia->update([
                "lunes" => $request->lunes == "on" ? 1 : 0,
                "martes" => $request->martes == "on" ? 1 : 0,
                "miercoles" => $request->miercoles == "on" ? 1 : 0,
                "jueves" => $request->jueves == "on" ? 1 : 0,
                "viernes" => $request->viernes == "on" ? 1 : 0,
                "sabado" => $request->sabado == "on" ? 1 : 0,
                "domingo" => $request->domingo == "on" ? 1 : 0,
            ]);

            DB::commit();
            return redirect()->route('designaciones.index')
                ->with('success', 'Designación editada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('designaciones.index')
                ->with('error', 'Ha ocurrido un error');
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $designaciondia = Designaciondia::where('designacione_id', $id)->delete();
            $designacione = Designacione::find($id)->delete();
            DB::commit();
            return redirect()->route('designaciones.index')
                ->with('success', 'Designación eliminada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('designaciones.index')
                ->with('error', $th->getMessage());
            // ->with('error', 'Ha ocurrido un error');
        }
    }

    public function designacioneguardia()
    {
        $data = DB::select("SELECT e.id,CONCAT(e.nombres, ' ', e.apellidos) AS empleado,IF(e.cubrerelevos = 1, 'Sí', 'No') AS cubrerelevos, COALESCE(SUM(CASE WHEN vd.estado = 1 THEN 1 ELSE 0 END), 0)
        AS activos, COALESCE(SUM(CASE WHEN vd.estado = 0 THEN 1 ELSE 0 END), 0) AS inactivos
FROM empleados e
JOIN users u ON u.id = e.user_id
LEFT JOIN vwdesignaciones vd ON vd.empleado_id = e.id
WHERE
    e.area_id = 2
    AND u.status = 1
GROUP BY e.id, e.nombres, e.apellidos, e.cubrerelevos
ORDER BY e.cubrerelevos ASC, empleado;");
        $empleados = collect($data)->map(function ($item) {
            return [
                'id' => $item->id,
                'nombre' => $item->empleado
            ];
        });


        return view('admin.designacione.guardias', compact('data', 'empleados'))->extends('adminlte::page');
    }
    public function seleccionarEmpleado($empleado_id)
    {
        $designaciones = Vwdesignacione::where('empleado_id', $empleado_id)->get();
        return response()->json($designaciones);
    }

    public function exportar(Request $request)
    {
        return Excel::download(new HistorialGuardiasExport($request), 'Reporte-Historial-Guardias.xlsx');
    }
}
