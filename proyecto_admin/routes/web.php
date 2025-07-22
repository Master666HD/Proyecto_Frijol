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
    Route::get('/reportes', [ReporteController::class, 'vistaReportes'])->name('vistaReportes');
    Route::get('/admin', [AdminController::class, 'index'])->name('vistaAdmin');
    Route::get('/operacion/crear', [OperacionPrototipoController::class, 'create'])->name('operacion.create');
    Route::post('/operacion/guardar', [OperacionPrototipoController::class, 'store'])->name('operacion.store');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




