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
use App\Http\Controllers\KardexController;
use App\Http\Controllers\AdminCourseProgressController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ComunarioController;

Auth::routes();

// ========== RUTAS PÚBLICAS (Invitados) ==========

// Página de inicio para invitados - redirige al mapa
Route::get('/invitado', function () {
    return redirect()->route('focos-calor.index');
})->name('guest.home');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Reporte Rápido Público
Route::get('/reporte-publico', [ReporteController::class, 'formularioPublico'])->name('reporte.publico');
Route::post('/reporte-publico', [ReporteController::class, 'storePublico'])->name('reporte.publico.store');

// Mapa en Tiempo Real (Público - Sin equipos)
Route::get('/focos-calor', [FocoCalorController::class, 'index'])->name('focos-calor.index');
Route::get('/focos-calor/api', [FocoCalorController::class, 'api'])->name('focos-calor.api');

// Noticias (Solo lectura)
Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
Route::get('/noticias/{noticia}', [NoticiaController::class, 'show'])->name('noticias.show');

// Cursos (Solo lectura - info básica)
Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
Route::get('/cursos/{curso}', [CursoController::class, 'show'])->name('cursos.show');

// ========== RUTAS AUTENTICADAS (Usuarios + Admin) ==========

Route::middleware(['auth'])->group(function () {
    // Redirección raíz
    Route::get('/', function () {
        return redirect('/home');
    });

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Perfil y Contraseña (Todos los usuarios autenticados)
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/cambiar-password', [PerfilController::class, 'cambiarPassword'])->name('cambiar-password');
    Route::put('/cambiar-password', [PerfilController::class, 'updatePassword'])->name('cambiar-password.update');

    // Kardex del Usuario
    Route::get('/kardex', [KardexController::class, 'index'])->name('kardex.index');
    Route::get('/kardex/pdf', [KardexController::class, 'descargarPdf'])->name('kardex.pdf');

    // Reportes (Todos los usuarios autenticados pueden ver todos los reportes ciudadanos)
    Route::resource('reportes', ReporteController::class);
    Route::patch('reportes/{reporte}/estado', [ReporteController::class, 'cambiarEstado'])->name('reportes.estado');

    // Reportes de Incendio (Usuarios ven solo los suyos, Admin ve todos - lógica en controlador)
    Route::resource('reportes-incendio', ReporteIncendioController::class);
    Route::patch('reportes-incendio/{reporte}/controlar', [ReporteIncendioController::class, 'marcarControlado'])->name('reportes-incendio.controlar');

    // Equipos (Usuarios ven solo su equipo - lógica en controlador)
    Route::get('equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');
    Route::get('equipos/data/map', [EquipoController::class, 'api'])->name('equipos.map-data');

    // Inscripción a Cursos (Usuarios/Voluntarios pueden inscribirse)
    Route::post('cursos/{curso}/inscribirme', [CursoController::class, 'inscribirme'])->name('cursos.inscribirme');

    // Marcar etapa como completada
    Route::post('cursos/{curso}/stages/{stage}/complete', [CursoController::class, 'markStageComplete'])->name('cursos.stages.complete');

    // Notificaciones
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

// ========== RUTAS SOLO ADMIN ==========

Route::middleware(['auth', 'role:admin'])->group(function () {

    // ========== OPERACIONES ==========

    // Recursos (Solo Admin)
    Route::resource('recursos', RecursoController::class);
    Route::patch('recursos/{recurso}/estado', [RecursoController::class, 'cambiarEstado'])->name('recursos.estado');

    // ========== GESTIÓN DE PERSONAL ==========

    // Usuarios (Solo Admin)
    Route::resource('usuarios', UsuarioController::class);
    Route::patch('usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
    Route::post('usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');

    // Equipos - CRUD completo para Admin
    Route::post('equipos', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('equipos/create', [EquipoController::class, 'create'])->name('equipos.create');
    Route::get('equipos/{equipo}/edit', [EquipoController::class, 'edit'])->name('equipos.edit');
    Route::put('equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::post('equipos/{equipo}/miembros', [EquipoController::class, 'agregarMiembro'])->name('equipos.agregar-miembro');
    Route::delete('equipos/{equipo}/miembros/{usuario}', [EquipoController::class, 'removerMiembro'])->name('equipos.remover-miembro');
    Route::post('equipos/{equipo}/comunarios', [EquipoController::class, 'agregarComunario'])->name('equipos.agregar-comunario');
    Route::delete('equipos/{equipo}/comunarios/{comunarioId}', [EquipoController::class, 'removerComunario'])->name('equipos.remover-comunario');

    // Comunarios (Solo Admin)
    Route::resource('comunarios', ComunarioController::class);

    // ========== INFORMACIÓN ==========

    // Noticias - CRUD completo para Admin
    Route::post('noticias', [NoticiaController::class, 'store'])->name('noticias.store');
    Route::get('noticias/create', [NoticiaController::class, 'create'])->name('noticias.create');
    Route::get('noticias/{noticia}/edit', [NoticiaController::class, 'edit'])->name('noticias.edit');
    Route::put('noticias/{noticia}', [NoticiaController::class, 'update'])->name('noticias.update');
    Route::delete('noticias/{noticia}', [NoticiaController::class, 'destroy'])->name('noticias.destroy');

    // Cursos - CRUD completo para Admin
    Route::post('cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('cursos/create', [CursoController::class, 'create'])->name('cursos.create');
    Route::get('cursos/{curso}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('cursos/{curso}', [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('cursos/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    Route::get('cursos/{curso}/asignar', [CursoController::class, 'asignar'])->name('cursos.asignar');
    Route::post('cursos/{curso}/asignar', [CursoController::class, 'storeAsignacion'])->name('cursos.store-asignacion');
    Route::delete('cursos/{curso}/asignacion/{asignacion}', [CursoController::class, 'removerAsignacion'])->name('cursos.remover-asignacion');
    Route::get('usuarios/{usuario}/cursos', [CursoController::class, 'cursosUsuario'])->name('usuarios.cursos');
    Route::get('comunarios/{comunario}/cursos', [CursoController::class, 'cursosComunario'])->name('comunarios.cursos');

    // Admin: Course Progress Management
    Route::get('admin/course-progress', [AdminCourseProgressController::class, 'index'])->name('admin.course-progress.index');
    Route::get('admin/course-progress/{curso}', [AdminCourseProgressController::class, 'show'])->name('admin.course-progress.show');
    Route::post('admin/course-progress/{progress}/approve', [AdminCourseProgressController::class, 'approve'])->name('admin.course-progress.approve');

    // Inscritos - CRUD para Admin
    Route::resource('inscritos', InscritoController::class);
    Route::post('cursos/{curso}/inscritos', [InscritoController::class, 'storeFromCurso'])->name('cursos.store-inscrito');

    // ========== CATÁLOGOS (Solo Admin) ==========

    Route::resource('roles', RoleController::class);
    Route::resource('generos', GeneroController::class);
    Route::resource('tipos-sangre', TipoSangreController::class);
    Route::resource('niveles-entrenamiento', NivelEntrenamientoController::class);
    Route::resource('niveles-gravedad', NivelGravedadController::class);
    Route::resource('tipos-incidente', TipoIncidenteController::class);
    Route::resource('tipos-recurso', TipoRecursoController::class);
    Route::resource('condiciones-climaticas', CondicionClimaticaController::class);
    Route::resource('estados-sistema', EstadoSistemaController::class);
});
