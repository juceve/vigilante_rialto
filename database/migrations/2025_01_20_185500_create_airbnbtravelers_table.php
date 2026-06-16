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
        Schema::create('airbnbtravelers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airbnblink_id')->nullable()->constrained()->nullOnDelete(); // Identificador del enlace
            $table->dateTime('arrival_date')->nullable(); // Fecha de ingreso            
            $table->dateTime('departure_date')->nullable(); // Fecha de salida            
            $table->string('name')->nullable(); // Nombre titular
            $table->string('department_info')->nullable(); // Informacion del DPTO
            $table->date('birth_date')->nullable(); // Fecha de nacimiento
            $table->string('document_type')->nullable(); // Tipo documento
            $table->string('document_number')->nullable(); // Número documento
            $table->string('city_of_origin')->nullable(); // Ciudad de procedencia
            $table->string('marital_status')->nullable(); // Estado civil
            $table->string('address')->nullable(); // Dirección de residencia
            $table->string('city')->nullable(); // Ciudad
            $table->string('country')->nullable(); // País
            $table->string('email')->nullable(); // Correo electrónico
            $table->string('phone')->nullable(); // Teléfono
            $table->string('occupation')->nullable(); // Ocupación
            $table->integer('luggage_count')->nullable(); // Cantidad de equipajes
            $table->string('company')->nullable(); // Empresa (opcional)
            $table->string('travel_purpose')->nullable(); // Propósito de viaje
            $table->dateTime('reg_in')->nullable();
            $table->dateTime('reg_out')->nullable();
            $table->enum('status',['CREADO','ACTIVADO','FINALIZADO'])->default('CREADO'); // Propósito de viaje
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airbnbtravelers');
    }
};
