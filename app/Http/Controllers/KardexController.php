<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KardexController extends Controller
{
    /**
     * Mostrar el kardex del usuario autenticado
     */
    public function index()
    {
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
        
        // Calcular estadísticas
        $estadisticas = [
            'total_reportes' => $reportes->count(),
            'reportes_controlados' => $reportes->where('controlado', true)->count(),
            'reportes_pendientes' => $reportes->where('controlado', false)->count(),
            'total_equipos' => $equipos->count(),
            'total_cursos' => $cursos->count(),
        ];
        
        return view('kardex.index', compact('usuario', 'equipos', 'reportes', 'cursos', 'estadisticas'));
    }

    /**
     * Descargar el kardex en formato PDF
     */
    public function descargarPdf()
    {
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
        
        // Calcular estadísticas
        $estadisticas = [
            'total_reportes' => $reportes->count(),
            'reportes_controlados' => $reportes->where('controlado', true)->count(),
            'reportes_pendientes' => $reportes->where('controlado', false)->count(),
            'total_equipos' => $equipos->count(),
            'total_cursos' => $cursos->count(),
        ];
        
        $pdf = \PDF::loadView('kardex.pdf', compact('usuario', 'equipos', 'reportes', 'cursos', 'estadisticas'));
        
        $nombreArchivo = 'kardex_' . $usuario->nombre . '_' . $usuario->apellido . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }
}
