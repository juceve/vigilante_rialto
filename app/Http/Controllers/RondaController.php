<?php

namespace App\Http\Controllers;

use App\Models\Ronda;
use Illuminate\Http\Request;

/**
 * Class RondaController
 * @package App\Http\Controllers
 */
class RondaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rondas = Ronda::paginate();

        return view('ronda.index', compact('rondas'))
            ->with('i', (request()->input('page', 1) - 1) * $rondas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ronda = new Ronda();
        return view('ronda.create', compact('ronda'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Ronda::$rules);

        $ronda = Ronda::create($request->all());

        return redirect()->route('rondas.index')
            ->with('success', 'Ronda created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ronda = Ronda::find($id);

        return view('ronda.show', compact('ronda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ronda = Ronda::find($id);

        return view('ronda.edit', compact('ronda'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Ronda $ronda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ronda $ronda)
    {
        request()->validate(Ronda::$rules);

        $ronda->update($request->all());

        return redirect()->route('rondas.index')
            ->with('success', 'Ronda updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $ronda = Ronda::find($id)->delete();

        return redirect()->route('rondas.index')
            ->with('success', 'Ronda deleted successfully');
    }
}
