<?php

namespace App\Http\Controllers;

use App\Models\ChklIncumplimiento;
use Illuminate\Http\Request;

/**
 * Class ChklIncumplimientoController
 * @package App\Http\Controllers
 */
class ChklIncumplimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chklIncumplimientos = ChklIncumplimiento::paginate();

        return view('chkl-incumplimiento.index', compact('chklIncumplimientos'))
            ->with('i', (request()->input('page', 1) - 1) * $chklIncumplimientos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chklIncumplimiento = new ChklIncumplimiento();
        return view('chkl-incumplimiento.create', compact('chklIncumplimiento'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ChklIncumplimiento::$rules);

        $chklIncumplimiento = ChklIncumplimiento::create($request->all());

        return redirect()->route('chkl-incumplimientos.index')
            ->with('success', 'ChklIncumplimiento created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chklIncumplimiento = ChklIncumplimiento::find($id);

        return view('chkl-incumplimiento.show', compact('chklIncumplimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chklIncumplimiento = ChklIncumplimiento::find($id);

        return view('chkl-incumplimiento.edit', compact('chklIncumplimiento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ChklIncumplimiento $chklIncumplimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChklIncumplimiento $chklIncumplimiento)
    {
        request()->validate(ChklIncumplimiento::$rules);

        $chklIncumplimiento->update($request->all());

        return redirect()->route('chkl-incumplimientos.index')
            ->with('success', 'ChklIncumplimiento updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $chklIncumplimiento = ChklIncumplimiento::find($id)->delete();

        return redirect()->route('chkl-incumplimientos.index')
            ->with('success', 'ChklIncumplimiento deleted successfully');
    }
}
