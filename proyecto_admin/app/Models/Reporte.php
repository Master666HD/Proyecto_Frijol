<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reportes';
    public $timestamps = false;

    protected $fillable = [
        'idOperacion',
        'tipo_reporte',
        'fechaRegistro'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}

