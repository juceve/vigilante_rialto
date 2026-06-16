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
        Schema::create('designaciondias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('designacione_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('domingo')->default(false);
            $table->boolean('lunes')->default(true);
            $table->boolean('martes')->default(true);
            $table->boolean('miercoles')->default(true);
            $table->boolean('jueves')->default(true);
            $table->boolean('viernes')->default(true);
            $table->boolean('sabado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designaciondias');
    }
};
