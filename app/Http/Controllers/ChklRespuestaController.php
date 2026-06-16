<?php

namespace App\Http\Controllers;

use App\Models\ChklRespuesta;
use Illuminate\Http\Request;

/**
 * Class ChklRespuestaController
 * @package App\Http\Controllers
 */
class ChklRespuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chklRespuestas = ChklRespuesta::paginate();

        return view('chkl-respuesta.index', compact('chklRespuestas'))
            ->with('i', (request()->input('page', 1) - 1) * $chklRespuestas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chklRespuesta = new ChklRespuesta();
        return view('chkl-respuesta.create', compact('chklRespuesta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ChklRespuesta::$rules);

        $chklRespuesta = ChklRespuesta::create($request->all());

        return redirect()->route('chkl-respuestas.index')
            ->with('success', 'ChklRespuesta created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chklRespuesta = ChklRespuesta::find($id);

        return view('chkl-respuesta.show', compact('chklRespuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chklRespuesta = ChklRespuesta::find($id);

        return view('chkl-respuesta.edit', compact('chklRespuesta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ChklRespuesta $chklRespuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChklRespuesta $chklRespuesta)
    {
        request()->validate(ChklRespuesta::$rules);

        $chklRespuesta->update($request->all());

        return redirect()->route('chkl-respuestas.index')
            ->with('success', 'ChklRespuesta updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $chklRespuesta = ChklRespuesta::find($id)->delete();

        return redirect()->route('chkl-respuestas.index')
            ->with('success', 'ChklRespuesta deleted successfully');
    }
}
