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
          Schema::create('movimientocajas', function (Blueprint $table) {
            $table->id();

            // Relación con la caja chica
            $table->foreignId('cajachica_id')
                ->constrained('cajachicas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Fecha del movimiento
            $table->date('fecha');

            // Tipo de movimiento
            $table->enum('tipo', ['INGRESO', 'EGRESO']);

            // Monto del movimiento
            $table->decimal('monto', 10, 2);

            // Detalle del movimiento
            $table->string('concepto');

            // Categoría opcional
            $table->string('categoria')->nullable();

            // Referencia opcional (factura, recibo, etc.)
            $table->string('referencia')->nullable();

            // Comprobante: ruta o nombre del archivo de imagen/PDF
            $table->string('comprobante')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientocajas');
    }
};
