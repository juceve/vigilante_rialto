<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index($lat, $lng)
    {
        return view('admin.ubicacion', compact('lat', 'lng'));
    }
}
