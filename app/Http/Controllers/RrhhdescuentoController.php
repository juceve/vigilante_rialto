<?php

namespace App\Http\Controllers;

use App\Models\Rrhhdescuento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RrhhdescuentoController extends Controller
{
    public function data($contrato_id, Request $request)
    {
        $mes = $request->mes;
        $anio = $request->anio;
        $descuentos = Rrhhdescuento::where('rrhhcontrato_id', $contrato_id)
            ->when($anio, function ($query) use ($anio) {
                // Siempre filtra por año si existe
                $query->whereYear('fecha', $anio);
            })
            ->when($mes, function ($query) use ($mes) {
                // Solo filtra por mes si viene
                $query->whereMonth('fecha', $mes);
            })
            ->get();
        return response()->json([
            'data' => $descuentos->map(function ($descuento) {
                $button = '<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalDescuento" onclick="editarDesc(' . $descuento->id . ')"><i class="fas fa-edit"></i></button> ';
                $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                if (!$descuento->estado) {
                    $estado = '<span class="badge badge-pill badge-secondary">Anulado</span>';
                }

                return [
                    'id' => $descuento->id,
                    'rrhhcontrato_id' => $descuento->rrhhcontrato_id,
                    'fecha' => $descuento->fecha,
                    'rrhhtipodescuento' => $descuento->rrhhtipodescuento->nombre ?? 'NULL',
                    'empleado_id' => $descuento->empleado_id,
                    'cantidad' => $descuento->cantidad,
                    'monto' => number_format($descuento->monto, 2, '.'),
                    'subtotal' => number_format(($descuento->monto * $descuento->cantidad), 2, '.'),
                    'estado' => $estado,
                    'boton' => $button,

                ];
            }),
        ]);
    }

    public function edit(Request $request)
    {
        $descuento = Rrhhdescuento::find($request->rrhhdescuento_id);
        return response()->json(['success' => true, 'message' => $descuento]);
    }

    public function update(Request $request)
    {
        $descuento = Rrhhdescuento::find($request->rrhhdescuento_id);

        $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required',
            'cantidad' => 'required',
            'rrhhtipodescuento_id' => 'required',
            'estado' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $descuento->fecha = $request->fecha;
            $descuento->monto = $request->monto;
            $descuento->rrhhtipodescuento_id = $request->rrhhtipodescuento_id;
            $descuento->cantidad = $request->cantidad;
            $descuento->estado = $request->estado;
            $descuento->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Descuento editado correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'rrhhcontrato_id' => 'required',
            'empleado_id' => 'required',
            'fecha' => 'required|date',
            'monto' => 'required',
            'cantidad' => 'required',
            'rrhhtipodescuento_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $fecha = explode('-', $request->fecha);

            $descuento = Rrhhdescuento::create([
                "rrhhcontrato_id" => $request->rrhhcontrato_id,
                "empleado_id" => $request->empleado_id,
                "fecha" => $request->fecha,
                "monto" => $request->monto,
                "rrhhtipodescuento_id" => $request->rrhhtipodescuento_id,
                "cantidad" => $request->cantidad,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Descuento registrado correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function pdfDescuentos()
    {
        $parametros = Session::get('param-descuentos');
        $search = $parametros[3];
        $estado = $parametros[0];
        $inicio = $parametros[1];
        $final = $parametros[2];
        $resultados = Rrhhdescuento::with('empleado')
            ->where('fecha', '>=', $inicio)
            ->where('fecha', '<=', $final)

            ->when($search != "", function ($q) use ($search) {
                $q->whereHas('empleado', function ($sub) use ($search) {
                    $sub->where('nombres', 'like', "%$search%")
                        ->orWhere('apellidos', 'like', "%$search%");
                });
            })
            ->when($estado != '', function ($q) use ($estado) {
                $q->where('estado', $estado);
            })
            ->orderBy('empleado_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        // return view('pdfs.pdfmarcaciones', compact('resultados', 'parametros', 'cliente'));
        $i = 1;
        $pdf = Pdf::loadView('pdfs.pdfsanciones', compact('resultados', 'parametros', 'estado', 'i'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream();
    }
}
