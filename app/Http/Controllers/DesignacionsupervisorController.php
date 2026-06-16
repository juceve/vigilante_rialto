<?php

namespace App\Http\Controllers;

use App\Models\Designacionsupervisor;
use Illuminate\Http\Request;

/**
 * Class DesignacionsupervisorController
 * @package App\Http\Controllers
 */
class DesignacionsupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designacionsupervisors = Designacionsupervisor::paginate();

        return view('designacionsupervisor.index', compact('designacionsupervisors'))
            ->with('i', (request()->input('page', 1) - 1) * $designacionsupervisors->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designacionsupervisor = new Designacionsupervisor();
        return view('designacionsupervisor.create', compact('designacionsupervisor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Designacionsupervisor::$rules);

        $designacionsupervisor = Designacionsupervisor::create($request->all());

        return redirect()->route('designacionsupervisors.index')
            ->with('success', 'Designacionsupervisor created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $designacionsupervisor = Designacionsupervisor::find($id);

        return view('designacionsupervisor.show', compact('designacionsupervisor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designacionsupervisor = Designacionsupervisor::find($id);

        return view('designacionsupervisor.edit', compact('designacionsupervisor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Designacionsupervisor $designacionsupervisor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designacionsupervisor $designacionsupervisor)
    {
        request()->validate(Designacionsupervisor::$rules);

        $designacionsupervisor->update($request->all());

        return redirect()->route('designacionsupervisors.index')
            ->with('success', 'Designacionsupervisor updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $designacionsupervisor = Designacionsupervisor::find($id)->delete();

        return redirect()->route('designacionsupervisors.index')
            ->with('success', 'Designacionsupervisor deleted successfully');
    }
}
