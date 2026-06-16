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
        Schema::create('citecobros', function (Blueprint $table) {
            $table->id();
            $table->integer('correlativo');
            $table->integer('gestion');
            $table->string('cite');
            $table->date('fecha');
            $table->string('fechaliteral');
            $table->string('cliente');
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->string('representante');
            $table->string('mescobro');
            $table->boolean('confactura')->default(false);
            $table->string('factura');
            $table->double('monto');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citecobros');
    }
};
