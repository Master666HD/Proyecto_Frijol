<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionPrototipo extends Model
{
    protected $table = 'devoluciones_prototipos';
    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'idOperacion',
        'fechaDevolucion',
        'observaciones'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }

    public function operacion()
    {
        return $this->belongsTo(OperacionPrototipo::class, 'idOperacion');
    }
}
