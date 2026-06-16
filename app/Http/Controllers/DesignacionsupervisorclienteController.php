<?php

namespace App\Http\Controllers;

use App\Models\Designacionsupervisorcliente;
use Illuminate\Http\Request;

/**
 * Class DesignacionsupervisorclienteController
 * @package App\Http\Controllers
 */
class DesignacionsupervisorclienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designacionsupervisorclientes = Designacionsupervisorcliente::paginate();

        return view('designacionsupervisorcliente.index', compact('designacionsupervisorclientes'))
            ->with('i', (request()->input('page', 1) - 1) * $designacionsupervisorclientes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designacionsupervisorcliente = new Designacionsupervisorcliente();
        return view('designacionsupervisorcliente.create', compact('designacionsupervisorcliente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Designacionsupervisorcliente::$rules);

        $designacionsupervisorcliente = Designacionsupervisorcliente::create($request->all());

        return redirect()->route('designacionsupervisorclientes.index')
            ->with('success', 'Designacionsupervisorcliente created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $designacionsupervisorcliente = Designacionsupervisorcliente::find($id);

        return view('designacionsupervisorcliente.show', compact('designacionsupervisorcliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designacionsupervisorcliente = Designacionsupervisorcliente::find($id);

        return view('designacionsupervisorcliente.edit', compact('designacionsupervisorcliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Designacionsupervisorcliente $designacionsupervisorcliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designacionsupervisorcliente $designacionsupervisorcliente)
    {
        request()->validate(Designacionsupervisorcliente::$rules);

        $designacionsupervisorcliente->update($request->all());

        return redirect()->route('designacionsupervisorclientes.index')
            ->with('success', 'Designacionsupervisorcliente updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $designacionsupervisorcliente = Designacionsupervisorcliente::find($id)->delete();

        return redirect()->route('designacionsupervisorclientes.index')
            ->with('success', 'Designacionsupervisorcliente deleted successfully');
    }
}
