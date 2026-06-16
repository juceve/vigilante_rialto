<?php

namespace App\Http\Controllers;

use App\Models\Hombrevivo;
use Illuminate\Http\Request;

/**
 * Class HombrevivoController
 * @package App\Http\Controllers
 */
class HombrevivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hombrevivos = Hombrevivo::paginate();

        return view('hombrevivo.index', compact('hombrevivos'))
            ->with('i', (request()->input('page', 1) - 1) * $hombrevivos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hombrevivo = new Hombrevivo();
        return view('hombrevivo.create', compact('hombrevivo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Hombrevivo::$rules);

        $hombrevivo = Hombrevivo::create($request->all());

        return redirect()->route('hombrevivos.index')
            ->with('success', 'Hombrevivo created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hombrevivo = Hombrevivo::find($id);

        return view('hombrevivo.show', compact('hombrevivo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hombrevivo = Hombrevivo::find($id);

        return view('hombrevivo.edit', compact('hombrevivo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Hombrevivo $hombrevivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hombrevivo $hombrevivo)
    {
        request()->validate(Hombrevivo::$rules);

        $hombrevivo->update($request->all());

        return redirect()->route('hombrevivos.index')
            ->with('success', 'Hombrevivo updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $hombrevivo = Hombrevivo::find($id)->delete();

        return redirect()->route('hombrevivos.index')
            ->with('success', 'Hombrevivo deleted successfully');
    }
}
