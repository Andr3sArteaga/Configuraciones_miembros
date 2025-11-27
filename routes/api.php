<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'loginAPI']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/students', function () {
        return 'students';
    });

    Route::get('/equipos', [EquipoController::class, 'api'])
        ->name('api.equipos');
});
