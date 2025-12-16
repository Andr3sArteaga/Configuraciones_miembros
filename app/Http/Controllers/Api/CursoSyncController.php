<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoSyncController extends Controller
{
    // GET /api/cursos/search?nombre=XYZ
    public function search(Request $request)
    {
        $nombre = $request->query('nombre');

        if (!$nombre) {
            return response()->json([
                'error' => 'Debe enviar el parámetro nombre'
            ], 400);
        }

        $curso = Curso::where('nombre', $nombre)->first();

        return response()->json([
            'exists' => $curso ? true : false,
            'data'   => $curso
        ]);
    }

    public function syncStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        // Buscar si ya existe
        $curso = Curso::where('nombre', $request->nombre)->first();

        if ($curso) {
            $curso->update([
                'descripcion' => $request->descripcion ?? $curso->descripcion
            ]);
        } else {
            $curso = Curso::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $curso
        ]);
    }
}

