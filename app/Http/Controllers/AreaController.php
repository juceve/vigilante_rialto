<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class AreaController
 * @package App\Http\Controllers
 */
class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:areas.index')->only('index');
        $this->middleware('can:areas.create')->only('create', 'store');
        $this->middleware('can:areas.edit')->only('edit', 'update');
        $this->middleware('can:areas.destroy')->only('destroy');
    }

    public function index()
    {
        $areas = Area::all();

        return view('admin.area.index', compact('areas'))
            ->with('i',  1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $area = new Area();
        $templateOptions = Area::getTemplateOptions();
        return view('admin.area.create', compact('area', 'templateOptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Area::$rules);

        $area = Area::create($request->all());

        return redirect()->route('areas.index')
            ->with('success', 'Area creada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $area = Area::find($id);

        return view('admin.area.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $area = Area::find($id);
        $templateOptions = Area::getTemplateOptions();
        return view('admin.area.edit', compact('area', 'templateOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Area $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        request()->validate(Area::$rules);

        DB::beginTransaction();
        try {
            $area->update($request->all());

            $empleados = $area->empleados;
            foreach ($empleados as $empleado) {
                $empleado->user->template = $area->template;
                $empleado->user->save();
            }
            DB::commit();
            return redirect()->route('areas.index')
                ->with('success', 'Area editada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('areas.edit', $area->id)
                ->with('error', 'Error al editar el area: ' . $th->getMessage());
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $area = Area::find($id)->delete();

        return redirect()->route('areas.index')
            ->with('success', 'Area eliminada correctamente');
    }
}
