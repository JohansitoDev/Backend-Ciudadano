<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketsSoporte extends Model
{
    use HasFactory;

    protected $table = 'tickets_soporte';

    protected $fillable = [
        'ciudadano_id',
        'correo_invitado',
        'telefono_invitado',
        'asunto',
        'descripcion',
        'categoria',
        'prioridad',
        'estado',
    ];

    protected $casts = [
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    
    public function ciudadano()
    {
        return $this->belongsTo(Ciudadano::class, 'ciudadano_id');
    }
}