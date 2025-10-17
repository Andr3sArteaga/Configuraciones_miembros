<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ComunarioApoyoController;
use App\Http\Controllers\ReporteIncendioController;
use App\Http\Controllers\NotificationController;

// ------------------ Autenticación ------------------

Auth::routes();

// ------------------ Middleware Auth ------------------

Route::middleware(['auth'])->group(function () {

    // ------------------ Página principal ------------------
    Route::get('/', fn() => view('welcome'))->name('dashboard');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // ------------------ Admin ------------------
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    });

    // ------------------ CRUD principales ------------------
    Route::resources([
        'usuarios' => UsuarioController::class,
        'reportes' => ReporteController::class,
        'recursos' => RecursoController::class,
        'comunarios_apoyo' => ComunarioApoyoController::class,
        'reportes_incendio' => ReporteIncendioController::class,
    ]);

    // ------------------ Donaciones ------------------
    Route::prefix('donaciones')->group(function () {
        Route::get('/', fn() => "Lista de donaciones")->name('donaciones.index');
    });

    // ------------------ Notificaciones ------------------
    Route::prefix('notifications')->group(function () {
        Route::get('/show', [NotificationController::class, 'show'])->name('notifications.show');
        Route::get('/get', [NotificationController::class, 'get'])->name('notifications.get');
        Route::get('/fake', [NotificationController::class, 'fake'])->name('notifications.fake');
    });
});

// ------------------ Home (redirige tras login) ------------------
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
