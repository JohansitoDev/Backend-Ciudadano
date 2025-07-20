<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_minutos',
        'institucion_id',
        'esta_activo',
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    
    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'institucion_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'servicio_id');
    }

    public function puntosGob()
    {
        return $this->belongsToMany(PuntoGob::class, 'servicio_punto_gob', 'servicio_id', 'punto_gob_id')
                    ->withTimestamps(); 
    }
}

