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
        Schema::create('rrhhasistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designacione_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha');
            $table->string('ingreso',50)->nullable();
            $table->string('salida',50)->nullable();
            $table->foreignId('rrhhestado_id')->nullable()->constrained()->nullOnDelete();
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhasistencias');
    }
};
