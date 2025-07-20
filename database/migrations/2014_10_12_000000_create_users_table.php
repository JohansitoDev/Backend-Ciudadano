<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); 
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo_electronico')->unique(); 
            $table->string('contrasena'); 
            $table->string('telefono', 20)->nullable(); 
            $table->string('direccion', 500)->nullable(); 
            $table->timestamp('correo_verificado_en')->nullable(); 
            $table->string('estado')->default('activo'); 
            $table->timestamps(); 
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};