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
        Schema::create('flujopases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paseingreso_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha', 12);
            $table->enum('tipo',['INGRESO','SALIDA']);
            $table->string('hora',10);
            $table->string('anotaciones',255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flujopases');
    }
};
