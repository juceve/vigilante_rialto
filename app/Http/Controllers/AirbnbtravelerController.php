<?php

namespace App\Http\Controllers;

use App\Models\Airbnbtraveler;
use Illuminate\Http\Request;

/**
 * Class AirbnbtravelerController
 * @package App\Http\Controllers
 */
class AirbnbtravelerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airbnbtravelers = Airbnbtraveler::paginate();

        return view('airbnbtraveler.index', compact('airbnbtravelers'))
            ->with('i', (request()->input('page', 1) - 1) * $airbnbtravelers->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airbnbtraveler = new Airbnbtraveler();
        return view('airbnbtraveler.create', compact('airbnbtraveler'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Airbnbtraveler::$rules);

        $airbnbtraveler = Airbnbtraveler::create($request->all());

        return redirect()->route('airbnbtravelers.index')
            ->with('success', 'Airbnbtraveler created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $airbnbtraveler = Airbnbtraveler::find($id);

        return view('airbnbtraveler.show', compact('airbnbtraveler'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $airbnbtraveler = Airbnbtraveler::find($id);

        return view('airbnbtraveler.edit', compact('airbnbtraveler'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Airbnbtraveler $airbnbtraveler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airbnbtraveler $airbnbtraveler)
    {
        request()->validate(Airbnbtraveler::$rules);

        $airbnbtraveler->update($request->all());

        return redirect()->route('airbnbtravelers.index')
            ->with('success', 'Airbnbtraveler updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $airbnbtraveler = Airbnbtraveler::find($id)->delete();

        return redirect()->route('airbnbtravelers.index')
            ->with('success', 'Airbnbtraveler deleted successfully');
    }
}
