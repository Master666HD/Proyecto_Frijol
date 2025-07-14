<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semilla extends Model
{
      protected $table = 'semillas';
    public $timestamps = false;
    protected $primaryKey = 'id_usuario';
    protected $fillable = [
        'color',
        'id_usuario'    
    ];
}
