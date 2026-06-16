<?php

namespace App\Http\Controllers;

use App\Models\Rrhhdotacion;
use App\Models\Rrhhestadodotacion;
use Illuminate\Http\Request;

class RrhhestadodotacionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:rrhhestadodotaciones.index')->only('index');
        $this->middleware('can:rrhhestadodotaciones.create')->only('create', 'store');
        $this->middleware('can:rrhhestadodotaciones.edit')->only('edit', 'update');
        $this->middleware('can:rrhhestadodotaciones.destroy')->only('destroy');
    }

    public function index()
    {
        $rrhhestadodotacions = Rrhhestadodotacion::all();

        return view('rrhhestadodotacion.index', compact('rrhhestadodotacions'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhestadodotacion = new Rrhhestadodotacion();
        return view('rrhhestadodotacion.create', compact('rrhhestadodotacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhestadodotacion::$rules);

        $rrhhestadodotacion = Rrhhestadodotacion::create($request->all());

        return redirect()->route('rrhhestadodotacions.index')
            ->with('success', 'Estado creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhestadodotacion = Rrhhestadodotacion::find($id);

        return view('rrhhestadodotacion.show', compact('rrhhestadodotacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhestadodotacion = Rrhhestadodotacion::find($id);

        return view('rrhhestadodotacion.edit', compact('rrhhestadodotacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhestadodotacion $rrhhestadodotacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rrhhestadodotacion = Rrhhestadodotacion::find($id);


        $rrhhestadodotacion->update($request->all());

        return redirect()->route('rrhhestadodotacions.index')
            ->with('success', 'Estado editado correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhestadodotacion = Rrhhestadodotacion::find($id)->delete();

        return redirect()->route('rrhhestadodotacions.index')
            ->with('success', 'Estado eliminado correctamente');
    }
}
