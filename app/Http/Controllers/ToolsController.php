<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ToolsController extends Controller
{
    public function passwordsPropietarios()
    {
        $propietarios = DB::table('propietarios')->get();

        foreach ($propietarios as $prop) {
            DB::table('users')
                ->where('id', $prop->user_id)
                ->update([
                    'password' => Hash::make($prop->cedula),
                ]);
        }
        return redirect()->route('home')->with('success', 'Contraseñas actualizadas correctamente.');

    }
}
