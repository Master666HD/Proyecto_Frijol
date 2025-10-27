<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionPrototipo extends Model
{
    protected $table = 'devoluciones_prototipos';
    public $timestamps = false;

    protected $fillable = [
        'idOperacion',
        'fechaDevolucion',
        'observaciones',
        'ganancia',          
        'monto_devolver',
    ];

    public function operacion()
    {
        return $this->belongsTo(OperacionPrototipo::class, 'idOperacion');
    }
}