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
       Schema::create('cajachicas', function (Blueprint $table) {
            $table->id();

            // Responsable de la caja
            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Gestión contable
            $table->integer('gestion');

            // Fechas de control
            $table->date('fecha_apertura');
            $table->date('fecha_cierre')->nullable();

            // Estado de la caja
            $table->enum('estado', ['ACTIVA', 'CERRADA'])->default('ACTIVA');

            // Observaciones opcionales
            $table->string('observacion')->nullable();

            $table->timestamps();

            // Evita duplicar cajas para el mismo responsable y gestión
            $table->unique(['empleado_id', 'gestion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajachicas');
    }
};
