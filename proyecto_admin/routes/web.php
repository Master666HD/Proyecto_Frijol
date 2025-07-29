<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OperacionPrototipoController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('usuarios', UserController::class);
    Route::resource('operacion', OperacionPrototipoController::class);
    Route::get('/reportes', [ReporteController::class, 'vistaReportes'])->name('vistaReportes');    
    Route::get('/admin', [AdminController::class, 'index'])->name('vistaAdmin');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/usuario/pdf', [ReporteController::class, 'reporteUsuario'])->name('reportes.usuario.pdf');
    Route::get('/reportes/fechas/pdf', [ReporteController::class, 'generarPorFechas'])->name('reportes.fechas.pdf');
    
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




