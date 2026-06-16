<?php

namespace App\Http\Controllers;

use App\Models\Cldotaciondetalle;
use Illuminate\Http\Request;

/**
 * Class CldotaciondetalleController
 * @package App\Http\Controllers
 */
class CldotaciondetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cldotaciondetalles = Cldotaciondetalle::paginate();

        return view('cldotaciondetalle.index', compact('cldotaciondetalles'))
            ->with('i', (request()->input('page', 1) - 1) * $cldotaciondetalles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cldotaciondetalle = new Cldotaciondetalle();
        return view('cldotaciondetalle.create', compact('cldotaciondetalle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Cldotaciondetalle::$rules);

        $cldotaciondetalle = Cldotaciondetalle::create($request->all());

        return redirect()->route('cldotaciondetalles.index')
            ->with('success', 'Cldotaciondetalle created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cldotaciondetalle = Cldotaciondetalle::find($id);

        return view('cldotaciondetalle.show', compact('cldotaciondetalle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cldotaciondetalle = Cldotaciondetalle::find($id);

        return view('cldotaciondetalle.edit', compact('cldotaciondetalle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Cldotaciondetalle $cldotaciondetalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cldotaciondetalle $cldotaciondetalle)
    {
        request()->validate(Cldotaciondetalle::$rules);

        $cldotaciondetalle->update($request->all());

        return redirect()->route('cldotaciondetalles.index')
            ->with('success', 'Cldotaciondetalle updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $cldotaciondetalle = Cldotaciondetalle::find($id)->delete();

        return redirect()->route('cldotaciondetalles.index')
            ->with('success', 'Cldotaciondetalle deleted successfully');
    }
}
