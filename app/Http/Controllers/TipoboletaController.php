<?php

namespace App\Http\Controllers;

use App\Models\Rrhhtipodescuento;
use App\Models\Tipoboleta;
use Illuminate\Http\Request;

/**
 * Class TipoboletaController
 * @package App\Http\Controllers
 */
class TipoboletaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipoboletas = Tipoboleta::paginate();
        

        return view('admin.tipoboleta.index', compact('tipoboletas'))
            ->with('i', (request()->input('page', 1) - 1) * $tipoboletas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipoboleta = new Tipoboleta();
        $tipodescuentos = Rrhhtipodescuento::all()->pluck('nombre', 'id');
        return view('admin.tipoboleta.create', compact('tipoboleta','tipodescuentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Tipoboleta::$rules);

        $tipoboleta = Tipoboleta::create($request->all());

        return redirect()->route('tipoboletas.index')
            ->with('success', 'Tipo de Boleta creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipoboleta = Tipoboleta::find($id);

        return view('admin.tipoboleta.show', compact('tipoboleta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipoboleta = Tipoboleta::find($id);
        $tipodescuentos = Rrhhtipodescuento::all()->pluck('nombre', 'id');

        return view('admin.tipoboleta.edit', compact('tipoboleta','tipodescuentos'));
    }


    public function update(Request $request, $tipoboleta_id)
    {

        $tipoboleta = Tipoboleta::find($tipoboleta_id);
        request()->validate(Tipoboleta::$rules);
        

        $tipoboleta->update($request->all());
        
        return redirect()->route('tipoboletas.index')
            ->with('success', 'Tipo de Boleta actualizado correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tipoboleta = Tipoboleta::find($id)->delete();

        return redirect()->route('tipoboletas.index')
            ->with('success', 'Tipo de Boleta eliminado correctamente');
    }
}
