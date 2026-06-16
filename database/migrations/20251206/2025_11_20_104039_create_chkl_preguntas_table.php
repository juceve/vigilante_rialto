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
        Schema::create('chkl_preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chkl_listaschequeo_id')->nullable()->constrained()->nullOnDelete();
            $table->text('descripcion');          
            $table->foreignId('tipoboleta_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('requerida')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chkl_preguntas');
    }
};
