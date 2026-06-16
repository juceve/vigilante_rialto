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
        Schema::create('rondaejecutadaubicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rondaejecutada_id')->nullable()->constrained('rondaejecutadas')->nullOnDelete();
            $table->foreignId('rondapunto_id')->nullable()->constrained('rondapuntos')->nullOnDelete();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->dateTime('fecha_hora');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rondaejecutadaubicaciones');
    }
};
