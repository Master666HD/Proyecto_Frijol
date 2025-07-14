<?php

namespace App\Http\Controllers;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        return view('vistaAdmin');
    }
    public function vistaReportes()
    {
        return view('vistaReportes');
    }
    public function vistaSemillas()
    {
        return view('vistaSemillas');
    }
}
