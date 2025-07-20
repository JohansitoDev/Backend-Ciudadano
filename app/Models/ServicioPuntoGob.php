<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioPuntoGob extends Model
{
    use HasFactory;

    protected $table = 'servicio_punto_gob'; 

    protected $fillable = [
        'servicio_id',
        'punto_gob_id',
    ];

    protected $casts = [
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

 
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function puntoGob()
    {
        return $this->belongsTo(PuntoGob::class, 'punto_gob_id');
    }
}