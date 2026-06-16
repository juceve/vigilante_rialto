<?php

namespace App\Http\Controllers;

use App\Models\Sistemaparametro;
use Illuminate\Http\Request;

class SistemaparametroController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:sistemaparametros.index')->only('index');
        $this->middleware('can:sistemaparametros.edit')->only('edit', 'update');
    }

    public function index()
    {
        $sistemaparametros = Sistemaparametro::paginate();

        return view('sistemaparametro.index', compact('sistemaparametros'))
            ->with('i', 0);
    }

    public function edit($id)
    {
        $sistemaparametro = Sistemaparametro::find($id);

        return view('sistemaparametro.edit', compact('sistemaparametro'));
    }

    public function update(Request $request, $sistemaparametro_id)
    {
        $sistemaparametro = Sistemaparametro::find($sistemaparametro_id);
        request()->validate(Sistemaparametro::$rules);

        $sistemaparametro->update($request->all());

        return redirect()->route('sistemaparametros.index')
            ->with('success', 'Parametros del Sistema actualizados correctamente.');
    }
}
