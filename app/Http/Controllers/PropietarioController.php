<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use App\Models\Residencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

/**
 * Class PropietarioController
 * @package App\Http\Controllers
 */
class PropietarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propietarios = Propietario::paginate();

        return view('propietario.index', compact('propietarios'))
            ->with('i', (request()->input('page', 1) - 1) * $propietarios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $propietario = new Propietario();
        return view('propietario.create', compact('propietario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Propietario::$rules);

        $propietario = Propietario::create($request->all());

        return redirect()->route('propietarios.index')
            ->with('success', 'Propietario created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $propietario = Propietario::find($id);

        return view('propietario.show', compact('propietario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $propietario = Propietario::find($id);

        return view('propietario.edit', compact('propietario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Propietario $propietario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Propietario $propietario)
    {
        request()->validate(Propietario::$rules);

        $propietario->update($request->all());

        return redirect()->route('propietarios.index')
            ->with('success', 'Propietario updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $propietario = Propietario::find($id)->delete();

        return redirect()->route('propietarios.index')
            ->with('success', 'Propietario deleted successfully');
    }

    public function resumen($id)
    {
        $propietarioId = Crypt::decryptString($id);
        $propietario = Propietario::findOrFail($propietarioId);
        // $residenciasIds = [];

        // // Obtener solo las residencias recién registradas
        // if (Session::get('residencias_registradas')) {
        //     $residenciasIds = Session::get('residencias_registradas');
        // }

        // $residencias = Residencia::where('propietario_id', $propietario->id)
        //     ->whereIn('id', $residenciasIds)
        //     ->get();

        // Limpia la variable de sesión para evitar mostrar residencias viejas en futuros registros
        // session()->forget('residencias_registradas');

        return view('propietario.resumen', compact('propietario'));
    }
}
