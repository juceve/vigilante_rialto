<?php

namespace App\Http\Controllers;

use App\Models\Rrhhtipopermiso;
use Illuminate\Http\Request;

/**
 * Class RrhhtipopermisoController
 * @package App\Http\Controllers
 */
class RrhhtipopermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhtipopermisos = Rrhhtipopermiso::all();

        return view('rrhhtipopermiso.index', compact('rrhhtipopermisos'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhtipopermiso = new Rrhhtipopermiso();
        return view('rrhhtipopermiso.create', compact('rrhhtipopermiso'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhtipopermiso::$rules);

        $rrhhtipopermiso = Rrhhtipopermiso::create($request->all());

        return redirect()->route('rrhhtipopermisos.index')
            ->with('success', 'Tipo Permiso creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhtipopermiso = Rrhhtipopermiso::find($id);

        return view('rrhhtipopermiso.show', compact('rrhhtipopermiso'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhtipopermiso = Rrhhtipopermiso::find($id);

        return view('rrhhtipopermiso.edit', compact('rrhhtipopermiso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhtipopermiso $rrhhtipopermiso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rrhhtipopermiso_id)
    {
        $rrhhtipopermiso = Rrhhtipopermiso::find($rrhhtipopermiso_id);
        request()->validate(Rrhhtipopermiso::$rules);

        $rrhhtipopermiso->update($request->all());

        return redirect()->route('rrhhtipopermisos.index')
            ->with('success', 'Tipo Permiso editado correctamente.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhtipopermiso = Rrhhtipopermiso::find($id)->delete();

        return redirect()->route('rrhhtipopermisos.index')
            ->with('success', 'Tipo Permiso eliminado correctamente.');
    }
}
