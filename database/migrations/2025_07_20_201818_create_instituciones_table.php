<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('instituciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion', 500)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo_electronico')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('instituciones');
    }
};