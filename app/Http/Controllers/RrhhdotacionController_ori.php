<?php

namespace App\Http\Controllers;

use App\Models\Rrhhdotacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class RrhhdotacionController
 * @package App\Http\Controllers
 */
class RrhhdotacionController extends Controller
{
    public function data($contrato_id)
    {
        $dotacions = Rrhhdotacion::where('rrhhcontrato_id', $contrato_id)->get();

        return response()->json([
            'data' => $dotacions->map(function ($dotacion) {
                $button = '<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditDotacion" onclick="editar3(' . $dotacion->id . ')"><i class="fas fa-edit"></i></button> ';

                return [
                    'id' => $dotacion->id,
                    'rrhhcontrato_id' => $dotacion->rrhhcontrato_id,
                    'fecha' => $dotacion->fecha,
                    'rrhhestadodotacion' => $dotacion->rrhhestadodotacion->nombre,
                    'empleado_id' => $dotacion->empleado_id,
                    'cantidad' => $dotacion->cantidad,
                    'detalle' => $dotacion->detalle,
                    'boton' => $button,
                ];
            }),
        ]);
    }

    public function edit(Request $request)
    {
        $dotacion = Rrhhdotacion::find($request->rrhhdotacion_id);
        return response()->json(['success' => true, 'message' => $dotacion]);
    }

    public function update(Request $request)
    {
        $dotacion = Rrhhdotacion::find($request->rrhhdotacion_id);

        $request->validate([
            'fecha' => 'required|date',
            'detalle' => 'required',
            'cantidad' => 'required',
            'rrhhestadodotacion_id' => 'required',            
        ]);

        DB::beginTransaction();
        try {

            $dotacion->fecha = $request->fecha;
            $dotacion->detalle = $request->detalle;
            $dotacion->rrhhestadodotacion_id = $request->rrhhestadodotacion_id;
            $dotacion->cantidad = $request->cantidad;            
            $dotacion->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Dotación editado correctamente.']);
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
            'detalles' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $fecha = explode('-', $request->fecha);
            $detalles = json_decode($request->input('detalles'), true);
            foreach ($detalles as $detalle) {

                $dotacion = Rrhhdotacion::create([
                    "rrhhcontrato_id" => $request->rrhhcontrato_id,
                    "empleado_id" => $request->empleado_id,
                    "fecha" => $request->fecha,
                    "detalle" => $detalle['detalle'],
                    "rrhhestadodotacion_id" => $detalle['estado'],
                    "cantidad" => $detalle['cantidad'],
                ]);
            }


            DB::commit();
            return response()->json(['success' => true, 'message' => 'Bono registrado correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
