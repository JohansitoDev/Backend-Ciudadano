<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoGob extends Model
{
    use HasFactory;

    protected $table = 'puntos_gob';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo_electronico',
        'latitud',
        'longitud',
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
        return $this->hasMany(Cita::class, 'punto_gob_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_punto_gob', 'punto_gob_id', 'servicio_id')
                    ->withTimestamps(); 
    }
}