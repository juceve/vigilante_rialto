<?php

namespace App\Http\Controllers;

use App\Models\Oficina;
use Illuminate\Http\Request;


class OficinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:oficinas.index')->only('index');
        $this->middleware('can:oficinas.create')->only('create', 'store');
        $this->middleware('can:oficinas.edit')->only('edit', 'update');
        $this->middleware('can:oficinas.destroy')->only('destroy');
    }

    public function index()
    {
        $oficinas = Oficina::all();

        return view('admin.oficina.index', compact('oficinas'))
            ->with('i', 0);
    }


    public function create()
    {
        $oficina = new Oficina();
        return view('admin.oficina.create', compact('oficina'));
    }


    public function store(Request $request)
    {
        request()->validate(Oficina::$rules);

        $oficina = Oficina::create($request->all());

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina creada correctamente.');
    }


    public function show($id)
    {
        $oficina = Oficina::find($id);

        return view('admin.oficina.show', compact('oficina'));
    }


    public function edit($id)
    {
        $oficina = Oficina::find($id);

        return view('admin.oficina.edit', compact('oficina'));
    }


    public function update(Request $request, Oficina $oficina)
    {
        request()->validate(Oficina::$rules);

        $oficina->update($request->all());

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina actualizada correctamente');
    }


    public function destroy($id)
    {
        $oficina = Oficina::find($id)->delete();

        return redirect()->route('oficinas.index')
            ->with('success', 'Oficina eliminada correctamente');
    }
}
