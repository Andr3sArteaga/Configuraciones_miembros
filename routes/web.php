<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComunarioApoyoController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RecursoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReporteIncendio;
use App\Http\Controllers\ReporteIncendioController;
use App\Http\Controllers\UsuarioController;
use App\Models\ComunariosApoyo;

Route::get('/', function () {
    return view('welcome'); // Asegúrate de tener resources/views/welcome.blade.php
})->middleware(['auth'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard'); // Asegúrate de tener resources/views/dashboard.blade.php
})->middleware(['auth'])->name('dashboard');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('usuarios', UsuarioController::class);

Route::resource('reportes', ReporteController::class);

Route::resource('recursos', RecursoController::class);

Route::resource('comunarios_apoyo', ComunarioApoyoController::class);

Route::resource('reportes_incendio', ReporteIncendioController::class);

Route::prefix('donaciones')->group(function () {
    Route::get('/', fn() => "Lista de donaciones")->name('donaciones.index');
});
