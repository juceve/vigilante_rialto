<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Tarea;
use App\Models\Vwtarea;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\FuncCall;

/**
 * Class TareaController
 * @package App\Http\Controllers
 */
class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tareas = Tarea::all();

        return view('admin.tarea.index', compact('tareas'))
            ->with('i', 1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tarea = new Tarea();
        return view('admin.tarea.create', compact('tarea'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Tarea::$rules);

        $tarea = Tarea::create($request->all());

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tarea = Tarea::find($id);

        return view('admin.tarea.show', compact('tarea'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tarea = Tarea::find($id);

        return view('admin.tarea.edit', compact('tarea'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Tarea $tarea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarea $tarea)
    {
        request()->validate(Tarea::$rules);

        $tarea->update($request->all());

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tarea = Tarea::find($id)->delete();

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea deleted successfully');
    }

    public function pdfTareas()
    {
        $parametros = Session::get('param-tarea');
        $cliente = Cliente::find($parametros[0]);
        $resultados = "";
        if ($parametros[1] == "") {
            $resultados = Vwtarea::where(
                [
                    ["fecha", ">=", $parametros[2]],
                    ["fecha", "<=", $parametros[3]],
                    ["cliente_id", $parametros[0]],
                    ['nombreempleado', 'LIKE', '%' . $parametros[4] . '%']
                ]
            )
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $resultados = Vwtarea::where([
                ["fecha", ">=", $parametros[2]],
                ["fecha", "<=", $parametros[3]],
                ["cliente_id", $parametros[0]],
                ['nombreempleado', 'LIKE', '%' . $parametros[4] . '%'],
                ["estado", $parametros[1]],
            ])
                ->orderBy('id', 'DESC')
                ->get();
        }
        // return view('pdfs.pdfmarcaciones', compact('resultados', 'parametros', 'cliente'));
        $i = 1;
        $pdf = Pdf::loadView('pdfs.pdftareas', compact('resultados', 'parametros', 'cliente', 'i'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
