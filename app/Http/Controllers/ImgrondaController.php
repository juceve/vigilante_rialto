<?php

namespace App\Http\Controllers;

use App\Models\Imgronda;
use Illuminate\Http\Request;

/**
 * Class ImgrondaController
 * @package App\Http\Controllers
 */
class ImgrondaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imgrondas = Imgronda::paginate();

        return view('imgronda.index', compact('imgrondas'))
            ->with('i', (request()->input('page', 1) - 1) * $imgrondas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $imgronda = new Imgronda();
        return view('imgronda.create', compact('imgronda'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Imgronda::$rules);

        $imgronda = Imgronda::create($request->all());

        return redirect()->route('imgrondas.index')
            ->with('success', 'Imgronda created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $imgronda = Imgronda::find($id);

        return view('imgronda.show', compact('imgronda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $imgronda = Imgronda::find($id);

        return view('imgronda.edit', compact('imgronda'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Imgronda $imgronda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Imgronda $imgronda)
    {
        request()->validate(Imgronda::$rules);

        $imgronda->update($request->all());

        return redirect()->route('imgrondas.index')
            ->with('success', 'Imgronda updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $imgronda = Imgronda::find($id)->delete();

        return redirect()->route('imgrondas.index')
            ->with('success', 'Imgronda deleted successfully');
    }
}
