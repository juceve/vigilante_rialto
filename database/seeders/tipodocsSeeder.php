<?php

namespace Database\Seeders;

use App\Models\Tipodocumento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class tipodocsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipo = Tipodocumento::create([
            "name" => "CEDULA IDENTIDAD",
            "shortname" => "C.I.",
        ]);
        $tipo = Tipodocumento::create([
            "name" => "DOCUMENTO DE IDENTIFICACION TRIBUTARIA",
            "shortname" => "NIT",
        ]);
        $tipo = Tipodocumento::create([
            "name" => "CEDULA EXTRAJERO",
            "shortname" => "C.E.",
        ]);
    }
}
