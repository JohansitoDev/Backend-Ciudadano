<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Ciudadano extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios'; 


    protected $fillable = [
        'nombre',
        'apellido',
        'correo_electronico',
        'contrasena', 
        'telefono',
        'direccion',
        'correo_verificado_en',
        'estado',
    ];

    protected $hidden = [
        'contrasena', 
    ];

    protected $casts = [
        'correo_verificado_en' => 'datetime',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

  
    public function citas()
    {
        return $this->hasMany(Cita::class, 'ciudadano_id');
    }

    public function ticketsSoporte()
    {
        return $this->hasMany(TicketsSoporte::class, 'ciudadano_id');
    }


    public function setPasswordAttribute($value)
    {
        $this->attributes['contrasena'] = bcrypt($value);
    }
}