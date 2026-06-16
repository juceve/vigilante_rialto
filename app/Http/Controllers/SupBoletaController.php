<?php

namespace App\Http\Controllers;

use App\Models\SupBoleta;
use Illuminate\Http\Request;

/**
 * Class SupBoletaController
 * @package App\Http\Controllers
 */
class SupBoletaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supBoletas = SupBoleta::paginate();

        return view('sup-boleta.index', compact('supBoletas'))
            ->with('i', (request()->input('page', 1) - 1) * $supBoletas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supBoleta = new SupBoleta();
        return view('sup-boleta.create', compact('supBoleta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(SupBoleta::$rules);

        $supBoleta = SupBoleta::create($request->all());

        return redirect()->route('sup-boletas.index')
            ->with('success', 'SupBoleta created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supBoleta = SupBoleta::find($id);

        return view('sup-boleta.show', compact('supBoleta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supBoleta = SupBoleta::find($id);

        return view('sup-boleta.edit', compact('supBoleta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  SupBoleta $supBoleta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupBoleta $supBoleta)
    {
        request()->validate(SupBoleta::$rules);

        $supBoleta->update($request->all());

        return redirect()->route('sup-boletas.index')
            ->with('success', 'SupBoleta updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $supBoleta = SupBoleta::find($id)->delete();

        return redirect()->route('sup-boletas.index')
            ->with('success', 'SupBoleta deleted successfully');
    }
}
