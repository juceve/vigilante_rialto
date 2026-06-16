<?php

namespace App\Http\Controllers;

use App\Models\Rrhhdetalledotacion;
use Illuminate\Http\Request;

/**
 * Class RrhhdetalledotacionController
 * @package App\Http\Controllers
 */
class RrhhdetalledotacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhdetalledotacions = Rrhhdetalledotacion::paginate();

        return view('rrhhdetalledotacion.index', compact('rrhhdetalledotacions'))
            ->with('i', (request()->input('page', 1) - 1) * $rrhhdetalledotacions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhdetalledotacion = new Rrhhdetalledotacion();
        return view('rrhhdetalledotacion.create', compact('rrhhdetalledotacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhdetalledotacion::$rules);

        $rrhhdetalledotacion = Rrhhdetalledotacion::create($request->all());

        return redirect()->route('rrhhdetalledotacions.index')
            ->with('success', 'Rrhhdetalledotacion created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhdetalledotacion = Rrhhdetalledotacion::find($id);

        return view('rrhhdetalledotacion.show', compact('rrhhdetalledotacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhdetalledotacion = Rrhhdetalledotacion::find($id);

        return view('rrhhdetalledotacion.edit', compact('rrhhdetalledotacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhdetalledotacion $rrhhdetalledotacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrhhdetalledotacion $rrhhdetalledotacion)
    {
        request()->validate(Rrhhdetalledotacion::$rules);

        $rrhhdetalledotacion->update($request->all());

        return redirect()->route('rrhhdetalledotacions.index')
            ->with('success', 'Rrhhdetalledotacion updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhdetalledotacion = Rrhhdetalledotacion::find($id)->delete();

        return redirect()->route('rrhhdetalledotacions.index')
            ->with('success', 'Rrhhdetalledotacion deleted successfully');
    }
}
