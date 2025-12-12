<?php

use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\FocoCalorController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;

Route::post('/login', [LoginApiController::class, 'loginAPI']);

Route::middleware('auth:sanctum')->group(function () {

    // Obtener equipos con ubicación (para apps móviles/APIs externas)
    Route::get('/equipos', [EquipoController::class, 'api'])
        ->name('api.equipos');
    

    // Inscribirse a un curso (para móvil)
    Route::post('/cursos/{id}/inscribirme', [CursoController::class, 'apiInscribirme'])
        ->name('api.cursos.inscribirme');

    // Estado de inscripción a un curso (para móvil)
    Route::get('/cursos/{id}/inscripcion', [CursoController::class, 'apiInscripcionEstado'])
        ->name('api.cursos.inscripcion.estado');

    // Stage completion and progress
    Route::post('/cursos/{curso}/stages/{stage}/complete', [CursoController::class, 'apiMarkStageComplete'])
        ->name('api.cursos.stages.complete');
    Route::get('/cursos/{curso}/progress', [CursoController::class, 'getStageProgress'])
        ->name('api.cursos.progress');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
        ->name('api.notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
        ->name('api.notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])
        ->name('api.notifications.read-all');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])
        ->name('api.notifications.unread-count');
});

// Public API endpoints (sin autenticación requerida)
Route::prefix('v1')->group(function () {

    // NASA FIRMS Hotspots API
    Route::get('/hotspots', [FocoCalorController::class, 'api'])
        ->name('api.hotspots');

    Route::get('/hotspots/stats', [FocoCalorController::class, 'stats'])
        ->name('api.hotspots.stats');

    // Live NASA FIRMS data (real-time de NASA API)
    Route::get('/hotspots/live', [FocoCalorController::class, 'live'])
        ->name('api.hotspots.live');

    // Reportes (Citizen Fire Reports) API
    Route::get('/reportes', [ReporteController::class, 'api'])
        ->name('api.reportes');
    Route::post('/reportes', [ReporteController::class, 'storePublico'])
        ->name('api.reportes.store');

    Route::get('/cursos', [CursoController::class, 'api'])
        ->name('api.cursos.api');

    // Equipos desplegados (History/Map)
    Route::get('/equipos/desplegados', [EquipoController::class, 'deployed'])
        ->name('api.equipos.deployed');

    // Team details (single team with full information)
    Route::get('/equipos/{id}', [EquipoController::class, 'showApi'])
        ->name('api.equipos.show');

    // Animal Injury Reports
    Route::post('/reports', [\App\Http\Controllers\Api\ReporteAnimalController::class, 'store'])
        ->name('api.reports.animal');
});
 