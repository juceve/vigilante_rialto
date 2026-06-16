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
        Schema::create('rrhhcontratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rrhhtipocontrato_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->float('salario_basico',10,2);
            $table->float('gestora',10,2)->default(0);
            $table->boolean('caja_seguro')->default(false);
            $table->foreignId('rrhhcargo_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('moneda',['BOL','USD'])->default('BOL');
            $table->string('motivo_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhcontratos');
    }
};
