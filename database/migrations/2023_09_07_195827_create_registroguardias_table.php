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
        Schema::create('registroguardias', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechahora');
            $table->enum('prioridad', ['BAJA', 'NORMAL', 'ALTA']);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('detalle')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->boolean('visto')->default(false);
            $table->integer('cliente_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registroguardias');
    }
};
