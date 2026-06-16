<?php

namespace App\Http\Controllers;

use App\Models\Airbnblink;
use Illuminate\Http\Request;

/**
 * Class AirbnblinkController
 * @package App\Http\Controllers
 */
class AirbnblinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airbnblinks = Airbnblink::paginate();

        return view('airbnblink.index', compact('airbnblinks'))
            ->with('i', (request()->input('page', 1) - 1) * $airbnblinks->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airbnblink = new Airbnblink();
        return view('airbnblink.create', compact('airbnblink'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Airbnblink::$rules);

        $airbnblink = Airbnblink::create($request->all());

        return redirect()->route('airbnblinks.index')
            ->with('success', 'Airbnblink created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $airbnblink = Airbnblink::find($id);

        return view('airbnblink.show', compact('airbnblink'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $airbnblink = Airbnblink::find($id);

        return view('airbnblink.edit', compact('airbnblink'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Airbnblink $airbnblink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airbnblink $airbnblink)
    {
        request()->validate(Airbnblink::$rules);

        $airbnblink->update($request->all());

        return redirect()->route('airbnblinks.index')
            ->with('success', 'Airbnblink updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $airbnblink = Airbnblink::find($id)->delete();

        return redirect()->route('airbnblinks.index')
            ->with('success', 'Airbnblink deleted successfully');
    }
}
