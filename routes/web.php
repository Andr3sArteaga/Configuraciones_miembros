<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComunarioApoyoController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReporteIncendio;
use App\Http\Controllers\ReporteIncendioController;
use App\Http\Controllers\UsuarioController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Models\ComunariosApoyo;

//* ------------------ RUTAS WEB -----------------
// -----------------   Bienvenida   --------------------

Route::get('/#', function () {
    return view('welcome'); // Asegúrate de tener resources/views/welcome.blade.php
})->middleware(['auth'])->name('dashboard');

// -----------------   Admin  ------------------

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
Route::put('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

// ------------------ Contenido -----------------

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

// ------------------ Rutas Donaciones -----------------

Route::prefix('donaciones')->group(function () {
    Route::get('/', fn() => "Lista de donaciones")->name('donaciones.index');
});

// ------------------ Rutas Dark Mode -----------------


// routes/web.php
// Route::get('/toggle-darkmode', function () {
//     $current = session('dark_mode', false);
//     session(['dark_mode' => !$current]);

//     return redirect()->back(); // Redirecciona a la página anterior
// })->name('toggle.darkmode');
