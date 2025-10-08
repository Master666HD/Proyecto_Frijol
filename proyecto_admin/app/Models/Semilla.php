<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semilla extends Model
{
    protected $table = 'semillas';
    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'idPrototipo',
        'uid',
        'color',
        'tamano',
        'peso',
        'estado',
        'fechaRegistro'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}
