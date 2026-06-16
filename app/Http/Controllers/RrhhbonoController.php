<?php

namespace App\Http\Controllers;

use App\Models\Rrhhbono;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class RrhhbonoController
 * @package App\Http\Controllers
 */
class RrhhbonoController extends Controller
{
    public function data($contrato_id,Request $request)
    {
        $mes = $request->mes;
        $anio = $request->anio;
        $bonos = Rrhhbono::where('rrhhcontrato_id', $contrato_id)
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
            'data' => $bonos->map(function ($bono) {
                $button = '<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalBonos" onclick="editar2(' . $bono->id . ')"><i class="fas fa-edit"></i></button> ';
                $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                $pagado = '<span class="badge badge-pill badge-success">SI</span>';
                if (!$bono->estado) {
                    $estado = '<span class="badge badge-pill badge-secondary">Anulado</span>';
                }
                if (!$bono->pagado) {
                    $pagado = '<span class="badge badge-pill badge-secondary">NO</span>';
                }

                return [
                    'id' => $bono->id,
                    'rrhhcontrato_id' => $bono->rrhhcontrato_id,
                    'fecha' => $bono->fecha,
                    'rrhhtipobono' => $bono->rrhhtipobono->nombre,
                    'empleado_id' => $bono->empleado_id,
                    'cantidad' => $bono->cantidad,
                    'monto' => number_format($bono->monto, 2, '.'),
                    'subtotal' => number_format(($bono->monto * $bono->cantidad), 2, '.'),
                    'pagado' => $pagado,
                    'estado' => $estado,
                    'boton' => $button,

                ];
            }),
        ]);
    }

    public function edit(Request $request)
    {
        $bono = Rrhhbono::find($request->rrhhbono_id);
        return response()->json(['success' => true, 'message' => $bono]);
    }

    public function update(Request $request)
    {
        $bono = Rrhhbono::find($request->rrhhbono_id);

        $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required',
            'cantidad' => 'required',
            'rrhhtipobono_id' => 'required',
            'estado' => 'required',
            'pagado' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $bono->fecha = $request->fecha;
            $bono->monto = $request->monto;
            $bono->rrhhtipobono_id = $request->rrhhtipobono_id;
            $bono->cantidad = $request->cantidad;
            $bono->estado = $request->estado;
            $bono->pagado = $request->pagado;
            $bono->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Bono editado correctamente.']);
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
            'rrhhtipobono_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $fecha = explode('-', $request->fecha);

            $bono = Rrhhbono::create([
                "rrhhcontrato_id" => $request->rrhhcontrato_id,
                "empleado_id" => $request->empleado_id,
                "fecha" => $request->fecha,
                "monto" => $request->monto,
                "rrhhtipobono_id" => $request->rrhhtipobono_id,
                "cantidad" => $request->cantidad,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Bono registrado correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
