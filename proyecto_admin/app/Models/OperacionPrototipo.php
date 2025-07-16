<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperacionPrototipo extends Model
{
    protected $table = 'operaciones_prototipos';
    public $timestamps = false;

    protected $fillable = [
        'idUsuario', 'idPrototipo', 'tipoOperacion',
        'precio', 'estado', 'fechaRegistro', 'fechaDevolucion'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }

    public function prototipo()
    {
        return $this->belongsTo(Prototipo::class, 'idPrototipo');
    }
}
