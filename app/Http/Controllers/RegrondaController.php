<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Regronda;
use App\Models\Vwronda;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class RegrondaController
 * @package App\Http\Controllers
 */
class RegrondaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regrondas = Regronda::paginate();

        return view('regronda.index', compact('regrondas'))
            ->with('i', (request()->input('page', 1) - 1) * $regrondas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regronda = new Regronda();
        return view('regronda.create', compact('regronda'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Regronda::$rules);

        $regronda = Regronda::create($request->all());

        return redirect()->route('regrondas.index')
            ->with('success', 'Regronda created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $regronda = Regronda::find($id);

        return view('regronda.show', compact('regronda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regronda = Regronda::find($id);

        return view('regronda.edit', compact('regronda'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Regronda $regronda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Regronda $regronda)
    {
        request()->validate(Regronda::$rules);

        $regronda->update($request->all());

        return redirect()->route('regrondas.index')
            ->with('success', 'Regronda updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $regronda = Regronda::find($id)->delete();

        return redirect()->route('regrondas.index')
            ->with('success', 'Regronda deleted successfully');
    }

    public function pdfRondas()
    {
        $parametros = Session::get('param-ronda');
        $cliente = Cliente::find($parametros[0]);
        $resultados = "";

        $resultados = Vwronda::where([
            ["fecha", ">=", $parametros[1]],
            ["fecha", "<=", $parametros[2]],
            ["cliente_id", $parametros[0]],
            ['empleado', 'LIKE', '%' . $parametros[3] . '%']
        ])->orWhere(
            [
                ["fecha", ">=", $parametros[1]],
                ["fecha", "<=", $parametros[2]],
                ["cliente_id", $parametros[0]],
                ['turno', 'LIKE', '%' . $parametros[3] . '%'],
            ]
        )->orderBy('fecha', 'DESC')
            ->get();

        $i = 1;
        // return view('pdfs.pdfrondas', compact('resultados', 'parametros', 'cliente', 'i'));

        $pdf = Pdf::loadView('pdfs.pdfrondas', compact('resultados', 'parametros', 'cliente', 'i'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }

    public function rondasEjecutadas(){
        $data = Session::get('rondas_ejecutadas', []);
        $fechas = Session::get('fechas_rondas', []);
        $pdf = Pdf::loadView('pdfs.rondasejecutadas', compact('data', 'fechas'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream('Reporte_Rondas_'.date('Ymd_His').'.pdf');
    }
}
