<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('tickets_soporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciudadano_id')->nullable()->constrained('usuarios')->onDelete('set null'); 
            $table->string('correo_invitado')->nullable(); 
            $table->string('telefono_invitado', 20)->nullable(); 
            $table->string('asunto');
            $table->text('descripcion');
            $table->string('categoria', 50); 
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            $table->enum('estado', ['abierto', 'en_progreso', 'cerrado', 'resuelto'])->default('abierto');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tickets_soporte');
    }
};
