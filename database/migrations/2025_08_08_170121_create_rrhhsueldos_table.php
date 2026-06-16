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
        Schema::create('rrhhsueldos', function (Blueprint $table) {
            $table->id();            
            $table->integer('gestion');
            $table->integer('mes');
            $table->date('fecha');
            $table->string('hora',8);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('estado',['CREADO','PROCESADO','ANULADO'])->default('CREADO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhsueldos');
    }
};
