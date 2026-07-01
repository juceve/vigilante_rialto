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
        Schema::create('sistemaparametros', function (Blueprint $table) {
            $table->id();
            $table->integer('tolerancia_ingreso');
            $table->string('telefono_panico',30)->nullable();
            $table->decimal('asistencia_sin_salida', 8, 2)->default(0);
            $table->decimal('falta_dia_completo', 8, 2)->default(0);
            $table->decimal('salida_antes_tiempo', 8, 2)->default(0);
            $table->integer('salida_anticipada')->nullable();
            $table->integer('ingreso_atrasado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sistemaparametros');
    }
};
