<?php

namespace App\Http\Controllers;

use App\Models\Marcacione;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class MarcacioneController
 * @package App\Http\Controllers
 */
class MarcacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcaciones = Marcacione::paginate();

        return view('marcacione.index', compact('marcaciones'))
            ->with('i', (request()->input('page', 1) - 1) * $marcaciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $marcacione = new Marcacione();
        return view('marcacione.create', compact('marcacione'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Marcacione::$rules);

        $marcacione = Marcacione::create($request->all());

        return redirect()->route('marcaciones.index')
            ->with('success', 'Marcacione created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marcacione = Marcacione::find($id);

        return view('marcacione.show', compact('marcacione'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $marcacione = Marcacione::find($id);

        return view('marcacione.edit', compact('marcacione'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Marcacione $marcacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marcacione $marcacione)
    {
        request()->validate(Marcacione::$rules);

        $marcacione->update($request->all());

        return redirect()->route('marcaciones.index')
            ->with('success', 'Marcacione updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $marcacione = Marcacione::find($id)->delete();

        return redirect()->route('marcaciones.index')
            ->with('success', 'Marcacione deleted successfully');
    }

    public function pdfRondas($id)
    {
        $parametros = Session::get('parametros');
        $movimientos = Session::get('contenedor1');
        $i = 0;
        $pdf = Pdf::loadView('pdfs.pdfmarcaciones')
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
