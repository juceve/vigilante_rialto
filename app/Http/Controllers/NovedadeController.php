<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Novedade;
use App\Models\Vwnovedade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class NovedadeController
 * @package App\Http\Controllers
 */
class NovedadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $novedades = Novedade::paginate();

        return view('novedade.index', compact('novedades'))
            ->with('i', (request()->input('page', 1) - 1) * $novedades->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $novedade = new Novedade();
        return view('novedade.create', compact('novedade'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Novedade::$rules);

        $novedade = Novedade::create($request->all());

        return redirect()->route('novedades.index')
            ->with('success', 'Novedade created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $novedade = Novedade::find($id);

        return view('novedade.show', compact('novedade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $novedade = Novedade::find($id);

        return view('novedade.edit', compact('novedade'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Novedade $novedade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Novedade $novedade)
    {
        request()->validate(Novedade::$rules);

        $novedade->update($request->all());

        return redirect()->route('novedades.index')
            ->with('success', 'Novedade updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $novedade = Novedade::find($id)->delete();

        return redirect()->route('novedades.index')
            ->with('success', 'Novedade deleted successfully');
    }

    public function pdfNovedades()
    {
        $parametros = Session::get('param-novedades');

        // Determinar si es supervisores, todos o cliente normal
        if ($parametros[0] == 'supervisores') {
            $cliente = (object)['nombre' => 'SUPERVISORES'];

            // Consulta para supervisores
            $resultados = Vwnovedade::whereBetween('fecha', [$parametros[1], $parametros[2]])
                ->where(function ($query) {
                    $query->whereNull('cliente_id')
                        ->orWhere('cliente_id', 0);
                });

            if ($parametros[3] != "") {
                $resultados = $resultados->where(function ($query) {
                    $query->where('empleado', 'LIKE', '%' . $parametros[3] . '%')
                        ->orWhere('contenido', 'LIKE', '%' . $parametros[3] . '%');
                });
            }

            if ($parametros[4] != "") {
                $resultados = $resultados->where('empleado_id', $parametros[4]);
            }

            $resultados = $resultados->orderBy('fecha', 'DESC')
                ->orderBy('id', 'DESC')
                ->get();

            // Procesar cliente del contenido para supervisores
            $resultados->transform(function ($item) {
                if (strpos($item->contenido, ':') !== false) {
                    $parts = explode(':', $item->contenido, 2);
                    $item->cliente_from_content = trim($parts[0]);
                    $item->contenido = trim($parts[1]); // Limpiar contenido
                } else {
                    $item->cliente_from_content = null;
                }
                return $item;
            });
        } elseif ($parametros[0] == 'todos') {
            $cliente = (object)['nombre' => 'TODOS LOS REGISTROS'];

            // Consulta para todos los registros
            $resultados = Vwnovedade::whereBetween('fecha', [$parametros[1], $parametros[2]]);

            if ($parametros[3] != "") {
                $resultados = $resultados->where(function ($query) {
                    $query->where('empleado', 'LIKE', '%' . $parametros[3] . '%')
                        ->orWhere('cliente', 'LIKE', '%' . $parametros[3] . '%')
                        ->orWhere('contenido', 'LIKE', '%' . $parametros[3] . '%');
                });
            }

            if ($parametros[4] != "") {
                $resultados = $resultados->where('empleado_id', $parametros[4]);
            }

            $resultados = $resultados->orderBy('fecha', 'DESC')
                ->orderBy('id', 'DESC')
                ->get();

            // Procesar contenido solo para supervisores
            $resultados->transform(function ($item) {
                if (is_null($item->cliente_id) || $item->cliente_id == 0) {
                    if (strpos($item->contenido, ':') !== false) {
                        $parts = explode(':', $item->contenido, 2);
                        $item->cliente_from_content = trim($parts[0]);
                        $item->contenido = trim($parts[1]); // Limpiar contenido
                    } else {
                        $item->cliente_from_content = null;
                    }
                } else {
                    $item->cliente_from_content = null;
                }
                return $item;
            });
        } else {
            $cliente = Cliente::find($parametros[0]);

            // Consulta para clientes normales
            $resultados = Vwnovedade::whereBetween('fecha', [$parametros[1], $parametros[2]])
                ->where('cliente_id', (int)$parametros[0]);

            if ($parametros[3] != "") {
                $resultados = $resultados->where(function ($query) {
                    $query->where('empleado', 'LIKE', '%' . $parametros[3] . '%')
                        ->orWhere('turno', 'LIKE', '%' . $parametros[3] . '%')
                        ->orWhere('contenido', 'LIKE', '%' . $parametros[3] . '%');
                });
            }

            if ($parametros[4] != "") {
                $resultados = $resultados->where('empleado_id', $parametros[4]);
            }

            $resultados = $resultados->orderBy('fecha', 'DESC')
                ->orderBy('id', 'DESC')
                ->get();
        }

        $i = 1;

        $pdf = Pdf::loadView('pdfs.pdfnovedades', compact('resultados', 'parametros', 'cliente', 'i'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
