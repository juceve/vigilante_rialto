<?php

namespace App\Http\Controllers;

use App\Models\Movimientocaja;
use Illuminate\Http\Request;

/**
 * Class MovimientocajaController
 * @package App\Http\Controllers
 */
class MovimientocajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movimientocajas = Movimientocaja::paginate();

        return view('movimientocaja.index', compact('movimientocajas'))
            ->with('i', (request()->input('page', 1) - 1) * $movimientocajas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $movimientocaja = new Movimientocaja();
        return view('movimientocaja.create', compact('movimientocaja'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Movimientocaja::$rules);

        $movimientocaja = Movimientocaja::create($request->all());

        return redirect()->route('movimientocajas.index')
            ->with('success', 'Movimientocaja created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movimientocaja = Movimientocaja::find($id);

        return view('movimientocaja.show', compact('movimientocaja'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movimientocaja = Movimientocaja::find($id);

        return view('movimientocaja.edit', compact('movimientocaja'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Movimientocaja $movimientocaja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movimientocaja $movimientocaja)
    {
        request()->validate(Movimientocaja::$rules);

        $movimientocaja->update($request->all());

        return redirect()->route('movimientocajas.index')
            ->with('success', 'Movimientocaja updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $movimientocaja = Movimientocaja::find($id)->delete();

        return redirect()->route('movimientocajas.index')
            ->with('success', 'Movimientocaja deleted successfully');
    }
}
