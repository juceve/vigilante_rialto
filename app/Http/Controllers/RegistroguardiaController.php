<?php

namespace App\Http\Controllers;

use App\Models\Registroguardia;
use Illuminate\Http\Request;

/**
 * Class RegistroguardiaController
 * @package App\Http\Controllers
 */
class RegistroguardiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registroguardias = Registroguardia::paginate();

        return view('registroguardia.index', compact('registroguardias'))
            ->with('i', (request()->input('page', 1) - 1) * $registroguardias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $registroguardia = new Registroguardia();
        return view('registroguardia.create', compact('registroguardia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Registroguardia::$rules);

        $registroguardia = Registroguardia::create($request->all());

        return redirect()->route('registroguardias.index')
            ->with('success', 'Registroguardia created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registroguardia = Registroguardia::find($id);

        return view('registroguardia.show', compact('registroguardia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registroguardia = Registroguardia::find($id);

        return view('registroguardia.edit', compact('registroguardia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Registroguardia $registroguardia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registroguardia $registroguardia)
    {
        request()->validate(Registroguardia::$rules);

        $registroguardia->update($request->all());

        return redirect()->route('registroguardias.index')
            ->with('success', 'Registroguardia updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $registroguardia = Registroguardia::find($id)->delete();

        return redirect()->route('registroguardias.index')
            ->with('success', 'Registroguardia deleted successfully');
    }
}
