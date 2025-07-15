<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // Importa el controlador base de Laravel

// La clase Controller NO debe ser abstracta. Debe extender de BaseController.
class Controller extends BaseController // <-- ¡Aquí estaba el cambio clave!
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Muestra la vista de administración.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('vistaAdmin');
    }

    /**
     * Muestra la vista de reportes.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function vistaReportes()
    {
        return view('vistaReportes');
    }

    /**
     * Muestra la vista de semillas.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function vistaSemillas()
    {
        return view('vistaSemillas');
    }
}
