<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name" => "Admin Sistema",
            "email" => "admin@vigilante",
            "password" => bcrypt('vigilante'),
            "template" => "ADMIN",
        ])->assignRole('Administrador');
    }
}
