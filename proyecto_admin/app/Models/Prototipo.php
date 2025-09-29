<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prototipo extends Model
{
    protected $table = 'prototipos';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'serial',
        'estado',
        'precio',
        'observaciones',
        'fechaRegistro'
    ];

    // Relaciones
    public function operaciones()
    {
        return $this->hasMany(OperacionPrototipo::class, 'idPrototipo');
    }

    public function semillas()
    {
        return $this->hasMany(Semilla::class, 'idPrototipo');
    }
}
