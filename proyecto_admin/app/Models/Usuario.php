<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon; // Para manejar fechas y horas

class Usuario extends Authenticatable
{
    // Traits que proporcionan funcionalidades para tokens API y notificaciones
    use HasApiTokens, Notifiable;

    // Define el nombre de la tabla de la base de datos asociada a este modelo
    protected $table = 'usuarios';

    // Deshabilita las marcas de tiempo automáticas de Laravel (created_at, updated_at)
    // Esto es intencional ya que estás manejando 'fechaRegistro' y 'fechaActualizacion' manualmente
    public $timestamps = false;

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'nombres',
        'apellidos',
        'telefono',
        'correo',
        'usuario',
        'contrasenia', // Nota: Asegúrate de que esta contraseña se hashee antes de guardar
        'rol',
        'estado',
        'fechaRegistro',
        'fechaActualizacion'
    ];

    // Atributos que deben ser ocultados de las serializaciones de arrays/JSON
    protected $hidden = [
        'contrasenia', // Es crucial ocultar la contraseña por seguridad
    ];

    // Conversión de tipos para atributos al leerlos de la base de datos
    protected $casts = [
        'estado' => 'boolean', // Convierte 'estado' a booleano
        'fechaRegistro' => 'datetime', // Convierte 'fechaRegistro' a objeto Carbon
        'fechaActualizacion' => 'datetime', // Convierte 'fechaActualizacion' a objeto Carbon
    ];

    /**
     * El método "booted" se ejecuta una vez que el modelo ha sido inicializado.
     * Aquí se definen los eventos del modelo para manejar las fechas personalizadas.
     */
    protected static function booted()
    {
        // Evento 'creating': se ejecuta antes de que un nuevo registro sea creado
        static::creating(function ($usuario) { // Cambié $usuarios a $usuario para consistencia con el nombre del modelo
            $usuario->fechaRegistro = Carbon::now(); // Establece la fecha de registro actual
            $usuario->fechaActualizacion = Carbon::now(); // Establece la fecha de actualización inicial
        });

        // Evento 'updating': se ejecuta antes de que un registro existente sea actualizado
        static::updating(function ($usuario) { // Cambié $usuarios a $usuario
            $usuario->fechaActualizacion = Carbon::now(); // Actualiza la fecha de actualización
        });
    }

    // Si estás usando 'contrasenia' como la columna de contraseña para autenticación,
    // Laravel esperará un método para obtener la contraseña.
    // Aunque 'contrasenia' está en $hidden, Laravel puede acceder a ella internamente.
    // Si necesitas un método específico para obtener la contraseña hasheada para la autenticación,
    // puedes añadirlo aquí, pero Laravel lo maneja automáticamente si la columna es 'password'
    // o si el campo en $hidden coincide con el nombre de la columna que almacena la contraseña.
    // En tu caso, 'contrasenia' es el nombre, así que Laravel lo usará.
}