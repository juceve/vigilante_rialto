<?php

namespace App\Http\Controllers;

use App\Models\Motivo;
use Illuminate\Http\Request;

/**
 * Class MotivoController
 * @package App\Http\Controllers
 */
class MotivoController extends Controller
{
   public function __construct()
    {
        $this->middleware('can:motivos.index')->only('index');
        $this->middleware('can:motivos.create')->only('create', 'store');
        $this->middleware('can:motivos.edit')->only('edit', 'update');
        $this->middleware('can:motivos.destroy')->only('destroy');
    }
    public function index()
    {
        $motivos = Motivo::all();

        return view('motivo.index', compact('motivos'))
            ->with('i', 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $motivo = new Motivo();
        return view('motivo.create', compact('motivo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Motivo::$rules);

        $motivo = Motivo::create($request->all());

        return redirect()->route('motivos.index')
            ->with('success', 'Motivo registrado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $motivo = Motivo::find($id);

        return view('motivo.show', compact('motivo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $motivo = Motivo::find($id);

        return view('motivo.edit', compact('motivo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Motivo $motivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $motivo_id)
    {
        request()->validate(Motivo::$rules);
        $motivo = Motivo::find($motivo_id);

        $motivo->nombre = $request->nombre;
        $motivo->estado = $request->estado;
        $motivo->save();

        return redirect()->route('motivos.index')
            ->with('success', 'Motivo actualizado correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $motivo = Motivo::find($id)->delete();

        return redirect()->route('motivos.index')
            ->with('success', 'Motivo eliminado correctamennte');
    }
}
