<?php

namespace App\Http\Controllers;

use App\Models\ChklEjecucione;
use Illuminate\Http\Request;

/**
 * Class ChklEjecucioneController
 * @package App\Http\Controllers
 */
class ChklEjecucioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chklEjecuciones = ChklEjecucione::paginate();

        return view('chkl-ejecucione.index', compact('chklEjecuciones'))
            ->with('i', (request()->input('page', 1) - 1) * $chklEjecuciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chklEjecucione = new ChklEjecucione();
        return view('chkl-ejecucione.create', compact('chklEjecucione'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ChklEjecucione::$rules);

        $chklEjecucione = ChklEjecucione::create($request->all());

        return redirect()->route('chkl-ejecuciones.index')
            ->with('success', 'ChklEjecucione created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chklEjecucione = ChklEjecucione::find($id);

        return view('chkl-ejecucione.show', compact('chklEjecucione'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chklEjecucione = ChklEjecucione::find($id);

        return view('chkl-ejecucione.edit', compact('chklEjecucione'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ChklEjecucione $chklEjecucione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChklEjecucione $chklEjecucione)
    {
        request()->validate(ChklEjecucione::$rules);

        $chklEjecucione->update($request->all());

        return redirect()->route('chkl-ejecuciones.index')
            ->with('success', 'ChklEjecucione updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $chklEjecucione = ChklEjecucione::find($id)->delete();

        return redirect()->route('chkl-ejecuciones.index')
            ->with('success', 'ChklEjecucione deleted successfully');
    }
}
