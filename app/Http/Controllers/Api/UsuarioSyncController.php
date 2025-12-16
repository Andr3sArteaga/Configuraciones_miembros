<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioSyncController extends Controller
{
    // GET /api/usuarios/ci/12345
    public function buscarPorCi($ci)
    {
        $user = Usuario::where('ci', $ci)->first();

        return response()->json([
            'exists' => $user ? true : false,
            'data'   => $user
        ]);
    }

    // PUT /api/usuarios/{id}/estado
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:activo,inactivo'
        ]);

        $user = Usuario::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'Usuario no encontrado'
            ], 404);
        }

        $user->estado = $request->estado;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado',
            'data'    => $user
        ]);
    }
}
