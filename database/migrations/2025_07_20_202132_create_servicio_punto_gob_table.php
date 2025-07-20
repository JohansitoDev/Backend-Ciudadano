<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('servicio_punto_gob', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('punto_gob_id')->constrained('puntos_gob')->onDelete('cascade');
            $table->timestamps();

           
            $table->unique(['servicio_id', 'punto_gob_id']);
        });
    }

 
    public function down(): void
    {
        Schema::dropIfExists('servicio_punto_gob');
    }
};

