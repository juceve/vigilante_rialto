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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->foreignId('tipodocumento_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cedula', 25);
            $table->string('expedido', 4);
            $table->date('fecnacimiento')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('direccion');
            $table->string('direccionlat')->nullable();
            $table->string('direccionlng')->nullable();
            $table->string('telefono', 50);
            $table->string('imgperfil')->nullable();
            $table->string('cedulaanverso')->nullable();
            $table->string('cedulareverso')->nullable();
            $table->boolean('cubrerelevos')->default(false);
            $table->string('email', 100);
            $table->string('enfermedades')->nullable();
            $table->string('alergias')->nullable();
            $table->string('persona_referencia')->nullable();
            $table->string('telefono_referencia')->nullable();
            $table->string('parentezco_referencia')->nullable();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('oficina_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
