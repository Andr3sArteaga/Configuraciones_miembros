<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KardexController extends Controller
{
    /**
     * Mostrar el kardex del usuario autenticado
     */
    public function index()
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Cargar todas las relaciones necesarias
        $usuario->load([
            'genero',
            'tipos_sangre',
            'niveles_entrenamiento',
            'role',
            'estados_sistema',
            'miembros_equipos.equipo'
        ]);

        // Obtener información de equipos
        $equipos = $usuario->miembros_equipos->map(function ($miembro) {
            return $miembro->equipo;
        })->filter()->unique('id');

        // Obtener reportes de incendio creados
        $reportes = $usuario->reportes_incendios()->latest('fecha_creacion')->get();

        // Obtener cursos asignados al usuario específicamente
        $cursos = \App\Models\CursoAsignado::where('entidad_id', $usuario->id)
            ->where('entidad_tipo', 'usuario')
            ->with('curso')
            ->get();

        // Obtener movimientos de auditoría del usuario
        $movimientos = Movimiento::where('ci_usuario', $usuario->ci)
            ->orderBy('created_at', 'desc')
            ->limit(100) // Limitar a los últimos 100 movimientos en la vista
            ->get();

        // Agrupar movimientos por módulo para estadísticas
        $movimientosPorModulo = $movimientos->groupBy('modulo');

        // Calcular estadísticas
        $estadisticas = [
            'total_reportes' => $reportes->count(),
            'reportes_controlados' => $reportes->where('controlado', true)->count(),
            'reportes_pendientes' => $reportes->where('controlado', false)->count(),
            'total_equipos' => $equipos->count(),
            'total_cursos' => $cursos->count(),
            'total_movimientos' => $movimientos->count(),
        ];

        return view('kardex.index', compact('usuario', 'equipos', 'reportes', 'cursos', 'movimientos', 'movimientosPorModulo', 'estadisticas'));
    }

    /**
     * Descargar el kardex en formato PDF
     */
    public function descargarPdf()
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Cargar todas las relaciones necesarias
        $usuario->load([
            'genero',
            'tipos_sangre',
            'niveles_entrenamiento',
            'role',
            'estados_sistema',
            'miembros_equipos.equipo'
        ]);

        // Obtener información de equipos
        $equipos = $usuario->miembros_equipos->map(function ($miembro) {
            return $miembro->equipo;
        })->filter()->unique('id');

        // Obtener reportes de incendio creados
        $reportes = $usuario->reportes_incendios()->latest('fecha_creacion')->get();

        // Obtener cursos asignados al usuario específicamente
        $cursos = \App\Models\CursoAsignado::where('entidad_id', $usuario->id)
            ->where('entidad_tipo', 'usuario')
            ->with('curso')
            ->get();

        // Obtener TODOS los movimientos de auditoría del usuario para el PDF
        $movimientos = Movimiento::where('ci_usuario', $usuario->ci)
            ->orderBy('created_at', 'desc')
            ->get();

        // Agrupar movimientos por módulo
        $movimientosPorModulo = $movimientos->groupBy('modulo');

        // Calcular estadísticas
        $estadisticas = [
            'total_reportes' => $reportes->count(),
            'reportes_controlados' => $reportes->where('controlado', true)->count(),
            'reportes_pendientes' => $reportes->where('controlado', false)->count(),
            'total_equipos' => $equipos->count(),
            'total_cursos' => $cursos->count(),
            'total_movimientos' => $movimientos->count(),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kardex.pdf', compact('usuario', 'equipos', 'reportes', 'cursos', 'movimientos', 'movimientosPorModulo', 'estadisticas'));

        $nombreArchivo = 'kardex_' . $usuario->nombre . '_' . $usuario->apellido . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}
