<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semilla extends Model
{
    protected $table = 'semillas';
    public $timestamps = false;

    protected $fillable = [
        'color',
        'forma',
        'peso',
        'idUsuario',
        'idPrototipo',
        'fechaRegistro'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}
