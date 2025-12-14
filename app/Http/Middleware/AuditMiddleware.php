<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Movimiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    /**
     * Campos sensibles que NO deben guardarse en auditoría
     */
    protected array $camposSensibles = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'token',
        'api_token',
        'reset_token',
        '_token',
        'secret',
    ];

    /**
     * Rutas que deben excluirse de la auditoría
     */
    protected array $rutasExcluidas = [
        'login',
        'logout',
        'register',
        'password',
        'sanctum',
        'api/registro/ci',
        'api/trazabilidad',
        '_debugbar',
        'livewire',
        'broadcasting',
        'storage',
    ];

    /**
     * Mapeo de rutas a módulos
     */
    protected array $mapaModulos = [
        'equipos' => 'equipos',
        'usuarios' => 'usuarios',
        'cursos' => 'cursos',
        'reportes' => 'reportes',
        'reportes-incendio' => 'emergencias',
        'emergencias' => 'emergencias',
        'vehiculos' => 'vehiculos',
        'recursos' => 'recursos',
        'noticias' => 'noticias',
        'perfil' => 'perfil',
        'asistencia' => 'asistencia',
        'focos-calor' => 'focos_calor',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Verificar si debe auditar esta request
        if (!$this->shouldAudit($request, $response)) {
            return;
        }

        try {
            $user = Auth::user();

            Movimiento::create([
                'usuario_id' => $user?->id,
                'ci_usuario' => $user?->ci,
                'accion' => $this->determinarAccion($request),
                'modulo' => $this->determinarModulo($request),
                'entidad_tipo' => $this->determinarEntidadTipo($request),
                'entidad_id' => $this->extraerEntidadId($request),
                'descripcion' => $this->generarDescripcion($request, $user),
                'datos_anteriores' => $this->obtenerDatosAnteriores($request),
                'datos_nuevos' => $this->obtenerDatosNuevos($request),
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit($request->userAgent(), 500),
                'metodo_http' => $request->method(),
                'ruta' => $request->path(),
            ]);
        } catch (\Exception $e) {
            // Log error pero no fallar la request
            Log::error('Error al guardar movimiento de auditoría', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'path' => $request->path(),
                'method' => $request->method(),
            ]);
        }
    }

    /**
     * Determinar si se debe auditar esta request
     */
    protected function shouldAudit(Request $request, Response $response): bool
    {
        // Solo auditar POST, PUT, PATCH, DELETE
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }

        // No auditar respuestas de error de cliente (4xx) excepto 422 (validation)
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400 && $statusCode < 500 && $statusCode !== 422) {
            return false;
        }

        // No auditar errores de servidor (5xx)
        if ($statusCode >= 500) {
            return false;
        }

        // Excluir rutas específicas
        $path = $request->path();
        foreach ($this->rutasExcluidas as $ruta) {
            if (Str::contains($path, $ruta)) {
                return false;
            }
        }

        // Excluir requests AJAX de assets
        if (Str::startsWith($path, ['css/', 'js/', 'images/', 'fonts/', 'vendor/'])) {
            return false;
        }

        return true;
    }

    /**
     * Determinar la acción basándose en el método HTTP y la ruta
     */
    protected function determinarAccion(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        // Acciones especiales basadas en la ruta
        if (Str::contains($path, '/aprobar')) {
            return 'aprobar';
        }
        if (Str::contains($path, '/rechazar')) {
            return 'rechazar';
        }
        if (Str::contains($path, '/activar')) {
            return 'activar';
        }
        if (Str::contains($path, '/desactivar')) {
            return 'desactivar';
        }
        if (Str::contains($path, '/asignar')) {
            return 'asignar';
        }
        if (Str::contains($path, '/desplegar')) {
            return 'desplegar';
        }
        if (Str::contains($path, '/finalizar')) {
            return 'finalizar';
        }

        // Acciones estándar por método HTTP
        return match ($method) {
            'POST' => 'crear',
            'PUT', 'PATCH' => 'editar',
            'DELETE' => 'eliminar',
            default => strtolower($method),
        };
    }

    /**
     * Determinar el módulo basándose en la ruta
     */
    protected function determinarModulo(Request $request): string
    {
        $path = $request->path();

        // Buscar en el mapa de módulos
        foreach ($this->mapaModulos as $patron => $modulo) {
            if (Str::contains($path, $patron)) {
                return $modulo;
            }
        }

        // Extraer el primer segmento de la ruta como módulo
        $segmentos = explode('/', $path);
        $primerSegmento = $segmentos[0] ?? 'general';

        // Limpiar prefijos comunes
        if (in_array($primerSegmento, ['api', 'admin', 'v1'])) {
            $primerSegmento = $segmentos[1] ?? 'general';
        }

        return Str::slug($primerSegmento, '_') ?: 'general';
    }

    /**
     * Determinar el tipo de entidad basándose en la ruta
     */
    protected function determinarEntidadTipo(Request $request): ?string
    {
        $modulo = $this->determinarModulo($request);

        $mapaEntidades = [
            'equipos' => 'Equipo',
            'usuarios' => 'Usuario',
            'cursos' => 'Curso',
            'reportes' => 'Reporte',
            'emergencias' => 'ReportesIncendio',
            'vehiculos' => 'Vehiculo',
            'recursos' => 'Recurso',
            'noticias' => 'NoticiasIncendio',
            'perfil' => 'Usuario',
            'asistencia' => 'Asistencia',
        ];

        return $mapaEntidades[$modulo] ?? null;
    }

    /**
     * Extraer el ID de la entidad de la ruta (UUID)
     */
    protected function extraerEntidadId(Request $request): ?string
    {
        $path = $request->path();
        $segmentos = explode('/', $path);

        // Buscar un UUID en los segmentos de la ruta
        foreach ($segmentos as $segmento) {
            if ($this->esUuid($segmento)) {
                return $segmento;
            }
        }

        // Si es un POST (crear), intentar obtener el ID de la respuesta o request
        if ($request->method() === 'POST') {
            $id = $request->input('id');
            if ($id && $this->esUuid($id)) {
                return $id;
            }
        }

        return null;
    }

    /**
     * Verificar si una cadena es un UUID válido
     */
    protected function esUuid(string $valor): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $valor);
    }

    /**
     * Generar descripción legible de la acción
     */
    protected function generarDescripcion(Request $request, $user): string
    {
        $accion = $this->determinarAccion($request);
        $modulo = $this->determinarModulo($request);
        $nombreUsuario = $user ? "{$user->nombre} {$user->apellido}" : 'Usuario anónimo';

        // Obtener nombre/identificador del registro afectado
        $nombreEntidad = $this->obtenerNombreEntidad($request);

        $accionesTexto = [
            'crear' => 'Creó',
            'editar' => 'Editó',
            'eliminar' => 'Eliminó',
            'aprobar' => 'Aprobó',
            'rechazar' => 'Rechazó',
            'activar' => 'Activó',
            'desactivar' => 'Desactivó',
            'asignar' => 'Asignó',
            'desplegar' => 'Desplegó',
            'finalizar' => 'Finalizó',
        ];

        $modulosTexto = [
            'equipos' => 'equipo',
            'usuarios' => 'usuario',
            'cursos' => 'curso',
            'reportes' => 'reporte',
            'emergencias' => 'emergencia',
            'vehiculos' => 'vehículo',
            'recursos' => 'recurso',
            'noticias' => 'noticia',
            'perfil' => 'perfil personal',
            'asistencia' => 'asistencia',
        ];

        $textoAccion = $accionesTexto[$accion] ?? ucfirst($accion);
        $textoModulo = $modulosTexto[$modulo] ?? $modulo;

        if ($modulo === 'perfil') {
            return "{$nombreUsuario} actualizó su perfil personal";
        }

        if ($nombreEntidad) {
            return "{$textoAccion} {$textoModulo}: {$nombreEntidad}";
        }

        return "{$textoAccion} un registro en {$textoModulo}";
    }

    /**
     * Obtener nombre o identificador de la entidad
     */
    protected function obtenerNombreEntidad(Request $request): ?string
    {
        $datos = $request->all();

        // Buscar campos comunes de identificación
        $camposIdentificacion = ['nombre', 'titulo', 'name', 'title', 'codigo', 'descripcion'];

        foreach ($camposIdentificacion as $campo) {
            if (!empty($datos[$campo])) {
                return Str::limit($datos[$campo], 50);
            }
        }

        // Para usuarios
        if (!empty($datos['nombre']) && !empty($datos['apellido'])) {
            return "{$datos['nombre']} {$datos['apellido']}";
        }

        return null;
    }

    /**
     * Obtener datos anteriores (para edición/eliminación)
     */
    protected function obtenerDatosAnteriores(Request $request): ?array
    {
        // Por ahora retornamos null - idealmente se obtendría del modelo antes de actualizar
        // Esto requeriría integración con los controladores o usar model events
        return null;
    }

    /**
     * Obtener datos nuevos (filtrados de campos sensibles)
     */
    protected function obtenerDatosNuevos(Request $request): ?array
    {
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            return null;
        }

        $datos = $request->except($this->camposSensibles);

        // Filtrar campos vacíos y archivos
        $datos = array_filter($datos, function ($valor, $clave) {
            // Excluir archivos
            if ($valor instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            // Excluir campos que terminan en _confirmation
            if (Str::endsWith($clave, '_confirmation')) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        return !empty($datos) ? $datos : null;
    }
}
