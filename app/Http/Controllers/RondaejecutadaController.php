<?php

namespace App\Http\Controllers;

use App\Models\Rondaejecutada;
use Illuminate\Http\Request;

/**
 * Class RondaejecutadaController
 * @package App\Http\Controllers
 */
class RondaejecutadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rondaejecutadas = Rondaejecutada::paginate();

        return view('rondaejecutada.index', compact('rondaejecutadas'))
            ->with('i', (request()->input('page', 1) - 1) * $rondaejecutadas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rondaejecutada = new Rondaejecutada();
        return view('rondaejecutada.create', compact('rondaejecutada'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rondaejecutada::$rules);

        $rondaejecutada = Rondaejecutada::create($request->all());

        return redirect()->route('rondaejecutadas.index')
            ->with('success', 'Rondaejecutada created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rondaejecutada = Rondaejecutada::find($id);

        return view('rondaejecutada.show', compact('rondaejecutada'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rondaejecutada = Rondaejecutada::find($id);

        return view('rondaejecutada.edit', compact('rondaejecutada'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rondaejecutada $rondaejecutada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rondaejecutada $rondaejecutada)
    {
        request()->validate(Rondaejecutada::$rules);

        $rondaejecutada->update($request->all());

        return redirect()->route('rondaejecutadas.index')
            ->with('success', 'Rondaejecutada updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rondaejecutada = Rondaejecutada::find($id)->delete();

        return redirect()->route('rondaejecutadas.index')
            ->with('success', 'Rondaejecutada deleted successfully');
    }
}
