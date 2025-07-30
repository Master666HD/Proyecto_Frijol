<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semilla;

class SemillaController extends Controller
{
    public function obtenerPorUsuario(Request $request)
{
    $idUsuario = $request->query('idUsuario');
    $semillas = Semilla::where('idUsuario', $idUsuario)->get();

    return response()->json($semillas);
}
}
