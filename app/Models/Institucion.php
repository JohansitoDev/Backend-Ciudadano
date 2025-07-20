<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasFactory;

    protected $table = 'instituciones';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo_electronico',
    ];

    protected $casts = [
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

  
    public function puntosGob()
    {
        return $this->hasMany(PuntoGob::class, 'institucion_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'institucion_id');
    }
}