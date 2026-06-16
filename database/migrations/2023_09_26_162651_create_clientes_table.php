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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('tipodocumento_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nrodocumento');
            $table->string('direccion');
            $table->string('uv', 50);
            $table->string('manzano', 50);
            $table->string('latitud', 50);
            $table->string('longitud', 50);
            $table->string('personacontacto');
            $table->string('telefonocontacto', 50);
            $table->foreignId('oficina_id')->nullable()->constrained()->nullOnDelete();
            $table->string('observaciones')->nullable();
            $table->string('email',100)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
