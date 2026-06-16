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
        Schema::create('rondaejecutadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ronda_id')->nullable()->constrained('rondas')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fin')->nullable();
            $table->string('latitud_inicio')->nullable();
            $table->string('longitud_inicio')->nullable();
            $table->string('latitud_fin')->nullable();
            $table->string('longitud_fin')->nullable();
            $table->enum('status', ['EN_PROGRESO', 'FINALIZADA'])->default('EN_PROGRESO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rondaejecutadas');
    }
};
