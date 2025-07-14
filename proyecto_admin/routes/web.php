<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/vistaAdmin', [Controller::class, 'index'])->name('vistaAdmin');
Route::get('/vistaUsuarios', [UserController::class, 'index'])->name('vistaUsuarios');
Route::get('/vistaUsuarios/create', [UserController::class, 'create'])->name('usuarios.create');
Route::post('/vistaUsuarios', [UserController::class, 'store'])->name('usuarios.store');
Route::get('/vistaUsuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
Route::put('/vistaUsuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
Route::delete('/vistaUsuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
Route::get('/vistaReportes', [Controller::class, 'vistaReportes'])->name('vistaReportes');
