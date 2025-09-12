<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SemillaController;
use App\Http\Controllers\Api\ClasificacionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',     [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']); // opcional
});
Route::get('/semillas', [SemillaController::class, 'obtenerPorUsuario']);
Route::get('/classification/summary', [ClasificacionController::class, 'getResumenClasificacion']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/last-batch-summary', [SemillaController::class, 'lastBatchSummary']);
    Route::get('/productivity-metrics', [SemillaController::class, 'productivityMetrics']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/semillas', [SemillaController::class, 'obtenerHistorialLotes']); 
    Route::get('/semillas/{id}', [SemillaController::class, 'obtenerDetalleLote']); 
    Route::post('/semillas/comparar', [SemillaController::class, 'compararLotes']); 
    Route::get('/semillas/exportar/{formato}', [SemillaController::class, 'exportarLote']); 
});

