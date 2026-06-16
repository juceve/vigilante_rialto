<?php

namespace App\Http\Controllers;

use App\Models\Imgnovedade;
use Illuminate\Http\Request;

/**
 * Class ImgnovedadeController
 * @package App\Http\Controllers
 */
class ImgnovedadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imgnovedades = Imgnovedade::paginate();

        return view('imgnovedade.index', compact('imgnovedades'))
            ->with('i', (request()->input('page', 1) - 1) * $imgnovedades->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $imgnovedade = new Imgnovedade();
        return view('imgnovedade.create', compact('imgnovedade'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Imgnovedade::$rules);

        $imgnovedade = Imgnovedade::create($request->all());

        return redirect()->route('imgnovedades.index')
            ->with('success', 'Imgnovedade created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $imgnovedade = Imgnovedade::find($id);

        return view('imgnovedade.show', compact('imgnovedade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $imgnovedade = Imgnovedade::find($id);

        return view('imgnovedade.edit', compact('imgnovedade'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Imgnovedade $imgnovedade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Imgnovedade $imgnovedade)
    {
        request()->validate(Imgnovedade::$rules);

        $imgnovedade->update($request->all());

        return redirect()->route('imgnovedades.index')
            ->with('success', 'Imgnovedade updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $imgnovedade = Imgnovedade::find($id)->delete();

        return redirect()->route('imgnovedades.index')
            ->with('success', 'Imgnovedade deleted successfully');
    }
}
