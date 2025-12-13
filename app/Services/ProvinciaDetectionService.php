<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProvinciaDetectionService
{
    /**
     * Detect provincia from coordinates using reverse geocoding
     * 
     * @param float $latitud
     * @param float $longitud
     * @return string
     */
    public static function detectFromCoordinates($latitud, $longitud)
    {
        try {
            // Use Nominatim (OpenStreetMap) reverse geocoding
            $response = Http::timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $latitud,
                'lon' => $longitud,
                'format' => 'json',
                'addressdetails' => 1,
                'accept-language' => 'es'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $address = $data['address'] ?? [];
                
                // Try to get provincia/county/state
                $provincia = $address['county'] 
                    ?? $address['state'] 
                    ?? $address['province']
                    ?? 'Santa Cruz de la Sierra';
                
                Log::info('Provincia detected from coordinates', [
                    'lat' => $latitud,
                    'lng' => $longitud,
                    'provincia' => $provincia
                ]);
                
                return $provincia;
            }
        } catch (\Exception $e) {
            Log::error('Provincia detection failed', [
                'lat' => $latitud,
                'lng' => $longitud,
                'error' => $e->getMessage()
            ]);
        }
        
        // Fallback to default
        return 'Santa Cruz de la Sierra';
    }
}
