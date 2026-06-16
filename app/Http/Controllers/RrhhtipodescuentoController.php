<?php

namespace App\Http\Controllers;

use App\Models\Rrhhtipodescuento;
use Illuminate\Http\Request;

/**
 * Class RrhhtipodescuentoController
 * @package App\Http\Controllers
 */
class RrhhtipodescuentoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:rrhhtipodescuentos.index')->only('index');
        $this->middleware('can:rrhhtipodescuentos.create')->only('create', 'store');
        $this->middleware('can:rrhhtipodescuentos.edit')->only('edit', 'update');
        $this->middleware('can:rrhhtipodescuentos.destroy')->only('destroy');
    }

    public function index()
    {
        $rrhhtipodescuentos = Rrhhtipodescuento::get();

        return view('rrhhtipodescuento.index', compact('rrhhtipodescuentos'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhtipodescuento = new Rrhhtipodescuento();
        return view('rrhhtipodescuento.create', compact('rrhhtipodescuento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhtipodescuento::$rules);

        $rrhhtipodescuento = Rrhhtipodescuento::create($request->all());

        return redirect()->route('rrhhtipodescuentos.index')
            ->with('success', 'Descuento registrado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhtipodescuento = Rrhhtipodescuento::find($id);

        return view('rrhhtipodescuento.show', compact('rrhhtipodescuento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhtipodescuento = Rrhhtipodescuento::find($id);

        return view('rrhhtipodescuento.edit', compact('rrhhtipodescuento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhtipodescuento $rrhhtipodescuento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rrhhtipodescuento_id)
    {
        $rrhhtipodescuento = Rrhhtipodescuento::find($rrhhtipodescuento_id);
        request()->validate(Rrhhtipodescuento::$rules);

        $rrhhtipodescuento->update($request->all());

        return redirect()->route('rrhhtipodescuentos.index')
            ->with('success', 'Descuento actualizado correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhtipodescuento = Rrhhtipodescuento::find($id)->delete();

        return redirect()->route('rrhhtipodescuentos.index')
            ->with('success', 'Descento eliminado correctamente.');
    }

    public function traeTipodescuento(Request $request)
    {
        $tipodescuento = Rrhhtipodescuento::find($request->rrhhtipodescuento_id);
        return response()->json(['success' => true, 'message' => $tipodescuento]);

    }
}
