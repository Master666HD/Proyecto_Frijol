<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // Importa el controlador base de Laravel

// La clase Controller NO debe ser abstracta. Debe extender de BaseController.
class Controller extends BaseController // <-- ¡Aquí estaba el cambio clave!
{
    use AuthorizesRequests, ValidatesRequests;
}
