<?php

namespace App\Http\Controllers;

use App\Models\Airbnbcompanion;
use Illuminate\Http\Request;

/**
 * Class AirbnbcompanionController
 * @package App\Http\Controllers
 */
class AirbnbcompanionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airbnbcompanions = Airbnbcompanion::paginate();

        return view('airbnbcompanion.index', compact('airbnbcompanions'))
            ->with('i', (request()->input('page', 1) - 1) * $airbnbcompanions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airbnbcompanion = new Airbnbcompanion();
        return view('airbnbcompanion.create', compact('airbnbcompanion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Airbnbcompanion::$rules);

        $airbnbcompanion = Airbnbcompanion::create($request->all());

        return redirect()->route('airbnbcompanions.index')
            ->with('success', 'Airbnbcompanion created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $airbnbcompanion = Airbnbcompanion::find($id);

        return view('airbnbcompanion.show', compact('airbnbcompanion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $airbnbcompanion = Airbnbcompanion::find($id);

        return view('airbnbcompanion.edit', compact('airbnbcompanion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Airbnbcompanion $airbnbcompanion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airbnbcompanion $airbnbcompanion)
    {
        request()->validate(Airbnbcompanion::$rules);

        $airbnbcompanion->update($request->all());

        return redirect()->route('airbnbcompanions.index')
            ->with('success', 'Airbnbcompanion updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $airbnbcompanion = Airbnbcompanion::find($id)->delete();

        return redirect()->route('airbnbcompanions.index')
            ->with('success', 'Airbnbcompanion deleted successfully');
    }
}
