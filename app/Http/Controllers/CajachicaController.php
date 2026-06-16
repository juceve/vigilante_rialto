<?php

namespace App\Http\Controllers;

use App\Models\Cajachica;
use Illuminate\Http\Request;

/**
 * Class CajachicaController
 * @package App\Http\Controllers
 */
class CajachicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cajachicas = Cajachica::paginate();

        return view('cajachica.index', compact('cajachicas'))
            ->with('i', (request()->input('page', 1) - 1) * $cajachicas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cajachica = new Cajachica();
        return view('cajachica.create', compact('cajachica'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Cajachica::$rules);

        $cajachica = Cajachica::create($request->all());

        return redirect()->route('cajachicas.index')
            ->with('success', 'Cajachica created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cajachica = Cajachica::find($id);

        return view('cajachica.show', compact('cajachica'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cajachica = Cajachica::find($id);

        return view('cajachica.edit', compact('cajachica'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Cajachica $cajachica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cajachica $cajachica)
    {
        request()->validate(Cajachica::$rules);

        $cajachica->update($request->all());

        return redirect()->route('cajachicas.index')
            ->with('success', 'Cajachica updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $cajachica = Cajachica::find($id)->delete();

        return redirect()->route('cajachicas.index')
            ->with('success', 'Cajachica deleted successfully');
    }
}
