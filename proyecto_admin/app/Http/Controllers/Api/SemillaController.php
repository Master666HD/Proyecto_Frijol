<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semilla;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class SemillaController extends Controller
{
    public function obtenerPorUsuario(Request $request)
{
    $idUsuario = $request->query('idUsuario');
    $semillas = Semilla::where('idUsuario', $idUsuario)->get();

    return response()->json($semillas);
}
public function lastBatchSummary()
{
    $idUser = auth()->user()->id;

    // Obtener todas las semillas del usuario ordenadas ascendente
    $semillas = Semilla::where('idUsuario', $idUser)
        ->orderBy('fechaRegistro', 'asc')
        ->get();

    if ($semillas->isEmpty()) {
        return response()->json(['message' => 'No data found'], 404);
    }

    $lotes = [];
    $loteActual = [];
    $ultimoTiempo = null;
    $minutosSeparacion = 20; // Diferencia mínima entre lotes en minutos

    foreach ($semillas as $semilla) {
        $fecha = Carbon::parse($semilla->fechaRegistro)->setTimezone('America/La_Paz');
        $diferencia = $ultimoTiempo ? $fecha->diffInMinutes($ultimoTiempo, true) : 0;

        if ($ultimoTiempo && $diferencia > $minutosSeparacion) {
            // Guardar lote anterior
            $lotes[] = $loteActual;
            $loteActual = [];
        }

        $loteActual[] = $semilla;
        $ultimoTiempo = $fecha;
    }

    if (!empty($loteActual)) {
        $lotes[] = $loteActual;
    }

    // Tomamos el último lote
    $lastBatch = end($lotes);

    $date = Carbon::parse($lastBatch[0]->fechaRegistro)->toDateString();

    return response()->json([
        'by_color' => collect($lastBatch)->groupBy('color')->map->count(),
        'by_size' => collect($lastBatch)->groupBy('tamano')->map->count(),
        'by_weight' => collect($lastBatch)->groupBy('peso')->map->count(),
        'date' => $date
    ]);
}


public function productivityMetrics()
{
    $idUser = auth()->user()->id;

    $totalBeans = Semilla::where('idUsuario', $idUser)->count();

    $last7Days = collect(range(0, 6))
        ->map(fn($d) => Carbon::today()->subDays($d)->toDateString())
        ->reverse();

    $weeklyBatches = Semilla::where('idUsuario', $idUser)
        ->where('fechaRegistro', '>=', now()->subDays(6))
        ->get()
        ->groupBy(fn($item) => Carbon::parse($item->fechaRegistro)->toDateString())
        ->map->count();

    $weeklyBatches = $last7Days->mapWithKeys(fn($date) => [
        $date => $weeklyBatches->get($date, 0)
    ]);

    return response()->json([
        'total_beans' => $totalBeans,
        'batches_last_week' => $weeklyBatches
    ]);
}



public function obtenerHistorialLotes(Request $request)
    {
        // Traer todas las semillas del usuario ordenadas por fecha ascendente
        $semillas = Semilla::where('idUsuario', $request->user()->id)
            ->orderBy('fechaRegistro', 'asc')
            ->get();

        $lotes = [];
        $loteActual = [];
        $ultimoTiempo = null;
        $minutosSeparacion = 10; // <- si la diferencia es mayor, se crea un nuevo lote

        foreach ($semillas as $semilla) {
            $fecha = Carbon::parse($semilla->fechaRegistro);

            if ($ultimoTiempo) {
               $diferencia = $fecha->diffInMinutes($ultimoTiempo, true);
                Log::info("Procesando semilla ID {$semilla->id} en {$fecha}, diferencia con anterior: {$diferencia} min");

                if ($diferencia > $minutosSeparacion) {
                    // Guardamos el lote actual
                    Log::info("Nueva separación detectada: creando nuevo lote con " . count($loteActual) . " semillas");
                    $lotes[] = [
                        'inicio' => $loteActual[0]->fechaRegistro,
                        'fin' => end($loteActual)->fechaRegistro,
                        'semillas' => $loteActual,
                        'total' => count($loteActual),
                    ];
                    $loteActual = [];
                }
            } else {
                Log::info("Procesando primera semilla ID {$semilla->id} en {$fecha}");
            }

            $loteActual[] = $semilla;
            $ultimoTiempo = $fecha;
        }

        // Guardar último lote si hay semillas
        if (!empty($loteActual)) {
            Log::info("Guardando último lote con " . count($loteActual) . " semillas, inicio: {$loteActual[0]->fechaRegistro}, fin: " . end($loteActual)->fechaRegistro);
            $lotes[] = [
                'inicio' => $loteActual[0]->fechaRegistro,
                'fin' => end($loteActual)->fechaRegistro,
                'semillas' => $loteActual,
                'total' => count($loteActual),
            ];
        }

        return response()->json($lotes, 200);
    }

    // ✅ Obtener detalle de un lote específico
    public function obtenerDetalleLote($id, Request $request)
    {
        $lote = Semilla::where('idUsuario', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($lote, 200);
    }

    // ✅ Comparar múltiples lotes seleccionados
    public function compararLotes(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:2',
            'ids.*' => 'integer|exists:semillas,id',
        ]);

        $lotes = Semilla::whereIn('id', $request->ids)
            ->where('idUsuario', $request->user()->id)
            ->get();

        return response()->json($lotes, 200);
    }

    // ✅ Exportar lote en CSV o PDF
   public function exportarLote(Request $request, $formato)
{
    $inicio = $request->query('inicio');
    $fin = $request->query('fin');

    if (!$inicio || !$fin) {
        return response()->json(['error' => 'Faltan parámetros inicio o fin'], 400);
    }

    // Traer todas las semillas del usuario dentro del rango
    $lote = Semilla::where('idUsuario', $request->user()->id)
        ->whereBetween('fechaRegistro', [$inicio, $fin])
        ->get();

    if ($lote->isEmpty()) {
        return response()->json(['error' => 'No se encontraron semillas en este rango'], 404);
    }

    // --- CSV ---
    if ($formato === 'csv') {
        $csv = "Color,Tamaño,Peso,Estado,Fecha Registro\n";
        foreach ($lote as $semilla) {
            $csv .= "{$semilla->color},{$semilla->tamano},{$semilla->peso},{$semilla->estado},{$semilla->fechaRegistro}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=lote_{$inicio}_{$fin}.csv",
        ]);
    }

    // --- PDF ---
    if ($formato === 'pdf') {
        $pdf = Pdf::loadView('exports.lote', ['lote' => $lote]);
        return $pdf->download("lote_{$inicio}_{$fin}.pdf");
    }

    return response()->json(['error' => 'Formato no válido (solo csv o pdf)'], 400);
}
}