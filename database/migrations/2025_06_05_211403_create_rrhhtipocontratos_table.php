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
        Schema::create('rrhhtipocontratos', function (Blueprint $table) {
            $table->id();            
            $table->string('codigo',30);
            $table->string('nombre',100);
            $table->string('descripcion')->nullable();
            $table->boolean('mensualizado')->default(true);
            $table->integer('cantidad_dias');
            $table->integer('horas_dia');
            $table->float('sueldo_referencial',10,2)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhtipocontratos');
    }
};
