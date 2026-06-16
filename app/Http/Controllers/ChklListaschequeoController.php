<?php

namespace App\Http\Controllers;

use App\Models\ChklListaschequeo;
use Illuminate\Http\Request;

/**
 * Class ChklListaschequeoController
 * @package App\Http\Controllers
 */
class ChklListaschequeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chklListaschequeos = ChklListaschequeo::paginate();

        return view('chkl-listaschequeo.index', compact('chklListaschequeos'))
            ->with('i', (request()->input('page', 1) - 1) * $chklListaschequeos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chklListaschequeo = new ChklListaschequeo();
        return view('chkl-listaschequeo.create', compact('chklListaschequeo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ChklListaschequeo::$rules);

        $chklListaschequeo = ChklListaschequeo::create($request->all());

        return redirect()->route('chkl-listaschequeos.index')
            ->with('success', 'ChklListaschequeo created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chklListaschequeo = ChklListaschequeo::find($id);

        return view('chkl-listaschequeo.show', compact('chklListaschequeo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chklListaschequeo = ChklListaschequeo::find($id);

        return view('chkl-listaschequeo.edit', compact('chklListaschequeo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ChklListaschequeo $chklListaschequeo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChklListaschequeo $chklListaschequeo)
    {
        request()->validate(ChklListaschequeo::$rules);

        $chklListaschequeo->update($request->all());

        return redirect()->route('chkl-listaschequeos.index')
            ->with('success', 'ChklListaschequeo updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $chklListaschequeo = ChklListaschequeo::find($id)->delete();

        return redirect()->route('chkl-listaschequeos.index')
            ->with('success', 'ChklListaschequeo deleted successfully');
    }
}
