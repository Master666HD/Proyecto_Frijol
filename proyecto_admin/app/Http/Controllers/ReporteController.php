<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperacionPrototipo;
use App\Models\Prototipo;
use App\Models\Reporte;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteController extends Controller
{
    // Vista inicial para elegir el reporte
    public function index()
    {
        return view('reportes.index');
    }

    // Reporte de facturación por ALQUILER
    public function reporteAlquiler(Request $request)
    {
        $inicio = $request->input('inicio');
        $fin = $request->input('fin');

        $query = OperacionPrototipo::whereRaw('LOWER(tipoOperacion) = ?', ['alquiler']);

        if ($inicio && $fin) {
            $inicio = Carbon::parse($inicio)->startOfDay();
            $fin = Carbon::parse($fin)->endOfDay();
            $query->whereBetween('fechaRegistro', [$inicio, $fin]);
        }

        $operaciones = $query->get();
        $total = $query->sum('precio');

        // Guardar reporte
        $reporte = Reporte::create([
            'idOperacion' => null,
            'tipo_reporte' => 'facturacion_alquiler',
            'fechaRegistro' => Carbon::now()
        ]);

        $pdf = Pdf::loadView('reportes.reporteAlquiler', [
            'operaciones' => $operaciones,
            'total' => $total,
            'inicio' => $inicio ? $inicio->format('Y-m-d') : null,
            'fin' => $fin ? $fin->format('Y-m-d') : null,
        ]);

        return $pdf->download("reporte_alquiler_{$reporte->id}.pdf");
    }

    // Reporte de facturación por VENTA
    public function reporteVenta(Request $request)
    {
        $inicio = $request->input('inicio');
        $fin = $request->input('fin');

        $query = OperacionPrototipo::whereRaw('LOWER(tipoOperacion) = ?', ['venta']);

        if ($inicio && $fin) {
            $inicio = Carbon::parse($inicio)->startOfDay();
            $fin = Carbon::parse($fin)->endOfDay();
            $query->whereBetween('fechaRegistro', [$inicio, $fin]);
        }

        $operaciones = $query->get();
        $total = $query->sum('precio');

        $reporte = Reporte::create([
            'idOperacion' => null,
            'tipo_reporte' => 'facturacion_venta',
            'fechaRegistro' => Carbon::now()
        ]);

        $pdf = Pdf::loadView('reportes.reporteVenta', [
            'operaciones' => $operaciones,
            'total' => $total,
            'inicio' => $inicio ? $inicio->format('Y-m-d') : null,
            'fin' => $fin ? $fin->format('Y-m-d') : null,
        ]);

        return $pdf->download("reporte_venta_{$reporte->id}.pdf");
    }

    // Reporte de prototipos en MANTENIMIENTO
    public function reporteMantenimiento()
    {
        // Prototipos que están en mantenimiento (estado = 3)
        $prototipos = Prototipo::where('estado', 3)->get();

        // Guardamos en la tabla reportes
        $reporte = Reporte::create([
            'idOperacion' => null,
            'tipo_reporte' => 'mantenimiento',
            'fechaRegistro' => Carbon::now()
        ]);

        // Generamos el PDF
        $pdf = Pdf::loadView('reportes.reporteMantenimiento', [
            'prototipos' => $prototipos
        ]);

        return $pdf->download("reporte_mantenimiento_{$reporte->id}.pdf");
    }


    // Reporte de STOCK (prototipos disponibles)
    public function reporteStock()
    {
        // Por ejemplo, estado 1 = disponible para alquilar, 2 = disponible para vender
        $prototipos = Prototipo::whereIn('estado', [1, 2])->get();

        $reporte = Reporte::create([
            'idOperacion' => null,
            'tipo_reporte' => 'stock',
            'fechaRegistro' => Carbon::now()
        ]);

        $pdf = Pdf::loadView('reportes.reporteStock', [
            'prototipos' => $prototipos
        ]);

        return $pdf->download("reporte_stock_{$reporte->id}.pdf");
    }
}
