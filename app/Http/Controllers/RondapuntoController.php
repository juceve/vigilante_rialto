<?php

namespace App\Http\Controllers;

use App\Models\Rondapunto;
use Illuminate\Http\Request;

/**
 * Class RondapuntoController
 * @package App\Http\Controllers
 */
class RondapuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rondapuntos = Rondapunto::paginate();

        return view('rondapunto.index', compact('rondapuntos'))
            ->with('i', (request()->input('page', 1) - 1) * $rondapuntos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rondapunto = new Rondapunto();
        return view('rondapunto.create', compact('rondapunto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rondapunto::$rules);

        $rondapunto = Rondapunto::create($request->all());

        return redirect()->route('rondapuntos.index')
            ->with('success', 'Rondapunto created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rondapunto = Rondapunto::find($id);

        return view('rondapunto.show', compact('rondapunto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rondapunto = Rondapunto::find($id);

        return view('rondapunto.edit', compact('rondapunto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rondapunto $rondapunto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rondapunto $rondapunto)
    {
        request()->validate(Rondapunto::$rules);

        $rondapunto->update($request->all());

        return redirect()->route('rondapuntos.index')
            ->with('success', 'Rondapunto updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rondapunto = Rondapunto::find($id)->delete();

        return redirect()->route('rondapuntos.index')
            ->with('success', 'Rondapunto deleted successfully');
    }
}
