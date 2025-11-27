<?php

use App\Http\Controllers\Api\LoginApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;

Route::post('/login', [LoginApiController::class, 'loginAPI']);

Route::middleware('auth:sanctum')->group(function () {

    // Obtener equipos con ubicación (para apps móviles/APIs externas)
    Route::get('/equipos', [EquipoController::class, 'api'])
        ->name('api.equipos');
});
