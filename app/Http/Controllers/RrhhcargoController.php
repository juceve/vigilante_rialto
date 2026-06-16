<?php

namespace App\Http\Controllers;

use App\Models\Rrhhcargo;
use Illuminate\Http\Request;

/**
 * Class RrhhcargoController
 * @package App\Http\Controllers
 */
class RrhhcargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhcargos = Rrhhcargo::all();

        return view('rrhhcargo.index', compact('rrhhcargos'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhcargo = new Rrhhcargo();
        return view('rrhhcargo.create', compact('rrhhcargo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhcargo::$rules);

        $rrhhcargo = Rrhhcargo::create($request->all());

        return redirect()->route('rrhhcargos.index')
            ->with('success', 'Cargo creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhcargo = Rrhhcargo::find($id);

        return view('rrhhcargo.show', compact('rrhhcargo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhcargo = Rrhhcargo::find($id);

        return view('rrhhcargo.edit', compact('rrhhcargo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhcargo $rrhhcargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrhhcargo $rrhhcargo)
    {
        request()->validate(Rrhhcargo::$rules);

        $rrhhcargo->update($request->all());

        return redirect()->route('rrhhcargos.index')
            ->with('success', 'Cargo actualizado correctamente.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhcargo = Rrhhcargo::find($id)->delete();

        return redirect()->route('rrhhcargos.index')
            ->with('success', 'Cargo eliminado correctamente.');
    }
}
