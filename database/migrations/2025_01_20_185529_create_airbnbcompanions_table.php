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
        Schema::create('airbnbcompanions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airbnbtraveler_id')->constrained()->cascadeOnDelete(); // Relación con viajero
            $table->string('name')->nullable(); // Nombre
            $table->date('birth_date')->nullable(); // Fecha de nacimiento
            $table->string('document_type')->nullable(); // Tipo documento
            $table->string('document_number')->nullable(); // Número documento
            $table->string('nationality')->nullable(); // Nacionalidad
            $table->integer('luggage_count')->nullable(); // Cantidad de equipajes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airbnbcompanions');
    }
};
