<?php

namespace App\Http\Controllers;

use App\Models\Ctrlpunto;
use Illuminate\Http\Request;

/**
 * Class CtrlpuntoController
 * @package App\Http\Controllers
 */
class CtrlpuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ctrlpuntos = Ctrlpunto::paginate();

        return view('ctrlpunto.index', compact('ctrlpuntos'))
            ->with('i', (request()->input('page', 1) - 1) * $ctrlpuntos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ctrlpunto = new Ctrlpunto();
        return view('ctrlpunto.create', compact('ctrlpunto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Ctrlpunto::$rules);

        $ctrlpunto = Ctrlpunto::create($request->all());

        return redirect()->route('ctrlpuntos.index')
            ->with('success', 'Ctrlpunto created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ctrlpunto = Ctrlpunto::find($id);

        return view('ctrlpunto.show', compact('ctrlpunto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ctrlpunto = Ctrlpunto::find($id);

        return view('ctrlpunto.edit', compact('ctrlpunto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Ctrlpunto $ctrlpunto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ctrlpunto $ctrlpunto)
    {
        request()->validate(Ctrlpunto::$rules);

        $ctrlpunto->update($request->all());

        return redirect()->route('ctrlpuntos.index')
            ->with('success', 'Ctrlpunto updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $ctrlpunto = Ctrlpunto::find($id)->delete();

        return redirect()->route('ctrlpuntos.index')
            ->with('success', 'Ctrlpunto deleted successfully');
    }
}
