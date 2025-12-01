<?php

use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\FocoCalorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;

Route::post('/login', [LoginApiController::class, 'loginAPI']);

Route::middleware('auth:sanctum')->group(function () {

    // Obtener equipos con ubicación (para apps móviles/APIs externas)
    Route::get('/equipos', [EquipoController::class, 'api'])
        ->name('api.equipos');
});

// Public API endpoints (no authentication required)
Route::prefix('v1')->group(function () {
    
    // NASA FIRMS Hotspots API
    Route::get('/hotspots', [FocoCalorController::class, 'api'])
        ->name('api.hotspots');
    
    Route::get('/hotspots/stats', [FocoCalorController::class, 'stats'])
        ->name('api.hotspots.stats');
    
    // Live NASA FIRMS data (real-time from NASA API)
    Route::get('/hotspots/live', [FocoCalorController::class, 'live'])
        ->name('api.hotspots.live');

    Route::get('/cursos', [CursoController::class, 'api'])
        ->name('api.cursos.api');
});


