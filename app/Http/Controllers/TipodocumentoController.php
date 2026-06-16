<?php

namespace App\Http\Controllers;

use App\Models\Tipodocumento;
use Illuminate\Http\Request;

/**
 * Class TipodocumentoController
 * @package App\Http\Controllers
 */
class TipodocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipodocumentos = Tipodocumento::paginate();

        return view('tipodocumento.index', compact('tipodocumentos'))
            ->with('i', (request()->input('page', 1) - 1) * $tipodocumentos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipodocumento = new Tipodocumento();
        return view('tipodocumento.create', compact('tipodocumento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Tipodocumento::$rules);

        $tipodocumento = Tipodocumento::create($request->all());

        return redirect()->route('tipodocumentos.index')
            ->with('success', 'Tipodocumento created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipodocumento = Tipodocumento::find($id);

        return view('tipodocumento.show', compact('tipodocumento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipodocumento = Tipodocumento::find($id);

        return view('tipodocumento.edit', compact('tipodocumento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tipodocumento $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipodocumento $tipodocumento)
    {
        request()->validate(Tipodocumento::$rules);

        $tipodocumento->update($request->all());

        return redirect()->route('tipodocumentos.index')
            ->with('success', 'Tipodocumento updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tipodocumento = Tipodocumento::find($id)->delete();

        return redirect()->route('tipodocumentos.index')
            ->with('success', 'Tipodocumento deleted successfully');
    }
}
