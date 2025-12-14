<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrazabilidadController extends Controller
{
    /**
     * Obtener trazabilidad de un voluntario por su CI
     * 
     * Este endpoint está diseñado para ser consumido por un API Gateway central
     * que necesita obtener el historial de acciones de un usuario en el sistema.
     * 
     * @param string $ci
     * @return \Illuminate\Http\JsonResponse
     */
    public function porVoluntario(string $ci)
    {
        Log::info('Consulta trazabilidad por CI', [
            'ci' => $ci,
        ]);

        // Obtener todos los movimientos del usuario por CI
        $movimientos = Movimiento::where('ci_usuario', $ci)
            ->orderBy('created_at', 'desc')
            ->get();

        // Agrupar por módulo
        $agrupados = $movimientos->groupBy('modulo');

        // Construir respuesta estructurada
        $response = [
            'success' => true,
            'ci_voluntario' => $ci,
            'total_movimientos' => $movimientos->count(),
        ];

        // Para cada módulo, crear un array con sus movimientos
        foreach ($agrupados as $modulo => $items) {
            $response[$modulo] = $items->map(function ($mov) {
                return [
                    'id' => $mov->id,
                    'accion' => $mov->accion,
                    'descripcion' => $mov->descripcion,
                    'fecha' => $mov->created_at->format('d-m-Y H:i:s'),
                    'entidad_tipo' => $mov->entidad_tipo,
                    'entidad_id' => $mov->entidad_id,
                    'metodo_http' => $mov->metodo_http,
                    'ruta' => $mov->ruta,
                ];
            })->toArray();
        }

        return response()->json($response, 200);
    }

    /**
     * Obtener trazabilidad completa (todos los movimientos)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Movimiento::query();

        // Filtros opcionales
        if ($request->has('ci')) {
            $query->porCi($request->ci);
        }

        if ($request->has('modulo')) {
            $query->porModulo($request->modulo);
        }

        if ($request->has('accion')) {
            $query->porAccion($request->accion);
        }

        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->entreFechas($request->fecha_inicio, $request->fecha_fin);
        }

        $movimientos = $query->recientes()
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $movimientos,
        ], 200);
    }
}
