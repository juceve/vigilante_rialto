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
        Schema::create('ctrlpuntos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('hora');
            $table->string('latitud');
            $table->string('longitud');
            $table->foreignId('turno_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctrlpuntos');
    }
};
