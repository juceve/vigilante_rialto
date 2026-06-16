<?php

namespace App\Http\Controllers;

use App\Models\Rrhhcontrato;
use App\Models\Rrhhdotacion;
use App\Models\Rrhhdetalledotacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Class RrhhdotacionController
 * @package App\Http\Controllers
 */
class RrhhdotacionController extends Controller
{
    public function acta($id, $contrato_id)
    {
        $dotacion = Rrhhdotacion::find($id);
        $designacione = traeDesignacionActiva($dotacion->empleado_id);
        $contrato = Rrhhcontrato::find($contrato_id);
        if ($dotacion) {
            $pdf = Pdf::loadView('pdfs.acta-dotacion-empleado', compact('dotacion','designacione', 'contrato'))
                ->setPaper('letter', 'portrait');

            return $pdf->stream('Acta_Dotacion_Empleados_' . date('Ymd_His') . '.pdf');
        } else {
            return redirect()->back()->with('error', 'Dotación no encontrada');
        }
    }


    public function data($contrato_id)
    {
        try {
            // Cargar únicamente lo necesario
            $dotaciones = Rrhhdotacion::where('rrhhcontrato_id', $contrato_id)
                ->select(['id', 'fecha', 'responsable_entrega'])
                ->orderBy('id', 'desc')
                ->get();

            // Retornar formato simple esperado por DataTables, incluye botones de acciones
            return response()->json([
                'data' => $dotaciones->map(function ($d) use ($contrato_id) {

                    $editBtn = ' <button class="btn btn-sm btn-warning" title="Editar" onclick="editar3(' . $d->id . ')"><i class="fas fa-edit"></i></button>';
                    // Asegurar que $contrato_id se pase como integer o como null literal en JS
                    $contratoParam = is_null($contrato_id) ? 'null' : (int) $contrato_id;
                    $actaBtn = ' <button class="btn btn-sm btn-danger" title="Generar Acta PDF" onclick="renderizarPDF(' . (int)$d->id . ', ' . $contratoParam . ')"><i class="fas fa-file-pdf"></i></button>';

                    return [
                        'id' => $d->id,
                        'fecha' => $d->fecha,
                        'responsable_entrega' => $d->responsable_entrega,
                        // 'detalles' => $d->detalles->toArray(),
                        'actions' => $editBtn.$actaBtn,
                    ];
                }),
            ]);
        } catch (\Throwable $th) {
            Log::error('Error al obtener dotaciones para DataTable', ['contrato_id' => $contrato_id, 'exception' => $th]);
            return response()->json(['data' => []]);
        }
    }

    public function edit(Request $request)
    {
        // Validar entrada
        $validator = \Validator::make($request->all(), [
            'rrhhdotacion_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetros inválidos.',
                'errors' => $validator->errors()->all(),
            ], 200);
        }

        $id = $request->input('rrhhdotacion_id');

        try {
            // Eager-load seguro: detalles (rrhhdetalledotacions) y su estado (rrhhestadodotacion)
            $dotacion = Rrhhdotacion::with([
                'rrhhdetalledotacions.rrhhestadodotacion',
                'empleado', // opcional, útil en la vista
                'rrhhcontrato' // opcional
            ])->find($id);

            if (!$dotacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dotación no encontrada.',
                ], 200);
            }

            // Construir payload consistente con lo que espera la vista (clave 'detalles')
            $payload = [
                'id' => $dotacion->id,
                'fecha' => $dotacion->fecha,
                'responsable_entrega' => $dotacion->responsable_entrega,
                'estado' => $dotacion->estado ?? null,
                'empleado' => $dotacion->empleado ? $dotacion->empleado->toArray() : null,
                'rrhhcontrato' => $dotacion->rrhhcontrato ? $dotacion->rrhhcontrato->toArray() : null,
                'detalles' => [],
            ];

            foreach ($dotacion->rrhhdetalledotacions as $det) {
                $payload['detalles'][] = [
                    'detalle' => $det->detalle,
                    'cantidad' => $det->cantidad,
                    'rrhhestadodotacion_id' => $det->rrhhestadodotacion_id ?? null,
                    'estado' => [
                        'nombre' => optional($det->rrhhestadodotacion)->nombre,
                    ],
                    'url' => $det->url ?? null,        // <-- añadir URL existente
                    'imagen' => $det->imagen ?? null,  // <-- añadir nombre de archivo si existe
                ];
            }

            return response()->json([
                'success' => true,
                'message' => $payload,
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error en RrhhdotacionController@edit', [
                'rrhhdotacion_id' => $id ?? null,
                'exception' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al obtener la dotación. Consulte el log.',
                'errors' => [$th->getMessage()],
            ], 500);
        }
    }

    public function update(Request $request)
    {
        // decode detalles if necessary
        $input = $request->all();
        if (isset($input['detalles']) && is_string($input['detalles'])) {
            $decoded = json_decode($input['detalles'], true);
            $input['detalles'] = is_array($decoded) ? $decoded : [];
        }

        $rules = [
            'rrhhdotacion_id' => 'required|integer',
            'fecha' => 'required|date',
            'responsable_entrega' => 'required|string|max:255',
            'detalles' => 'required|array|min:1',
            'detalles.*.detalle' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.estado' => 'required|integer',
        ];

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            $dotacion = Rrhhdotacion::findOrFail($input['rrhhdotacion_id']);
            $dotacion->update([
                'fecha' => $input['fecha'],
                'responsable_entrega' => $input['responsable_entrega'],
            ]);

            // Delete existing details
            $dotacion->rrhhdetalledotacions()->delete();

            // files
            $images = $request->file('images', []);

            foreach ($input['detalles'] as $idx => $detalle) {
                $det = Rrhhdetalledotacion::create([
                    'rrhhdotacion_id' => $dotacion->id,
                    'detalle' => $detalle['detalle'],
                    'cantidad' => $detalle['cantidad'],
                    'rrhhestadodotacion_id' => $detalle['estado'],
                ]);

                // buscar archivo usando image_index (enviado por el cliente) o fallback al índice $idx
                $fileKey = array_key_exists('image_index', $detalle) && $detalle['image_index'] !== null
                    ? $detalle['image_index']
                    : $idx;

                if (is_array($images) && array_key_exists($fileKey, $images) && $images[$fileKey]) {
                    $file = $images[$fileKey];
                    $ext = $file->getClientOriginalExtension() ?: 'jpg';
                    $filename = 'dot_' . $dotacion->id . '_det_' . $det->id . '_' . Str::uuid() . '.' . $ext;
                    // Comprimir y guardar usando GD (objetivo <= 200 KB). Retorna ruta relativa dentro de disk 'public'
                    $path = $this->compressAndStoreUploaded($file, 'images/dotaciones-empleados', $filename, 200);
                    $url = $path ? asset('storage/' . $path) : null;
                    
                    // save both imagen (legacy) and url (new)
                    if (Schema::hasColumn('rrhhdetalledotacions', 'imagen')) {
                        $det->imagen = $filename;
                    }
                    if (Schema::hasColumn('rrhhdetalledotacions', 'url')) {
                        $det->url = $url;
                    }
                    $det->save();
                } else {
                    // Si no se subió nuevo archivo pero el payload trae url/imagen (imagen original),
                    // conservar esos valores en el nuevo registro para no perder la referencia.
                    if (!empty($detalle['url']) && Schema::hasColumn('rrhhdetalledotacions', 'url')) {
                        $det->url = $detalle['url'];
                    }
                    if (!empty($detalle['imagen']) && Schema::hasColumn('rrhhdetalledotacions', 'imagen')) {
                        $det->imagen = $detalle['imagen'];
                    }
                    if (!empty($detalle['url']) || !empty($detalle['imagen'])) {
                        $det->save();
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Dotación actualizada correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error actualizar dotacion', ['exception' => $th]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la dotación: ' . $th->getMessage(),
                'errors' => [$th->getMessage()]
            ]);
        }
    }

    public function store(Request $request)
    {
        // Prepare input and decode detalles JSON if necessary
        $input = $request->all();
        if (isset($input['detalles']) && is_string($input['detalles'])) {
            $decoded = json_decode($input['detalles'], true);
            $input['detalles'] = is_array($decoded) ? $decoded : [];
        }

        // Validation rules
        $rules = [
            'rrhhcontrato_id' => 'required|integer',
            'empleado_id' => 'required|integer',
            'fecha' => 'required|date',
            'responsable_entrega' => 'required|string|max:255',
            'detalles' => 'required|array|min:1',
            'detalles.*.detalle' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.estado' => 'required|integer',
        ];

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            $dotacion = Rrhhdotacion::create([
                'rrhhcontrato_id' => $input['rrhhcontrato_id'],
                'empleado_id' => $input['empleado_id'],
                'fecha' => $input['fecha'],
                'responsable_entrega' => $input['responsable_entrega'],
            ]);

            // handle images sent as images[index]
            $images = $request->file('images', []); // returns array or null

            foreach ($input['detalles'] as $idx => $detalle) {
                $det = Rrhhdetalledotacion::create([
                    'rrhhdotacion_id' => $dotacion->id,
                    'detalle' => $detalle['detalle'],
                    'cantidad' => $detalle['cantidad'],
                    'rrhhestadodotacion_id' => $detalle['estado'],
                ]);

                // buscar archivo usando image_index (enviado por el cliente) o fallback al índice $idx
                $fileKey = array_key_exists('image_index', $detalle) && $detalle['image_index'] !== null
                    ? $detalle['image_index']
                    : $idx;

                if (is_array($images) && array_key_exists($fileKey, $images) && $images[$fileKey]) {
                    $file = $images[$fileKey];
                    $ext = $file->getClientOriginalExtension() ?: 'jpg';
                    $filename = 'dot_' . $dotacion->id . '_det_' . $det->id . '_' . Str::uuid() . '.' . $ext;
                    $path = $this->compressAndStoreUploaded($file, 'images/dotaciones-empleados', $filename, 200);
                    $url = $path ? asset('storage/' . $path) : null;
                    
                    if (Schema::hasColumn('rrhhdetalledotacions', 'imagen')) {
                        $det->imagen = $filename;
                    }
                    if (Schema::hasColumn('rrhhdetalledotacions', 'url')) {
                        $det->url = $url;
                    }
                    $det->save();
                } else {
                    // conservar url/imagen si el cliente envió referencias (raro en store, pero seguro)
                    if (!empty($detalle['url']) && Schema::hasColumn('rrhhdetalledotacions', 'url')) {
                        $det->url = $detalle['url'];
                    }
                    if (!empty($detalle['imagen']) && Schema::hasColumn('rrhhdetalledotacions', 'imagen')) {
                        $det->imagen = $detalle['imagen'];
                    }
                    if (!empty($detalle['url']) || !empty($detalle['imagen'])) {
                        $det->save();
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Dotación registrada correctamente.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Error crear dotacion', ['exception' => $th]);
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la dotación: ' . $th->getMessage(),
                'errors' => [$th->getMessage()]
            ]);
        }
    }

    /**
     * Comprime un UploadedFile y lo almacena en disk 'public', retorna la ruta relativa dentro del disk.
     */
    private function compressAndStoreUploaded($file, $dir, $filename, $maxKb = 200)
    {
        try {
            $tmpIn = $file->getRealPath();
            if (!file_exists($tmpIn)) return null;
            $mime = $file->getClientMimeType() ?: mime_content_type($tmpIn);
            $src = null;
            switch (strtolower($mime)) {
                case 'image/jpeg':
                case 'image/jpg':
                    $src = @imagecreatefromjpeg($tmpIn);
                    break;
                case 'image/png':
                    $src = @imagecreatefrompng($tmpIn);
                    break;
                case 'image/gif':
                    $src = @imagecreatefromgif($tmpIn);
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) $src = @imagecreatefromwebp($tmpIn);
                    break;
                default:
                    $data = @file_get_contents($tmpIn);
                    $src = $data ? @imagecreatefromstring($data) : null;
            }
            if (!$src) return null;

            $w = imagesx($src);
            $h = imagesy($src);
            $maxDim = 1600;
            $scale = 1;
            if (max($w, $h) > $maxDim) $scale = $maxDim / max($w, $h);
            $newW = max(1, (int)round($w * $scale));
            $newH = max(1, (int)round($h * $scale));
            $dst = imagecreatetruecolor($newW, $newH);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

            $tmpOut = tempnam(sys_get_temp_dir(), 'imgc');
            $quality = 90;
            imagejpeg($dst, $tmpOut, $quality);
            $sizeKb = filesize($tmpOut) / 1024;
            while ($sizeKb > $maxKb && $quality > 30) {
                $quality -= 10;
                imagejpeg($dst, $tmpOut, $quality);
                clearstatcache(true, $tmpOut);
                $sizeKb = filesize($tmpOut) / 1024;
            }
            $reduceScale = 0.9;
            while ($sizeKb > $maxKb && ($newW > 400 && $newH > 400)) {
                $newW = max(100, (int)round($newW * $reduceScale));
                $newH = max(100, (int)round($newH * $reduceScale));
                $tmpDst = imagecreatetruecolor($newW, $newH);
                imagealphablending($tmpDst, false);
                imagesavealpha($tmpDst, true);
                $transparent = imagecolorallocatealpha($tmpDst, 255, 255, 255, 127);
                imagefilledrectangle($tmpDst, 0, 0, $newW, $newH, $transparent);
                imagecopyresampled($tmpDst, $dst, 0, 0, 0, 0, $newW, $newH, imagesx($dst), imagesy($dst));
                imagejpeg($tmpDst, $tmpOut, max(25, $quality - 10));
                imagedestroy($tmpDst);
                clearstatcache(true, $tmpOut);
                $sizeKb = filesize($tmpOut) / 1024;
            }

            $relativePath = trim($dir, '/') . '/' . $filename;
            $content = file_get_contents($tmpOut);
            \Storage::disk('public')->put($relativePath, $content);

            if (file_exists($tmpOut)) @unlink($tmpOut);
            imagedestroy($dst);
            imagedestroy($src);

            return $relativePath;
        } catch (\Throwable $e) {
            \Log::error('compressAndStoreUploaded error', ['exception' => $e]);
            return null;
        }
    }
}
