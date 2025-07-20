<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'ciudadano_id',
        'servicio_id',
        'fecha_cita',
        'hora_cita',
        'estado',
        'notas_ciudadano',
        'notas_administrador',
        'datos_qr',
        'punto_gob_id',
        'asignado_en',
    ];

    protected $casts = [
        'fecha_cita' => 'date',
        'hora_cita' => 'datetime', 
        'asignado_en' => 'datetime',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

  
    public function ciudadano()
    {
        return $this->belongsTo(Ciudadano::class, 'ciudadano_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function puntoGob()
    {
        return $this->belongsTo(PuntoGob::class, 'punto_gob_id');
    }
}