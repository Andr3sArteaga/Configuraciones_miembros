<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReporteIncendioController;
use App\Http\Controllers\FocoCalorController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\InscritoController;

// ... (existing imports)
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\TipoSangreController;
use App\Http\Controllers\NivelEntrenamientoController;
use App\Http\Controllers\NivelGravedadController;
use App\Http\Controllers\TipoIncidenteController;
use App\Http\Controllers\TipoRecursoController;
use App\Http\Controllers\CondicionClimaticaController;
use App\Http\Controllers\EstadoSistemaController;
use App\Http\Controllers\PerfilController;

Auth::routes();

// Ruta pública para reportes ciudadanos
Route::get('/reporte-publico', [ReporteController::class, 'formularioPublico'])->name('reporte.publico');
Route::post('/reporte-publico', [ReporteController::class, 'storePublico'])->name('reporte.publico.store');

// Rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {
    // Redirección raíz
    Route::get('/', function () {
        return redirect('/home');
    });

    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // ========== OPERACIONES ==========

    // Reportes Ciudadanos
    Route::resource('reportes', ReporteController::class);
    Route::patch('reportes/{reporte}/estado', [ReporteController::class, 'cambiarEstado'])->name('reportes.estado');

    // Reportes de Incendios
    Route::resource('reportes-incendio', ReporteIncendioController::class);
    Route::patch('reportes-incendio/{reporte}/controlar', [ReporteIncendioController::class, 'marcarControlado'])->name('reportes-incendio.controlar');

    // Focos de Calor
    Route::get('focos-calor', [FocoCalorController::class, 'index'])->name('focos-calor.index');
    Route::get('focos-calor/mapa', [FocoCalorController::class, 'mapa'])->name('focos-calor.mapa');
    Route::get('focos-calor/api', [FocoCalorController::class, 'api'])->name('focos-calor.api');

    // Recursos
    Route::resource('recursos', RecursoController::class);
    Route::patch('recursos/{recurso}/estado', [RecursoController::class, 'cambiarEstado'])->name('recursos.estado');

    // ========== GESTIÓN DE PERSONAL ==========

    // Usuarios
    Route::resource('usuarios', UsuarioController::class);
    Route::patch('usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
    Route::post('usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');

    // Equipos
    Route::resource('equipos', EquipoController::class);
    // Ruta para obtener equipos en el mapa del dashboard (autenticación web/sesión)
    Route::get('equipos/data/map', [EquipoController::class, 'api'])->name('equipos.map-data');
    Route::post('equipos/{equipo}/miembros', [EquipoController::class, 'agregarMiembro'])->name('equipos.agregar-miembro');
    Route::delete('equipos/{equipo}/miembros/{usuario}', [EquipoController::class, 'removerMiembro'])->name('equipos.remover-miembro');
    Route::post('equipos/{equipo}/comunarios', [EquipoController::class, 'agregarComunario'])->name('equipos.agregar-comunario');
    Route::delete('equipos/{equipo}/comunarios/{comunario}', [EquipoController::class, 'removerComunario'])->name('equipos.remover-comunario');

    // ========== INFORMACIÓN ==========

    // Noticias
    Route::resource('noticias', NoticiaController::class);

    // Cursos
    Route::resource('cursos', CursoController::class);
    Route::get('cursos/{curso}/asignar', [CursoController::class, 'asignar'])->name('cursos.asignar');
    Route::post('cursos/{curso}/asignar', [CursoController::class, 'storeAsignacion'])->name('cursos.store-asignacion');
    Route::delete('cursos/{curso}/asignacion/{asignacion}', [CursoController::class, 'removerAsignacion'])->name('cursos.remover-asignacion');
    Route::get('usuarios/{usuario}/cursos', [CursoController::class, 'cursosUsuario'])->name('usuarios.cursos');
    Route::get('comunarios/{comunario}/cursos', [CursoController::class, 'cursosComunario'])->name('comunarios.cursos');
    // Inscritos
    Route::post('cursos/{curso}/inscritos', [InscritoController::class, 'storeFromCurso'])->name('cursos.store-inscrito');

    // ========== CATÁLOGOS ==========

    // Roles
    Route::resource('roles', RoleController::class);

    // Géneros
    Route::resource('generos', GeneroController::class);

    // Tipos de Sangre
    Route::resource('tipos-sangre', TipoSangreController::class);

    // Niveles de Entrenamiento
    Route::resource('niveles-entrenamiento', NivelEntrenamientoController::class);

    // Niveles de Gravedad
    Route::resource('niveles-gravedad', NivelGravedadController::class);

    // Tipos de Incidente
    Route::resource('tipos-incidente', TipoIncidenteController::class);

    // Tipos de Recurso
    Route::resource('tipos-recurso', TipoRecursoController::class);

    // Condiciones Climáticas
    Route::resource('condiciones-climaticas', CondicionClimaticaController::class);

    // Estados del Sistema
    Route::resource('estados-sistema', EstadoSistemaController::class);

    // Inscritos
    Route::resource('inscritos', InscritoController::class);

    // ========== MI CUENTA ==========

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    // Cambiar Contraseña
    Route::get('/cambiar-password', [PerfilController::class, 'cambiarPassword'])->name('cambiar-password');
    Route::put('/cambiar-password', [PerfilController::class, 'updatePassword'])->name('cambiar-password.update');
});
