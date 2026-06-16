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
        Schema::create('sup_boletas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechahora')->default(now());
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tipoboleta_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('empleados','id')->nullOnDelete();
            $table->text('detalles')->nullable();
            $table->decimal('descuento',10,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sup_boletas');
    }
};
