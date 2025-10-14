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
    $seeds = Semilla::where('idUsuario', $idUser)
        ->orderBy('fechaRegistro', 'asc')
        ->get();

    if ($seeds->isEmpty()) {
        return response()->json(['message' => 'No data found'], 404);
    }

    $batches = [];
    $currentBatch = [];
    $lastTime = null;
    $minutesGap = 10; // Diferencia mínima entre lotes en minutos

    foreach ($seeds as $seed) {
        $date = Carbon::parse($seed->fechaRegistro)->setTimezone('America/La_Paz');
        $diff = $lastTime ? $date->diffInMinutes($lastTime, true) : 0;

        if ($lastTime && $diff > $minutesGap) {
            // Guardar lote anterior
            $batches[] = $currentBatch;
            $currentBatch = [];
        }

        $currentBatch[] = $seed;
        $lastTime = $date;
    }

    if (!empty($currentBatch)) {
        $batches[] = $currentBatch;
    }

    // Tomar solo el último lote
    $lastBatch = end($batches);

    return response()->json([
        'by_color' => collect($lastBatch)->groupBy('color')->map->count(),
        'by_size' => collect($lastBatch)->groupBy('tamano')->map->count(),
        'by_weight' => collect($lastBatch)->groupBy('peso')->map->count(),
        'by_status' => collect($lastBatch)->groupBy('estado')->map->count(),
        'date' => Carbon::parse($lastBatch[0]->fechaRegistro)->toDateString(),
        
    ]);
}


public function productivityMetrics()
{
    $idUser = auth()->user()->id;
    Log::info('📊 [Metrics] Usuario autenticado', ['id' => $idUser]);

    $totalBeans = Semilla::where('idUsuario', $idUser)->count();

    // Últimos 7 días (fechas)
    $last7Days = collect(range(0, 6))
        ->map(fn($d) => Carbon::today()->subDays($d)->toDateString())
        ->reverse();

    // Inicio del periodo (usar startOfDay para cubrir todo el día)
    $start = Carbon::today()->subDays(6)->startOfDay();
    Log::info('📊 [Metrics] Filtro inicio', ['start' => $start->toDateTimeString(), 'timezone' => config('app.timezone')]);

    // Semillas en el rango
    $seeds = Semilla::where('idUsuario', $idUser)
        ->where('fechaRegistro', '>=', $start)
        ->orderBy('fechaRegistro', 'asc')
        ->get();

    Log::info('📊 [Metrics] Semillas crudas', ['count' => $seeds->count()]);

    if ($seeds->isEmpty()) {
        // Información adicional de depuración para entender el dataset real del usuario
        $totalUserSeeds = Semilla::where('idUsuario', $idUser)->count();
        $earliest = Semilla::where('idUsuario', $idUser)->min('fechaRegistro');
        $latest   = Semilla::where('idUsuario', $idUser)->max('fechaRegistro');

        Log::warning('📊 [Metrics] No hay semillas en el rango solicitado', [
            'total_user_seeds' => $totalUserSeeds,
            'earliest' => $earliest,
            'latest' => $latest
        ]);

        // Responder con ceros pero incluyendo debug para cliente (útil en desarrollo)
        $batchesLastWeek = $last7Days->mapWithKeys(fn($date) => [$date => 0]);

        return response()->json([
            'total_beans' => $totalBeans,
            'batches_last_week' => $batchesLastWeek,
            'debug' => [
                'start_filter' => $start->toDateTimeString(),
                'total_user_seeds' => $totalUserSeeds,
                'earliest' => $earliest,
                'latest' => $latest
            ]
        ]);
    }

    // Agrupación por lotes (misma lógica que getBatchHistory)
    $minutesGap = 10;
    $batches = [];
    $currentBatch = [];
    $lastTime = null;

    foreach ($seeds as $seed) {
        $date = Carbon::parse($seed->fechaRegistro);
        $diff = $lastTime ? $date->diffInMinutes($lastTime, true) : 0;

        if ($lastTime && $diff > $minutesGap) {
            $batches[] = $currentBatch;
            $currentBatch = [];
        }

        $currentBatch[] = $seed;
        $lastTime = $date;
    }

    if (!empty($currentBatch)) $batches[] = $currentBatch;

    Log::info('📊 [Metrics] Lotes detectados', ['total_lotes' => count($batches)]);

    $batchesByDay = collect($batches)
        ->groupBy(fn($batch) => Carbon::parse($batch[0]->fechaRegistro)->toDateString())
        ->map->count();

    $batchesLastWeek = $last7Days->mapWithKeys(fn($date) => [
        $date => $batchesByDay->get($date, 0)
    ]);

    Log::info('📊 [Metrics] Normalizado a 7 días', $batchesLastWeek->toArray());

    return response()->json([
        'total_beans' => $totalBeans,
        'batches_last_week' => $batchesLastWeek
    ]);
}





public function getBatchHistory(Request $request)
{
    $userId = $request->user()->id;

    $seeds = Semilla::where('idUsuario', $userId)
        ->orderBy('fechaRegistro', 'asc')
        ->get();

    $batches = [];
    $currentBatch = [];
    $lastTime = null;
    $minutesGap = 10;

    foreach ($seeds as $seed) {
        $date = Carbon::parse($seed->fechaRegistro);

        $diff = $lastTime ? $date->diffInMinutes($lastTime, true) : 0;

        if ($lastTime && $diff > $minutesGap) {
            // Save previous batch
            $batches[] = [
                'start' => $currentBatch[0]->fechaRegistro,
                'end' => end($currentBatch)->fechaRegistro,
                'seeds' => $currentBatch,
                'total' => count($currentBatch),
            ];
            $currentBatch = [];
        }

        $currentBatch[] = $seed;
        $lastTime = $date;
    }

    if (!empty($currentBatch)) {
        $batches[] = [
            'start' => $currentBatch[0]->fechaRegistro,
            'end' => end($currentBatch)->fechaRegistro,
            'seeds' => $currentBatch,
            'total' => count($currentBatch),
        ];
    }

    // Map output to English keys
    $batches = array_map(function ($batch) {
        return [
            'start' => $batch['start'],
            'end' => $batch['end'],
            'total' => $batch['total'],
            'seeds' => array_map(function ($seed) {
                return [
                    'id' => $seed->id,
                    'color' => $seed->color,
                    'size' => $seed->tamano,
                    'weight' => $seed->peso,
                    'status' => $seed->estado,
                    'registration_date' => $seed->fechaRegistro
                ];
            }, $batch['seeds'])
        ];
    }, $batches);

    return response()->json($batches, 200);
}

// Similar changes para obtenerDetalleLote y compararLotes
public function getBatchDetail($id, Request $request)
{
    $seed = Semilla::where('idUsuario', $request->user()->id)
        ->where('id', $id)
        ->firstOrFail();

    // Map to English keys
    return response()->json([
        'id' => $seed->id,
        'color' => $seed->color,
        'size' => $seed->tamano,
        'weight' => $seed->peso,
        'status' => $seed->estado,
        'registration_date' => $seed->fechaRegistro
    ], 200);
}

public function compareBatches(Request $request)
{
    $request->validate([
        'ids' => 'required|array|min:2',
        'ids.*' => 'integer|exists:semillas,id'
    ]);

    $seeds = Semilla::whereIn('id', $request->ids)
        ->where('idUsuario', $request->user()->id)
        ->get();

    // Map to English
    $seeds = $seeds->map(function ($seed) {
        return [
            'id' => $seed->id,
            'color' => $seed->color,
            'size' => $seed->tamano,
            'weight' => $seed->peso,
            'status' => $seed->estado,
            'registration_date' => $seed->fechaRegistro
        ];
    });

    return response()->json($seeds, 200);
}
public function exportBatch(Request $request, $format)
{
    $start = $request->query('start');
    $end = $request->query('end');

    if (!$start || !$end) {
        return response()->json(['error' => 'Missing start or end parameters'], 400);
    }

    // Get all seeds of the user in the given range
    $batch = Semilla::where('idUsuario', $request->user()->id)
        ->whereBetween('fechaRegistro', [$start, $end])
        ->get();

    if ($batch->isEmpty()) {
        return response()->json(['error' => 'No seeds found in this range'], 404);
    }

    // --- CSV export ---
    if ($format === 'csv') {
        $csv = "Color,Size,Weight,Status,Registration Date\n";
        foreach ($batch as $seed) {
            $csv .= "{$seed->color},{$seed->tamano},{$seed->peso},{$seed->estado},{$seed->fechaRegistro}\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"batch_{$start}_{$end}.csv\"",
        ]);
    }

    // --- PDF export ---
    if ($format === 'pdf') {
        // Map seeds to English keys
        $batchMapped = $batch->map(function($seed) {
            return [
                'color' => $seed->color,
                'size' => $seed->tamano,
                'weight' => $seed->peso,
                'status' => $seed->estado,
                'registration_date' => $seed->fechaRegistro
            ];
        });

        // Ensure the view exists and pass the mapped data
        if (!view()->exists('exports.batch')) {
            return response()->json(['error' => 'PDF view not found'], 500);
        }

        $pdf = Pdf::loadView('exports.batch', ['batch' => $batchMapped]);
        return $pdf->download("batch_{$start}_{$end}.pdf");
    }

    return response()->json(['error' => 'Invalid format (only csv or pdf)'], 400);
}

public function exportBatchHistoryPdf(Request $request)
{
    $userId = $request->user()->id;

    $seeds = Semilla::where('idUsuario', $userId)
        ->orderBy('fechaRegistro', 'asc')
        ->get();

    $batches = [];
    $currentBatch = [];
    $lastTime = null;
    $minutesGap = 10;

    // Agrupar en lotes según intervalo de tiempo
    foreach ($seeds as $seed) {
        $date = Carbon::parse($seed->fechaRegistro);
        $diff = $lastTime ? $date->diffInMinutes($lastTime, true) : 0;

        if ($lastTime && $diff > $minutesGap) {
            $batches[] = $currentBatch;
            $currentBatch = [];
        }

        $currentBatch[] = $seed;
        $lastTime = $date;
    }

    if (!empty($currentBatch)) {
        $batches[] = $currentBatch;
    }

    // ✅ Calcular resumen de cada lote
    $summary = [];
    foreach ($batches as $index => $batch) {
        $aptos = collect($batch)->where('estado', 'APTO')->count();
        $noAptos = collect($batch)->where('estado', 'NO APTO')->count();

        $summary[] = [
            'lote' => $index + 1,
            'inicio' => $batch[0]->fechaRegistro ?? '-',
            'fin' => end($batch)->fechaRegistro ?? '-',
            'total' => count($batch),
            'aptos' => $aptos,
            'no_aptos' => $noAptos,
        ];
    }

    $pdf = Pdf::loadView('exports.batches', ['summary' => $summary]);
    return $pdf->download('resumen_lotes.pdf');
}


}