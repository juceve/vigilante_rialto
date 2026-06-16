<?php

namespace App\Http\Controllers;

use App\Models\Rrhhadelanto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class RrhhadelantoController
 * @package App\Http\Controllers
 */
class RrhhadelantoController extends Controller
{
    public function data($contrato_id, Request $request)
    {
        $mes = $request->mes;
        $anio = $request->anio;
        $adelantos = Rrhhadelanto::where('rrhhcontrato_id', $contrato_id)
            ->when($anio, function ($query) use ($anio) {
                // Siempre filtra por año si existe
                $query->whereYear('fecha', $anio);
            })
            ->when($mes, function ($query) use ($mes) {
                // Solo filtra por mes si viene
                $query->whereMonth('fecha', $mes);
            })
            ->where('activo', true)
            ->orderBy('estado', 'desc')
            ->get();

        return response()->json([
            'data' => $adelantos->map(function ($adelanto) {
                $button = '<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalAdelantos" onclick="editar1(' . $adelanto->id . ')"><i class="fas fa-edit"></i></button> ';

                if (!is_null($adelanto->documento_adjunto)) {
                    $button .= '<a href="' . Storage::url($adelanto->documento_adjunto) . '"
                                                    class="btn btn-sm btn-success" download title="Descargar" target="_blank">
                                                    <i class="fas fa-cloud-download-alt"></i>
                                                </a>';
                } else {
                    $button .= '<button class="btn btn-sm btn-success" disabled><i class="fas fa-cloud-download-alt"></i>
                                                </a>';
                }

                $estado = "N/A";

                switch ($adelanto->estado) {
                    case 'SOLICITADO':
                        $estado = '<span class="badge bg-warning text-dark">Solicitado</span>';
                        break;
                    case 'APROBADO':
                        $estado = '<span class="badge bg-success text-white">Aprobado</span>';
                        break;
                    case 'RECHAZADO':
                        $estado = '<span class="badge bg-danger text-white">Rechazado</span>';
                        break;
                }

                return [
                    'id' => $adelanto->id,
                    'rrhhcontrato_id' => $adelanto->rrhhcontrato_id,
                    'empleado_id' => $adelanto->empleado_id,
                    'fecha' => $adelanto->fecha,
                    'mes' => $adelanto->mes,
                    'monto' => number_format($adelanto->monto, 2, '.'),
                    'motivo' => $adelanto->motivo,
                    'estado' => $estado,
                    'boton' => $button,
                    'documento_adjunto' => $adelanto->documento_adjunto,
                ];
            }),
        ]);
    }

    public function edit(Request $request)
    {
        $adelanto = Rrhhadelanto::find($request->rrhhadelanto_id);
        return response()->json(['success' => true, 'message' => $adelanto]);
    }

    public function update(Request $request)
    {
        $adelanto = Rrhhadelanto::find($request->rrhhadelanto_id);
        $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required',
            'estado' => 'required',
            'motivo' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $fecha = explode('-', $request->fecha);

            $adelanto->fecha = $request->fecha;
            $adelanto->monto = $request->monto;
            $adelanto->motivo = $request->motivo;
            $adelanto->estado = $request->estado;
            $adelanto->mes = $fecha[1];
            $adelanto->save();

            if ($request->hasFile('documento_adjunto')) {
                $archivo = $request->file('documento_adjunto');

                if (!is_null($adelanto->documento_adjunto)) {
                    Storage::disk('public')->delete($adelanto->documento_adjunto);
                }

                $rutaDestino = "storage/documentos/adelantos/";
                if (!File::exists($rutaDestino)) {
                    File::makeDirectory($rutaDestino, 0755, true);
                }
                $nombre = $adelanto->rrhhcontrato_id . '_' . Auth::user()->id . "_" . date('Ymd_His');
                // Obtiene la extensión original
                $extension = $archivo->getClientOriginalExtension();
                $archivo->move($rutaDestino, $nombre . '.' . $extension);
                $adelanto->documento_adjunto = "documentos/adelantos/" . $nombre . '.' . $extension;
                $adelanto->save();
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Adelanto actualizado correctamente.']);
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
            'motivo' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $fecha = explode('-', $request->fecha);

            $adelanto = Rrhhadelanto::create([
                "rrhhcontrato_id" => $request->rrhhcontrato_id,
                "empleado_id" => $request->empleado_id,
                "fecha" => $request->fecha,
                "monto" => $request->monto,
                "motivo" => $request->motivo,
                "mes" => $fecha[1],

            ]);
            if ($request->hasFile('documento_adjunto')) {
                $archivo = $request->file('documento_adjunto');
                $rutaDestino = "storage/documentos/adelantos/";

                if (!File::exists($rutaDestino)) {
                    File::makeDirectory($rutaDestino, 0755, true);
                }
                $nombre = $request->rrhhcontrato_id . '_' . Auth::user()->id . "_" . date('Ymd_His');
                // Obtiene la extensión original
                $extension = $archivo->getClientOriginalExtension();
                $archivo->move($rutaDestino, $nombre . '.' . $extension);
                $adelanto->documento_adjunto = "documentos/adelantos/" . $nombre . '.' . $extension;
                $adelanto->save();
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Adelanto registrado correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
