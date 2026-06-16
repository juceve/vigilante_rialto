<?php

namespace App\Http\Controllers;

use App\Models\Rrhhestado;
use Illuminate\Http\Request;

/**
 * Class RrhhestadoController
 * @package App\Http\Controllers
 */
class RrhhestadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:rrhhestados.index')->only('index');
        $this->middleware('can:rrhhestados.create')->only('create', 'store');
        $this->middleware('can:rrhhestados.edit')->only('edit', 'update');
        $this->middleware('can:rrhhestados.destroy')->only('destroy');
    }

    public function index()
    {
        $rrhhestados = Rrhhestado::all();

        return view('admin.rrhhestado.index', compact('rrhhestados'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhestado = new Rrhhestado();
        return view('admin.rrhhestado.create', compact('rrhhestado'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhestado::$rules);

        $rrhhestado = Rrhhestado::create($request->all());

        return redirect()->route('rrhhestados.index')
            ->with('success', 'Rrhhestado created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhestado = Rrhhestado::find($id);

        return view('admin.rrhhestado.show', compact('rrhhestado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhestado = Rrhhestado::find($id);

        return view('admin.rrhhestado.edit', compact('rrhhestado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhestado $rrhhestado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rrhhestado_id)
    {
        $rrhhestado = Rrhhestado::find($rrhhestado_id);
        request()->validate(Rrhhestado::$rules);

        $rrhhestado->update($request->all());

        return redirect()->route('rrhhestados.index')
            ->with('success', 'Rrhhestado updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhestado = Rrhhestado::find($id)->delete();

        return redirect()->route('rrhhestados.index')
            ->with('success', 'Rrhhestado deleted successfully');
    }
}
