<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class ReporteAnimalController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Animal Report Payload:', $request->all());
        
        // Validation
        $validated = $request->validate([
            'incendio_id' => 'required|string', // UUID string
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'direccion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'condicion_inicial_id' => 'required|numeric',
            'tipo_incidente_id' => 'required|numeric',
            'tamano' => 'required|string|in:pequeño,pequeno,mediano,grande',
            'puede_moverse' => 'required|boolean',
            'traslado_inmediato' => 'required|boolean',
            'centro_id' => 'nullable|numeric',
            'imagen' => 'required|file|image|max:10240', 
        ]);

        try {
            // Handle File Upload
            $path = '';
            if ($request->hasFile('imagen')) {
                // Store in 'public/animales' folder
                $path = $request->file('imagen')->store('animales', 'public');
            }

            // Create Record
            $reporte = \App\Models\ReporteAnimal::create([
                'incendio_id' => $validated['incendio_id'],
                'latitud' => $validated['latitud'],
                'longitud' => $validated['longitud'],
                'direccion' => $validated['direccion'],
                'observaciones' => $validated['observaciones'],
                'condicion_inicial_id' => $validated['condicion_inicial_id'],
                'tipo_incidente_id' => $validated['tipo_incidente_id'],
                'tamano' => $validated['tamano'],
                'puede_moverse' => filter_var($validated['puede_moverse'], FILTER_VALIDATE_BOOLEAN),
                'traslado_inmediato' => filter_var($validated['traslado_inmediato'], FILTER_VALIDATE_BOOLEAN),
                'centro_id' => $validated['centro_id'],
                'imagen_path' => $path,
            ]);

            return response()->json([
                'message' => 'Reporte de animal creado exitosamente',
                'data' => $reporte
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear reporte de animal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
