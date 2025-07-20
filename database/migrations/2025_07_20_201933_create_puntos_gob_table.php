<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('puntos_gob', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion', 500);
            $table->string('telefono', 20)->nullable();
            $table->string('correo_electronico')->nullable();
            $table->decimal('latitud', 10, 7)->nullable(); 
            $table->decimal('longitud', 10, 7)->nullable(); 
            $table->foreignId('institucion_id')->constrained('instituciones')->onDelete('cascade'); 
            $table->boolean('esta_activo')->default(true); 
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('puntos_gob');
    }
};