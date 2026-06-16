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
        Schema::create('rrhhpermisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rrhhcontrato_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rrhhtipopermiso_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('cantidad_horas')->nullable();
            $table->string('motivo')->nullable();
            $table->string('documento_adjunto')->nullable();
            $table->boolean('activo')->default(true);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhpermisos');
    }
};
