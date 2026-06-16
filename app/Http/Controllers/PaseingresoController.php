<?php

namespace App\Http\Controllers;

use App\Models\Paseingreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

/**
 * Class PaseingresoController
 * @package App\Http\Controllers
 */
class PaseingresoController extends Controller
{

    public function resumen($id){
        $decryptedId = Crypt::decrypt($id);
        $paseingreso = Paseingreso::find($decryptedId);
        return view('paseingreso.resumen-pase',compact('paseingreso'));
    }
}
