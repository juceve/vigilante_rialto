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
        Schema::create('rrhhadelantos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rrhhcontrato_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha');
            $table->string('mes',20);
            $table->string('motivo')->nullable();
            $table->float('monto',10,2);
            $table->string('documento_adjunto')->nullable();
            $table->enum('estado',['SOLICITADO','APROBADO','ANULADO'])->default('APROBADO');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhadelantos');
    }
};
