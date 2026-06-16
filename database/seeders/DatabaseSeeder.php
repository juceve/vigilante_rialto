<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Rrhhtipodescuento;
use App\Models\Sistemaparametro;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(tipodocsSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(userSeeder::class);

        $tipodescuento = Rrhhtipodescuento::create([
            "nombre" => "Atraso en Marcación",
            "nombre_corto" => "Atraso",
            "monto" => "60",
        ]);
        $parametros = Sistemaparametro::create([
            "tolerancia_ingreso" => 15,
            "telefono_panico" => '0',
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
