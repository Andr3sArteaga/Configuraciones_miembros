<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistroSimpleController extends Controller
{
    /**
     * Consultar usuario por CI
     * 
     * Este endpoint está diseñado para ser consumido por un API Gateway central
     * que necesita verificar si un CI existe en el sistema de bomberos.
     * 
     * @param Request $request
     * @param string $ci
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByCi(Request $request, string $ci)
    {
        Log::info('Consulta registro por CI', [
            'ci' => $ci,
            'client_system' => $request->header('X-Client-System', 'unknown'),
            'ip' => $request->ip(),
        ]);

        $user = Usuario::where('ci', $ci)->first();

        return response()->json([
            'success' => true,
            'system' => 'bomberos',
            'ci' => $ci,
            'found' => $user ? true : false,
            'data' => $user ? [
                'ci' => $user->ci,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'telefono' => $user->telefono,
            ] : null,
        ], 200);
    }
}
