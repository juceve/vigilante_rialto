<?php

namespace App\Http\Controllers;

use App\Models\Intervalo;
use Illuminate\Http\Request;

/**
 * Class IntervaloController
 * @package App\Http\Controllers
 */
class IntervaloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $intervalos = Intervalo::paginate();

        return view('intervalo.index', compact('intervalos'))
            ->with('i', (request()->input('page', 1) - 1) * $intervalos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $intervalo = new Intervalo();
        return view('intervalo.create', compact('intervalo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Intervalo::$rules);

        $intervalo = Intervalo::create($request->all());

        return redirect()->route('intervalos.index')
            ->with('success', 'Intervalo created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $intervalo = Intervalo::find($id);

        return view('intervalo.show', compact('intervalo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $intervalo = Intervalo::find($id);

        return view('intervalo.edit', compact('intervalo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Intervalo $intervalo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Intervalo $intervalo)
    {
        request()->validate(Intervalo::$rules);

        $intervalo->update($request->all());

        return redirect()->route('intervalos.index')
            ->with('success', 'Intervalo updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $intervalo = Intervalo::find($id)->delete();

        return redirect()->route('intervalos.index')
            ->with('success', 'Intervalo deleted successfully');
    }
}
