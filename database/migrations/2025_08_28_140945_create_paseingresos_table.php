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
        Schema::create('paseingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residencia_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nombre',100);
            $table->string('cedula',50);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->foreignId('motivo_id')->nullable()->constrained()->nullOnDelete();
            $table->string('detalles');
            $table->boolean('usounico')->nullable()->default(true);
            $table->string('url_foto')->nullable();
            $table->boolean('estado')->default(true);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitados');
    }
};
