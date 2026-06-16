<?php

namespace App\Http\Controllers;

use App\Models\Imgregistro;
use Illuminate\Http\Request;

/**
 * Class ImgregistroController
 * @package App\Http\Controllers
 */
class ImgregistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imgregistros = Imgregistro::paginate();

        return view('imgregistro.index', compact('imgregistros'))
            ->with('i', (request()->input('page', 1) - 1) * $imgregistros->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $imgregistro = new Imgregistro();
        return view('imgregistro.create', compact('imgregistro'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Imgregistro::$rules);

        $imgregistro = Imgregistro::create($request->all());

        return redirect()->route('imgregistros.index')
            ->with('success', 'Imgregistro created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $imgregistro = Imgregistro::find($id);

        return view('imgregistro.show', compact('imgregistro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $imgregistro = Imgregistro::find($id);

        return view('imgregistro.edit', compact('imgregistro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Imgregistro $imgregistro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Imgregistro $imgregistro)
    {
        request()->validate(Imgregistro::$rules);

        $imgregistro->update($request->all());

        return redirect()->route('imgregistros.index')
            ->with('success', 'Imgregistro updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $imgregistro = Imgregistro::find($id)->delete();

        return redirect()->route('imgregistros.index')
            ->with('success', 'Imgregistro deleted successfully');
    }
}
