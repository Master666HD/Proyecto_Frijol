<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prototipo;
use Carbon\Carbon;
use App\Models\OperacionPrototipo;

use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
         // 📌 KPI’s generales
        $totalOperaciones = OperacionPrototipo::count();
        $totalVentas = OperacionPrototipo::where('tipoOperacion', 'venta')->count();
        $totalAlquileres = OperacionPrototipo::where('tipoOperacion', 'alquiler')->count();

        // 💰 Ingresos totales
        $ingresosTotales = OperacionPrototipo::sum('precio');
        $ingresosVentas = OperacionPrototipo::where('tipoOperacion', 'venta')->sum('precio');
        $ingresosAlquileres = OperacionPrototipo::where('tipoOperacion', 'alquiler')->sum('precio');

        // 📊 Estado de prototipos (para gráfico de pastel)
        $prototiposParaAlquilar = Prototipo::where('estado', 1)->count();
        $prototiposParaVender = Prototipo::where('estado', 2)->count();
        $prototiposMantenimiento = Prototipo::where('estado', 3)->count();
    
        $prototiposVendidos = Prototipo::where('estado', 4)->count();
        $prototiposAlquilados = Prototipo::where('estado', 5)->count();

     

        // 🗓️ Gráfico de barras (operaciones por mes)
        $operacionesPorMes = OperacionPrototipo::select(
                DB::raw('MONTH(fechaRegistro) as mes'),
                DB::raw('SUM(CASE WHEN tipoOperacion = "venta" THEN 1 ELSE 0 END) as ventas'),
                DB::raw('SUM(CASE WHEN tipoOperacion = "alquiler" THEN 1 ELSE 0 END) as alquileres')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Etiquetas de los meses
        $labelsMeses = $operacionesPorMes->map(function ($op) {
            return Carbon::create()->month($op->mes)->locale('es')->monthName;
        });

        $dataVentas = $operacionesPorMes->pluck('ventas');
        $dataAlquileres = $operacionesPorMes->pluck('alquileres');

        // 📋 Últimas operaciones
        $ultimasOperaciones = OperacionPrototipo::with(['usuario', 'prototipo'])
            ->orderBy('fechaRegistro', 'desc')
            ->take(5)
            ->get();

        return view('vistaAdmin', compact(
            'totalOperaciones',
            'totalVentas',
            'totalAlquileres',
            'ingresosTotales',
            'ingresosVentas',
            'ingresosAlquileres',
            'prototiposVendidos',
            'prototiposAlquilados',
            'labelsMeses',
            'dataVentas',
            'prototiposParaAlquilar',
            'prototiposParaVender',
            'prototiposMantenimiento',
            'dataAlquileres',
            'ultimasOperaciones'
        ));
}
}
