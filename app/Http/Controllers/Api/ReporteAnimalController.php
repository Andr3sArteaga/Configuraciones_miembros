<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

class ReporteAnimalController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Animal Report Payload:', $request->all());
        
        // Validation
        $validated = $request->validate([
            'incendio_id' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'direccion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'condicion_inicial_id' => 'required|numeric',
            'tipo_incidente_id' => 'required|numeric',
            'tamano' => 'required|string|in:pequeno,mediano,grande',
            'puede_moverse' => 'required',
            'traslado_inmediato' => 'required',
            'centro_id' => 'nullable|numeric',
            'imagen' => 'nullable|file|image|max:10240', 
        ]);

        try {
            // Handle File Upload
            $path = null;
            if ($request->hasFile('imagen') && $request->file('imagen')) {
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
                'puede_moverse' => $validated['puede_moverse'] === 'si' || $validated['puede_moverse'] === true || $validated['puede_moverse'] === 'true',
                'traslado_inmediato' => $validated['traslado_inmediato'] === true || $validated['traslado_inmediato'] === 'true' || $validated['traslado_inmediato'] === 1,
                'centro_id' => $validated['centro_id'] ?? null,
                'imagen_path' => $path,
            ]);

            // Enviar a microservicio externo
            try {
                // Determine boolean values
                $puedeMoverse = $validated['puede_moverse'] === 'si' || $validated['puede_moverse'] === true || $validated['puede_moverse'] === 'true' || $validated['puede_moverse'] === 1 || $validated['puede_moverse'] === '1';
                $trasladoInmediato = $validated['traslado_inmediato'] === true || $validated['traslado_inmediato'] === 'true' || $validated['traslado_inmediato'] === 1 || $validated['traslado_inmediato'] === '1';

                // Map fields - Handle direccion vs nombre_lugar mismatch
                $direccion = $request->input('direccion') 
                            ?? $request->input('nombre_lugar') 
                            ?? $validated['direccion'] 
                            ?? "";

                $http = Http::timeout(15);
                
                // Attach image if exists
                if ($request->hasFile('imagen')) {
                    $file = $request->file('imagen');
                    $http->attach(
                        'imagen', 
                        file_get_contents($file->getRealPath()), 
                        $file->getClientOriginalName()
                    );
                }

                $externalPayload = [
                    "incendio_id" => null, 
                    "latitud" => (string)$validated['latitud'], 
                    "longitud" => (string)$validated['longitud'],
                    "direccion" => $direccion,
                    "observaciones" => $validated['observaciones'] ?? "",
                    "condicion_inicial_id" => (int)$validated['condicion_inicial_id'],
                    "tipo_incidente_id" => (int)$validated['tipo_incidente_id'],
                    "tamano" => $validated['tamano'],
                    "puede_moverse" => $puedeMoverse ? '1' : '0',
                    "traslado_inmediato" => $trasladoInmediato ? '1' : '0',
                    "centro_id" => isset($validated['centro_id']) ? (int)$validated['centro_id'] : null
                ];

                Log::info('Sending to External API (Multipart):', $externalPayload);

                $response = $http->post(config('services.microservices.animal_reports.base_url') . '/api/reports', $externalPayload);

                if ($response->successful()) {
                    Log::info('External API Success: ' . $response->status());
                } else {
                    Log::error('External API Failed: ' . $response->status() . ' - ' . $response->body());
                }

            } catch (\Exception $ex) {
                // No interrumpir el flujo si falla el servicio externo
                Log::error('External API Connection Error: ' . $ex->getMessage());
            }

            return response()->json([
                'message' => 'Reporte de animal creado exitosamente',
                'data' => $reporte
            ], 201);

        } catch (\Exception $e) {
            Log::error('Animal Report Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e
            ]);
            
            return response()->json([
                'message' => 'Error al crear reporte de animal',
                'error' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
