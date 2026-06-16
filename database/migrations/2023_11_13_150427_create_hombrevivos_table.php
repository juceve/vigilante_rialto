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
        Schema::create('hombrevivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervalo_id')->nullable()->constrained()->nullOnDelete();
            $table->date('fecha');
            $table->string('hora', 5);
            $table->string('anotaciones')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hombrevivos');
    }
};
