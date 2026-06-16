<?php

namespace App\Http\Controllers;

use App\Models\Rrhhtipobono;
use Illuminate\Http\Request;

/**
 * Class RrhhtipobonoController
 * @package App\Http\Controllers
 */
class RrhhtipobonoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhtipobonos = Rrhhtipobono::all();

        return view('rrhhtipobono.index', compact('rrhhtipobonos'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhtipobono = new Rrhhtipobono();
        return view('rrhhtipobono.create', compact('rrhhtipobono'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhtipobono::$rules);

        $rrhhtipobono = Rrhhtipobono::create($request->all());

        return redirect()->route('rrhhtipobonos.index')
            ->with('success', 'Tipo de bono creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhtipobono = Rrhhtipobono::find($id);

        return view('rrhhtipobono.show', compact('rrhhtipobono'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhtipobono = Rrhhtipobono::find($id);

        return view('rrhhtipobono.edit', compact('rrhhtipobono'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhtipobono $rrhhtipobono
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rrhhtipobono = Rrhhtipobono::find($id);
        request()->validate(Rrhhtipobono::$rules);

        $rrhhtipobono->update($request->all());

        return redirect()->route('rrhhtipobonos.index')
            ->with('success', 'Tipo de bono editado correctamente.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhtipobono = Rrhhtipobono::find($id)->delete();

        return redirect()->route('rrhhtipobonos.index')
            ->with('success', 'Tipo de bono eliminado correctamente.');
    }
}
