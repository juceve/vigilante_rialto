<?php

namespace App\Http\Controllers;

use App\Models\Rrhhtipocontrato;
use Illuminate\Http\Request;

/**
 * Class RrhhtipocontratoController
 * @package App\Http\Controllers
 */
class RrhhtipocontratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhtipocontratos = Rrhhtipocontrato::all();

        return view('rrhhtipocontrato.index', compact('rrhhtipocontratos'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhtipocontrato = new Rrhhtipocontrato();
        return view('rrhhtipocontrato.create', compact('rrhhtipocontrato'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'nombre' => 'required',
            'cantidad_dias' => 'required',
            'activo' => 'required',
        ]);

        $rrhhtipocontrato = Rrhhtipocontrato::create([
            'codigo' => rand(1, 100) . '-' . date('YmdHis'),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'mensualizado' => $request->mensualizado,
            'cantidad_dias' => $request->cantidad_dias,
            'horas_dia' => 0,
            'sueldo_referencial' => $request->sueldo_referencial,
        ]);

        return redirect()->route('rrhhtipocontratos.index')
            ->with('success', 'Tipo de contrato creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhtipocontrato = Rrhhtipocontrato::find($id);

        return view('rrhhtipocontrato.show', compact('rrhhtipocontrato'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhtipocontrato = Rrhhtipocontrato::find($id);

        return view('rrhhtipocontrato.edit', compact('rrhhtipocontrato'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhtipocontrato $rrhhtipocontrato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rrhhtipocontrato_id)
    {
        $rrhhtipocontrato = Rrhhtipocontrato::find($rrhhtipocontrato_id);
        request()->validate(Rrhhtipocontrato::$rules);

        $rrhhtipocontrato->update($request->all());

        return redirect()->route('rrhhtipocontratos.index')
            ->with('success', 'Tipo de contrato editado correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhtipocontrato = Rrhhtipocontrato::find($id)->delete();

        return redirect()->route('rrhhtipocontratos.index')
            ->with('success', 'Tipo de contrato eliminado correctamente');
    }
}
