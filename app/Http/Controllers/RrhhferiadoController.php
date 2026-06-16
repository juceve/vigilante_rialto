<?php

namespace App\Http\Controllers;

use App\Models\Rrhhferiado;
use Illuminate\Http\Request;

/**
 * Class RrhhferiadoController
 * @package App\Http\Controllers
 */
class RrhhferiadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhferiados = Rrhhferiado::paginate();

        return view('rrhhferiado.index', compact('rrhhferiados'))
            ->with('i', (request()->input('page', 1) - 1) * $rrhhferiados->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhferiado = new Rrhhferiado();
        return view('rrhhferiado.create', compact('rrhhferiado'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhferiado::$rules);

        $rrhhferiado = Rrhhferiado::create($request->all());

        return redirect()->route('rrhhferiados.index')
            ->with('success', 'Rrhhferiado created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhferiado = Rrhhferiado::find($id);

        return view('rrhhferiado.show', compact('rrhhferiado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhferiado = Rrhhferiado::find($id);

        return view('rrhhferiado.edit', compact('rrhhferiado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhferiado $rrhhferiado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrhhferiado $rrhhferiado)
    {
        request()->validate(Rrhhferiado::$rules);

        $rrhhferiado->update($request->all());

        return redirect()->route('rrhhferiados.index')
            ->with('success', 'Rrhhferiado updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhferiado = Rrhhferiado::find($id)->delete();

        return redirect()->route('rrhhferiados.index')
            ->with('success', 'Rrhhferiado deleted successfully');
    }
}
