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
        Schema::create('rrhhestados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',80);
            $table->string('nombre_corto',20);
            $table->float('factor')->default(1);
            $table->string('color',15)->default('#FFF');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rrhhestados');
    }
};
