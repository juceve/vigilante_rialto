<?php

namespace App\Http\Controllers;

use App\Models\Designaciondia;
use Illuminate\Http\Request;

/**
 * Class DesignaciondiaController
 * @package App\Http\Controllers
 */
class DesignaciondiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designaciondias = Designaciondia::paginate();

        return view('designaciondia.index', compact('designaciondias'))
            ->with('i', (request()->input('page', 1) - 1) * $designaciondias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designaciondia = new Designaciondia();
        return view('designaciondia.create', compact('designaciondia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Designaciondia::$rules);

        $designaciondia = Designaciondia::create($request->all());

        return redirect()->route('designaciondias.index')
            ->with('success', 'Designaciondia created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $designaciondia = Designaciondia::find($id);

        return view('designaciondia.show', compact('designaciondia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designaciondia = Designaciondia::find($id);

        return view('designaciondia.edit', compact('designaciondia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Designaciondia $designaciondia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designaciondia $designaciondia)
    {
        request()->validate(Designaciondia::$rules);

        $designaciondia->update($request->all());

        return redirect()->route('designaciondias.index')
            ->with('success', 'Designaciondia updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $designaciondia = Designaciondia::find($id)->delete();

        return redirect()->route('designaciondias.index')
            ->with('success', 'Designaciondia deleted successfully');
    }
}
