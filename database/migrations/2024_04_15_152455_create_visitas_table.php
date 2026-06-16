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
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('docidentidad', 20);
            $table->string('residente')->nullable();
            $table->string('nrovivienda')->nullable();
            $table->foreignId('motivo_id')->nullable()->constrained()->nullOnDelete();
            $table->string('otros')->nullable();
            $table->longText('imgs')->nullable();
            $table->longText('observaciones')->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('designacione_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
