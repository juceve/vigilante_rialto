<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UploadsController extends Controller
{
    public function uploadFile(Request $request)
    {
        $option = $request->option;
        switch ($option) {
            case 1: {
                    $request->validate([
                        'archivo' => 'required|file|max:10240', // 10MB max
                    ]);

                    $archivo = $request->file('archivo');
                    $id = $request->id;
                    

                    $rutaDestino = "storage/documentos/contratos/";
                    if (!File::exists($rutaDestino)) {
                        File::makeDirectory($rutaDestino, 0755, true);
                    }                    
                    $nombre = $id.'_'.Auth::user()->id . "_" . date('Ymd_His');
                    // Obtiene la extensión original
                    $extension = $archivo->getClientOriginalExtension();
                    $archivo->move($rutaDestino, $nombre . '.' . $extension);
                    return response()->json([
                        'message' => 'Archivo subido correctamente',
                        'url' => 'documentos/contratos/' . $nombre . '.' . $extension,
                    ]);
                }
                break;
        }
    }
}
