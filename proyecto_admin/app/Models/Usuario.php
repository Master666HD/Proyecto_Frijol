<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
     use HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'nombres', 'apellidos', 'telefono', 'correo', 'usuario', 'contrasenia',
        'rol', 'estado', 'fechaRegistro', 'fechaActualizacion'
    ];

    protected $hidden = ['contrasenia'];

    protected $casts = [
        'estado' => 'boolean',
        'fechaRegistro' => 'datetime',
        'fechaActualizacion' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($usuarios) {
            $usuarios->fechaRegistro = Carbon::now();
            $usuarios->fechaActualizacion = Carbon::now();
        });

        static::updating(function ($usuarios) {
            $usuarios->fechaActualizacion = Carbon::now();
        });
    }
}
