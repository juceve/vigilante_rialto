<?php

namespace App\Http\Controllers;

use App\Models\Rrhhdocscontrato;
use Illuminate\Http\Request;

/**
 * Class RrhhdocscontratoController
 * @package App\Http\Controllers
 */
class RrhhdocscontratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rrhhdocscontratos = Rrhhdocscontrato::paginate();

        return view('rrhhdocscontrato.index', compact('rrhhdocscontratos'))
            ->with('i', (request()->input('page', 1) - 1) * $rrhhdocscontratos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rrhhdocscontrato = new Rrhhdocscontrato();
        return view('rrhhdocscontrato.create', compact('rrhhdocscontrato'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Rrhhdocscontrato::$rules);

        $rrhhdocscontrato = Rrhhdocscontrato::create($request->all());

        return redirect()->route('rrhhdocscontratos.index')
            ->with('success', 'Rrhhdocscontrato created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rrhhdocscontrato = Rrhhdocscontrato::find($id);

        return view('rrhhdocscontrato.show', compact('rrhhdocscontrato'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rrhhdocscontrato = Rrhhdocscontrato::find($id);

        return view('rrhhdocscontrato.edit', compact('rrhhdocscontrato'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Rrhhdocscontrato $rrhhdocscontrato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrhhdocscontrato $rrhhdocscontrato)
    {
        request()->validate(Rrhhdocscontrato::$rules);

        $rrhhdocscontrato->update($request->all());

        return redirect()->route('rrhhdocscontratos.index')
            ->with('success', 'Rrhhdocscontrato updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $rrhhdocscontrato = Rrhhdocscontrato::find($id)->delete();

        return redirect()->route('rrhhdocscontratos.index')
            ->with('success', 'Rrhhdocscontrato deleted successfully');
    }
}
