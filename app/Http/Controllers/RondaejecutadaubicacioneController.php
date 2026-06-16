<?php

namespace App\Http\Controllers;

use App\Models\Rondaejecutadaubicacione;
use Illuminate\Http\Request;

/**
 * Class RondaejecutadaubicacioneController
 * @package App\Http\Controllers
 */
class RondaejecutadaubicacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rondaejecutadaubicaciones = Rondaejecutadaubicacione::paginate();

        return view('rondaejecutadaubicacione.index', compact('rondaejecutadaubicaciones'))
            ->with('i', (request()->input('page', 1) - 1) * $rondaejecutadaubicaciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rondaejecutadaubicacione = new Rondaejecutadaubicacione();
        return view('rondaejecutadaubicacione.create', compact('rondaejecutadaubicacione'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rondaejecutadaubicacione::$rules);

        $rondaejecutadaubicacione = Rondaejecutadaubicacione::create($request->all());

        return redirect()->route('rondaejecutadaubicaciones.index')
            ->with('success', 'Rondaejecutadaubicacione created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rondaejecutadaubicacione = Rondaejecutadaubicacione::find($id);

        return view('rondaejecutadaubicacione.show', compact('rondaejecutadaubicacione'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rondaejecutadaubicacione = Rondaejecutadaubicacione::find($id);

        return view('rondaejecutadaubicacione.edit', compact('rondaejecutadaubicacione'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rondaejecutadaubicacione $rondaejecutadaubicacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rondaejecutadaubicacione $rondaejecutadaubicacione)
    {
        request()->validate(Rondaejecutadaubicacione::$rules);

        $rondaejecutadaubicacione->update($request->all());

        return redirect()->route('rondaejecutadaubicaciones.index')
            ->with('success', 'Rondaejecutadaubicacione updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rondaejecutadaubicacione = Rondaejecutadaubicacione::find($id)->delete();

        return redirect()->route('rondaejecutadaubicaciones.index')
            ->with('success', 'Rondaejecutadaubicacione deleted successfully');
    }
}
