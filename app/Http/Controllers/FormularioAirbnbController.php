<?php

namespace App\Http\Controllers;

use App\Models\Airbnbcompanion;
use App\Models\Airbnblink;
use App\Models\Airbnbtraveler;
use App\Models\Citecobro;
use App\Models\Citeinforme;
use App\Models\Citecotizacion;
use App\Models\Citerecibo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class FormularioAirbnbController extends Controller
{
    public function index($encryptedId)
    {
        try {
            $link_id =  Crypt::decrypt($encryptedId);
            $airbnblink = Airbnblink::find($link_id);
            if (!$airbnblink) {
                return;
            }
            $cliente = $airbnblink->cliente;
            if ($airbnblink->vigencia >= now()) {
                return view('customer.formulario_airbnb', compact('link_id', 'cliente'));
            } else {
                return view('customer.formulario_expirado', compact('link_id'));
            }
        } catch (DecryptException $e) {
            return view('customer.formulario_cobroerror');
        }
    }
    public function cobro($encryptedId)
    {
        try {
            $decryptedId = Crypt::decrypt($encryptedId);
            $link_id =  Crypt::decrypt($encryptedId);
            $citecobro = Citecobro::find($link_id);

            if (!$citecobro) {
                return;
            }
            return view('customer.formulario_cobro', compact('link_id', 'citecobro'));
        } catch (DecryptException $e) {
            return view('customer.formulario_cobroerror');
        }
    }
    public function cotizacion($encryptedId)
    {
        try {
            $decryptedId = Crypt::decrypt($encryptedId);
            $link_id =  Crypt::decrypt($encryptedId);
            $citecotizacion = Citecotizacion::find($link_id);

            if (!$citecotizacion) {
                return;
            }
        // dd($citecotizacion);
            return view('customer.formulario_cotizacion', compact('link_id', 'citecotizacion'));
        } catch (DecryptException $e) {
            return view('customer.formulario_cobroerror');
        }
    }
    public function recibo($encryptedId)
    {
        try {
            $decryptedId = Crypt::decrypt($encryptedId);
            $link_id =  Crypt::decrypt($encryptedId);
            $citerecibo = Citerecibo::find($link_id);

            if (!$citerecibo) {
                return;
            }
            return view('customer.formulario_recibo', compact('link_id', 'citerecibo'));
        } catch (DecryptException $e) {
            return view('customer.formulario_cobroerror');
        }
    }
    public function informe($encryptedId)
    {
        try {
            $decryptedId = Crypt::decrypt($encryptedId);
            $link_id =  Crypt::decrypt($encryptedId);
            $citeinforme = Citeinforme::find($link_id);

            if (!$citeinforme) {
                return;
            }
            $puntos = explode('|', $citeinforme->puntos);
            return view('customer.formulario_informe', compact('link_id', 'citeinforme','puntos'));
        } catch (DecryptException $e) {
            return view('customer.formulario_cobroerror');
        }
    }
    public function regsuccess($encryptedId)
    {
        $registro_id = Crypt::decrypt($encryptedId);
        $traveler = Airbnbtraveler::find($registro_id);
        $companions = $traveler->airbnbcompanions;
        $link = $traveler->airbnblink;

        $contenido = $link->id . '|' . $traveler->id . '|' . $companions->count();
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($contenido) . '&size=200x200';
        return view('customer.registroexitoso', compact('traveler', 'companions', 'link', 'qrUrl', 'contenido'));
    }

    public function descargarQr($contenido)
    {

        // Sanitizar y codificar el contenido
        $encodedContent = urlencode($contenido);
        $data = explode(",", $contenido);

        // URL de la API para generar el QR
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?data={$encodedContent}&size=200x200";

        // Descargar el QR como un archivo binario
        $response = Http::get($qrApiUrl);

        // Verificar que la respuesta sea válida
        if ($response->ok()) {
            // Descargar la imagen
            return response($response->body(), 200)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="QR_CONTROL.png"');
        }

        // Manejar errores en la generación del QR
        return response()->json([
            'message' => 'No se pudo generar el código QR. Intente nuevamente.',
        ], 500);
    }
    public function descargarPDF($id)
    {
        $airbnbtraveler = Airbnbtraveler::find($id);
        $data = $airbnbtraveler->toArray();
        $companions_count = $airbnbtraveler->airbnbcompanions->count();
        $condominio = $airbnbtraveler->airbnblink->cliente->nombre;
        $data['condominio'] = $condominio;
        $data['companions_count'] = $companions_count;
        $companions = array();
        if ($companions_count > 0) {
            foreach ($airbnbtraveler->airbnbcompanions as $airbnbcompanion) {
                $companions[] = $airbnbcompanion->toArray();
            }
        }
        $data['companions_data'] = $companions;

        $pdf = Pdf::loadView('pdfs.pdfformularioairbnb', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'formularioairbnb.pdf');
    }
}
