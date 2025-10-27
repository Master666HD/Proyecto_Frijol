<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperacionPrototipoController;
use App\Http\Controllers\PrototipoController;

Route::get('/', [AuthController::class, 'mostrarLogin'])->name('login');

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('usuarios', UserController::class);
    Route::resource('prototipos', PrototipoController::class);
    Route::resource('operaciones', OperacionPrototipoController::class);

    Route::get('/operaciones/{id}/devolucion', [OperacionPrototipoController::class, 'formDevolucion'])->name('operaciones.devolucion.form');
    Route::post('/operaciones/{id}/devolucion', [OperacionPrototipoController::class, 'registrarDevolucion'])->name('operacion.devolucion.store');

    Route::post('prototipos/store-multiple', [PrototipoController::class, 'storeMultiple'])->name('prototipos.storeMultiple');

    Route::get('/admin', [AdminController::class, 'index'])->name('vistaAdmin');
    Route::get('operaciones/{id}/recibo', [OperacionPrototipoController::class, 'generarRecibo'])->name('operaciones.recibo');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::post('/reportes/alquiler', [ReporteController::class, 'reporteAlquiler'])->name('reportes.alquiler');
    Route::post('/reportes/venta', [ReporteController::class, 'reporteVenta'])->name('reportes.venta');
    Route::post('/reportes/stock', [ReporteController::class, 'reporteStock'])->name('reportes.stock');
    Route::post('/reportes/mantenimiento', [ReporteController::class, 'reporteMantenimiento'])->name('reportes.mantenimiento');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
