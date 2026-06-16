<?php

namespace App\Http\Controllers;

use App\Models\ChklPregunta;
use Illuminate\Http\Request;

/**
 * Class ChklPreguntaController
 * @package App\Http\Controllers
 */
class ChklPreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chklPreguntas = ChklPregunta::paginate();

        return view('chkl-pregunta.index', compact('chklPreguntas'))
            ->with('i', (request()->input('page', 1) - 1) * $chklPreguntas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chklPregunta = new ChklPregunta();
        return view('chkl-pregunta.create', compact('chklPregunta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ChklPregunta::$rules);

        $chklPregunta = ChklPregunta::create($request->all());

        return redirect()->route('chkl-preguntas.index')
            ->with('success', 'ChklPregunta created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chklPregunta = ChklPregunta::find($id);

        return view('chkl-pregunta.show', compact('chklPregunta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chklPregunta = ChklPregunta::find($id);

        return view('chkl-pregunta.edit', compact('chklPregunta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ChklPregunta $chklPregunta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChklPregunta $chklPregunta)
    {
        request()->validate(ChklPregunta::$rules);

        $chklPregunta->update($request->all());

        return redirect()->route('chkl-preguntas.index')
            ->with('success', 'ChklPregunta updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $chklPregunta = ChklPregunta::find($id)->delete();

        return redirect()->route('chkl-preguntas.index')
            ->with('success', 'ChklPregunta deleted successfully');
    }
}
