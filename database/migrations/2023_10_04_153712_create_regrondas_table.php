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
        Schema::create('regrondas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designacione_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ctrlpunto_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha');
            $table->string('hora', 5);
            $table->string('anotaciones')->nullable();
            $table->string('latA')->nullable();
            $table->string('lngA')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regrondas');
    }
};
