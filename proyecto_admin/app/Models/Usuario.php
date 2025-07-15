<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{

    use HasApiTokens, Notifiable;
    protected $table = 'usuarios';
    public $timestamps = false;


    protected $fillable = [
        'nombres',
        'apellidos',
        'telefono',
        'correo',
        'usuario',
        'contrasenia',
        'rol'
    ];

    protected $hidden = [
        'contrasenia',
    ];

    protected $casts = [
        'estado' => 'boolean', 
        'fechaRegistro' => 'datetime', 
        'fechaActualizacion' => 'datetime', 
    ];

    protected static function booted()
    {
        static::creating(function ($usuario) { 
            $usuario->fechaRegistro = Carbon::now(); 
            $usuario->fechaActualizacion = Carbon::now();
        });
        static::updating(function ($usuario) { 
            $usuario->fechaActualizacion = Carbon::now(); 
        });
    }

    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    // Mutador para encriptar la contraseña automáticamente cuando se asigna
    public function setContraseniaAttribute($value)
    {
        // Encripta la contraseña solo si no está ya encriptada (o si es un nuevo valor)
        // Hash::needsRehash($value) podría ser útil para rehashear
        $this->attributes['contrasenia'] = Hash::make($value);
    }

}