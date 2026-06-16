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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('designacione_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha');
            $table->datetime('ingreso')->nullable();
            $table->datetime('salida')->nullable();
            $table->string('latingreso')->nullable();
            $table->string('lngingreso')->nullable();
            $table->string('latsalida')->nullable();
            $table->string('lngsalida')->nullable();
            $table->boolean('estado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
