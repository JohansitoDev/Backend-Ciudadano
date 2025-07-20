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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciudadano_id')->constrained('usuarios')->onDelete('cascade'); 
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade'); 
            $table->date('fecha_cita');
            $table->time('hora_cita');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada', 'no_asistio'])->default('pendiente');
            $table->text('notas_ciudadano')->nullable();
            $table->text('notas_administrador')->nullable();
            $table->string('datos_qr')->unique(); 
            $table->foreignId('punto_gob_id')->nullable()->constrained('puntos_gob')->onDelete('set null'); 
            $table->timestamp('asignado_en')->nullable(); 
            $table->timestamps();
        });
    }

 
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};