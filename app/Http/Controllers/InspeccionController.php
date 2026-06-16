<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use Illuminate\Http\Request;

/**
 * Class InspeccionController
 * @package App\Http\Controllers
 */
class InspeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspeccions = Inspeccion::paginate();

        return view('inspeccion.index', compact('inspeccions'))
            ->with('i', (request()->input('page', 1) - 1) * $inspeccions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspeccion = new Inspeccion();
        return view('inspeccion.create', compact('inspeccion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Inspeccion::$rules);

        $inspeccion = Inspeccion::create($request->all());

        return redirect()->route('inspeccions.index')
            ->with('success', 'Inspeccion created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inspeccion = Inspeccion::find($id);

        return view('inspeccion.show', compact('inspeccion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspeccion = Inspeccion::find($id);

        return view('inspeccion.edit', compact('inspeccion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Inspeccion $inspeccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inspeccion $inspeccion)
    {
        request()->validate(Inspeccion::$rules);

        $inspeccion->update($request->all());

        return redirect()->route('inspeccions.index')
            ->with('success', 'Inspeccion updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $inspeccion = Inspeccion::find($id)->delete();

        return redirect()->route('inspeccions.index')
            ->with('success', 'Inspeccion deleted successfully');
    }
}
