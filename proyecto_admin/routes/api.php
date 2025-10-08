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
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::put('/user',        [AuthController::class, 'update']);
    Route::put('/user/password',[AuthController::class, 'updatePassword']); 
});
Route::get('/semillas', [SemillaController::class, 'obtenerPorUsuario']);
Route::get('/resumen_clasificacion', [ClasificacionController::class, 'getResumenClasificacion']);
Route::get('/semillas/asignacion', [SemillaController::class, 'getAsignacionActiva']);
Route::post('/semillas', [SemillaController::class, 'store']);
Route::get('/classification/summary', [ClasificacionController::class, 'getResumenClasificacion']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/last-batch-summary', [SemillaController::class, 'lastBatchSummary']);
    Route::get('/productivity-metrics', [SemillaController::class, 'productivityMetrics']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/seeds', [SemillaController::class, 'getBatchHistory']); 
    Route::get('/seeds/{id}', [SemillaController::class, 'getBatchDetail']); 
    Route::post('/seeds/compare', [SemillaController::class, 'compareBatches']); 
    Route::get('/seeds/export/{format}', [SemillaController::class, 'exportBatch']); 
});

