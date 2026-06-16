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
        Schema::create('rrhhsueldoempleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rrhhsueldo_id')->constrained()->onDelete('cascade');
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rrhhcontrato_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nombreempleado',70)->nullable();
            $table->integer('total_permisos')->default(0);
            $table->decimal('total_adelantos', 10, 2)->default(0);
            $table->decimal('total_bonos', 10, 2)->default(0);
            $table->string('ids_bonos')->nullable();
            $table->decimal('total_descuentos', 10, 2)->default(0);
            $table->decimal('total_ctrlasistencias', 10, 2)->default(0);
            $table->decimal('salario_mes', 10, 2)->default(0);
            $table->decimal('liquido_pagable', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhsueldoempleados');
    }
};
