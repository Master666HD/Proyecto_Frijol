<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semilla;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Total de semillas
        $total = Semilla::count();

        // Semillas BUENAS y MALAS
        $semillasBuenas = Semilla::where('color', 'Bueno')->count();
        $semillasMalas = Semilla::where('color', 'Malo')->count();

        // Últimas semillas registradas (5)
        $ultimasSemillas = Semilla::with('usuario')->orderBy('fechaRegistro', 'desc')->get();

        // Gráfico de tendencia por día
        $tendencia = Semilla::select(
            DB::raw('DATE(fechaRegistro) as fecha'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('fecha')
        ->orderBy('fecha', 'asc')
        ->get();
        // Prepare data for Chart.js
        $labels = $tendencia->pluck('fecha')->toArray();
        $data = $tendencia->pluck('total')->toArray();


        return view('vistaAdmin', [
            'total' => $total,
            'semillasBuenas' => $semillasBuenas,
            'semillasMalas' => $semillasMalas,
            'ultimasSemillas' => $ultimasSemillas,
            'tendencia' => $tendencia,
            'labels' => $labels, 
            'data' => $data
        ]);
    }
}
