<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rrhhferiados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->date('fecha')->nullable();          // fecha única
            $table->date('fecha_inicio')->nullable();   // rango inicio
            $table->date('fecha_fin')->nullable();      // rango fin
            $table->boolean('recurrente')->default(false);
            $table->float('factor', 5, 2)->default(1.0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhferiados');
    }
};
