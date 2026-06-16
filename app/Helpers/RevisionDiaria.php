<?php

use App\Models\Designacione;
use App\Models\Revdiaria;
use Illuminate\Support\Facades\DB;

function hayRevisionHoy()
{
    $revision = Revdiaria::where('fecha', date('Y-m-d'))->get();
    if ($revision->count()) {
        return true;
    } else {
        return false;
    }
}

function procesosDiarios()
{
    $hoy = date('Y-m-d');


    DB::beginTransaction();
    try {
        //////////////// REVISA DESIGNACIONES EXPIRADAS//////////////
        $designacionesexpiradas = Designacione::where([
            ['fechaFin', '<', $hoy],
            ['estado', 1],
        ])->get();

        foreach ($designacionesexpiradas as $item) {
            $item->estado = false;
            $item->save();
        }
        //////////////// FIN DESIGNACIONES EXPIRADAS ////////////////

        //////////////// PROCESO 2 //////////////
        //////////////// FIN PROCESO 2 //////////

        //////////////// PROCESO 3 //////////////
        //////////////// FIN PROCESO 3 //////////

        //////////////// PROCESO 4 //////////////
        //////////////// FIN PROCESO 4 //////////

        $revision = Revdiaria::create([
            "fecha" => $hoy
        ]);

        DB::commit();
    } catch (\Throwable $th) {
        DB::rollBack();
    }
}
