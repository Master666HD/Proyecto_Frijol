<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClasificacionController extends Controller
{
    public function getResumenClasificacion(Request $request)
    {
        $idUsuario = $request->query('idUsuario');

        // Validar que el idUsuario esté presente y sea un número
        if (!$idUsuario || !is_numeric($idUsuario)) {
            return response()->json(['error' => 'ID de usuario no válido.'], 400);
        }

        // 1. Obtener todas las semillas para el usuario dado
        // Si usas Eloquent:
        // $semillas = Semilla::where('idUsuario', $idUsuario)->get();
        // Si usas DB Facade (más directo para agregados):
        $semillas = DB::table('semillas')
                      ->where('idUsuario', $idUsuario)
                      ->get();

        // 2. Calcular los agregados y el resumen

        $totalFrijolesClasificados = $semillas->count();

        $frijolesAptos = $semillas->where('estado', 'APTO')->count();
        $porcentajeAptos = ($totalFrijolesClasificados > 0)
                            ? round(($frijolesAptos / $totalFrijolesClasificados) * 100, 2)
                            : 0.0; // Redondear a 2 decimales

        // Para la última fecha de clasificación
        $ultimaClasificacion = $semillas->sortByDesc('fechaRegistro')->first();
        $ultimaClasificacionFecha = $ultimaClasificacion ? $ultimaClasificacion->fechaRegistro : null;

        // Para el conteo por estado (APTO vs NO APTO)
        $frijolesPorEstado = $semillas->groupBy('estado')
                                      ->map(fn($item) => $item->count());
        // Asegúrate de que las claves "APTO" y "NO APTO" existan aunque el conteo sea 0
        $frijolesPorEstado = [
            'APTO' => $frijolesPorEstado['APTO'] ?? 0,
            'NO APTO' => $frijolesPorEstado['NO APTO'] ?? 0,
            // Agrega otros estados si los tienes
        ];

        // Para el conteo por color
        $frijolesPorColor = $semillas->groupBy('color')
                                     ->map(fn($item) => $item->count());

        // 3. Construir la respuesta JSON en el formato que espera tu app Android
        $response = [
            'totalFrijolesClasificados' => $totalFrijolesClasificados,
            'porcentajeAptos' => $porcentajeAptos,
            'ultimaClasificacionFecha' => $ultimaClasificacionFecha,
            'frijolesPorEstado' => $frijolesPorEstado,
            'frijolesPorColor' => $frijolesPorColor->toArray(), // Convertir a array si es una colección
        ];

        return response()->json($response);
    }
}