<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\FocosCalor;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FocoCalorController extends Controller
{
    public function index()
    {
        $focos = FocosCalor::orderBy('acq_date', 'desc')->paginate(20);
        $countEquiposDesplegados = Equipo::whereNotNull('reporte_id')
            ->count();

        // Obtener reportes con ubicación para mostrar en el mapa
        $reportes = Reporte::with(['tipos_incidente', 'niveles_gravedad', 'estados_sistema'])
            ->whereNotNull('ubicacion')
            ->orderBy('fecha_hora', 'desc')
            ->limit(100)
            ->get();

        return view('focos-calor.index', compact('focos', 'countEquiposDesplegados', 'reportes'));
    }

    public function mapa()
    {
        $focos = FocosCalor::orderBy('acq_date', 'desc')->limit(100)->get();
        return view('focos-calor.mapa', compact('focos'));
    }

    /**
     * API endpoint para obtener focos de calor (NASA FIRMS hotspots)
     * 
     * Query Parameters:
     * - days: Número de días hacia atrás (default: 7, max: 30)
     * - min_lat, max_lat, min_lng, max_lng: Bounding box para filtrar por área
     * - min_confidence: Confianza mínima (0-100 o 'l', 'n', 'h')
     * - format: 'json' o 'geojson' (default: json)
     * - per_page: Resultados por página (default: 100, max: 1000)
     * - page: Número de página
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api(Request $request)
    {
        // Validar parámetros
        $validated = $request->validate([
            'days' => 'nullable|integer|min:1|max:30',
            'min_lat' => 'nullable|numeric|min:-90|max:90',
            'max_lat' => 'nullable|numeric|min:-90|max:90',
            'min_lng' => 'nullable|numeric|min:-180|max:180',
            'max_lng' => 'nullable|numeric|min:-180|max:180',
            'min_confidence' => 'nullable',
            'format' => 'nullable|in:json,geojson',
            'per_page' => 'nullable|integer|min:1|max:1000',
            'page' => 'nullable|integer|min:1',
        ]);

        // Construir query
        $query = FocosCalor::query();

        // Filtrar por fecha (últimos N días)
        $days = $request->input('days', 7);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $query->where('acq_date', '>=', $startDate);

        // Filtrar por bounding box (área geográfica)
        if ($request->has('min_lat')) {
            $query->where('latitude', '>=', $request->input('min_lat'));
        }
        if ($request->has('max_lat')) {
            $query->where('latitude', '<=', $request->input('max_lat'));
        }
        if ($request->has('min_lng')) {
            $query->where('longitude', '>=', $request->input('min_lng'));
        }
        if ($request->has('max_lng')) {
            $query->where('longitude', '<=', $request->input('max_lng'));
        }

        // Filtrar por confianza mínima
        if ($request->has('min_confidence')) {
            $minConfidence = $request->input('min_confidence');

            // Convertir letras a números si es necesario
            if (in_array(strtolower($minConfidence), ['l', 'n', 'h'])) {
                $confidenceMap = ['l' => 0, 'n' => 50, 'h' => 80];
                $minConfidence = $confidenceMap[strtolower($minConfidence)];
            }

            // Filtrar por confianza numérica o letra
            $query->where(function ($q) use ($minConfidence) {
                $q->where('confidence', '>=', $minConfidence)
                    ->orWhere('confidence', '=', 'h')
                    ->when($minConfidence <= 50, function ($query) {
                        $query->orWhere('confidence', '=', 'n');
                    })
                    ->when($minConfidence <= 0, function ($query) {
                        $query->orWhere('confidence', '=', 'l');
                    });
            });
        }

        // Ordenar por fecha más reciente
        $query->orderBy('acq_date', 'desc')
            ->orderBy('acq_time', 'desc');

        // Paginación
        $perPage = min($request->input('per_page', 100), 1000);
        $focos = $query->paginate($perPage);

        // Formato de respuesta
        $format = $request->input('format', 'json');

        if ($format === 'geojson') {
            return $this->toGeoJSON($focos);
        }

        // Respuesta JSON estándar con metadata
        return response()->json([
            'success' => true,
            'data' => $focos->items(),
            'meta' => [
                'total' => $focos->total(),
                'per_page' => $focos->perPage(),
                'current_page' => $focos->currentPage(),
                'last_page' => $focos->lastPage(),
                'from' => $focos->firstItem(),
                'to' => $focos->lastItem(),
            ],
            'filters' => [
                'days' => $days,
                'date_from' => $startDate->toDateString(),
                'bounding_box' => [
                    'min_lat' => $request->input('min_lat'),
                    'max_lat' => $request->input('max_lat'),
                    'min_lng' => $request->input('min_lng'),
                    'max_lng' => $request->input('max_lng'),
                ],
                'min_confidence' => $request->input('min_confidence'),
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Convertir focos de calor a formato GeoJSON
     * 
     * @param \Illuminate\Pagination\LengthAwarePaginator $focos
     * @return \Illuminate\Http\JsonResponse
     */
    private function toGeoJSON($focos)
    {
        $features = collect($focos->items())->map(function ($foco) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $foco->longitude,
                        (float) $foco->latitude,
                    ],
                ],
                'properties' => [
                    'id' => $foco->id,
                    'confidence' => $foco->confidence,
                    'acq_date' => $foco->acq_date->toDateString(),
                    'acq_time' => $foco->acq_time,
                    'bright_ti4' => $foco->bright_ti4,
                    'bright_ti5' => $foco->bright_ti5,
                    'frp' => $foco->frp,
                    'created_at' => $foco->creado?->toIso8601String(),
                ],
            ];
        });


        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
            'meta' => [
                'total' => $focos->total(),
                'per_page' => $focos->perPage(),
                'current_page' => $focos->currentPage(),
                'last_page' => $focos->lastPage(),
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * API endpoint para estadísticas de focos de calor
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $days = $request->input('days', 7);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $query = FocosCalor::where('acq_date', '>=', $startDate);

        $stats = [
            'total_hotspots' => $query->count(),
            'high_confidence' => (clone $query)->where(function ($q) {
                $q->where('confidence', '=', 'h')
                    ->orWhere('confidence', '>=', 80);
            })->count(),
            'medium_confidence' => (clone $query)->where(function ($q) {
                $q->where('confidence', '=', 'n')
                    ->orWhereBetween('confidence', [50, 79]);
            })->count(),
            'low_confidence' => (clone $query)->where(function ($q) {
                $q->where('confidence', '=', 'l')
                    ->orWhere('confidence', '<', 50);
            })->count(),
            'avg_frp' => round((clone $query)->avg('frp'), 2),
            'max_frp' => (clone $query)->max('frp'),
            'period' => [
                'days' => $days,
                'from' => $startDate->toDateString(),
                'to' => Carbon::now()->toDateString(),
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Live NASA FIRMS data (proxied from NASA API)
     * Fetches real-time hotspot data for Bolivia
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Live NASA FIRMS data (proxied from NASA API)
     * Fetches real-time hotspot data for Bolivia and returns a normalized structure.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function live(Request $request)
    {
        $validated = $request->validate([
            'days' => 'nullable|integer|min:1|max:10',
        ]);

        $days = $request->input('days', 2);

        // NASA FIRMS API configuration
        $NASA_API_KEY = '1ae0346a287432156ada4abb791d57cd';
        $NASA_API_BASE = 'https://firms.modaps.eosdis.nasa.gov/api/area/csv';

        // Bolivia bounding box
        $BOLIVIA_BOUNDS = [
            'min_lat' => -22.9,
            'max_lat' => -9.7,
            'min_lng' => -69.6,
            'max_lng' => -57.5,
        ];

        try {
            // Fetch from NASA FIRMS (VIIRS_NOAA21_NRT)
            // https://firms.modaps.eosdis.nasa.gov/api/area/csv/YOUR_KEY/VIIRS_NOAA21_NRT/minLon,minLat,maxLon,maxLat/days
            $url = sprintf(
                '%s/%s/VIIRS_NOAA21_NRT/%s,%s,%s,%s/%s',
                $NASA_API_BASE,
                $NASA_API_KEY,
                $BOLIVIA_BOUNDS['min_lng'], // API expects West, South, East, North (Lon, Lat, Lon, Lat)
                $BOLIVIA_BOUNDS['min_lat'],
                $BOLIVIA_BOUNDS['max_lng'],
                $BOLIVIA_BOUNDS['max_lat'],
                $days
            );

            // Using GET request with timeout
            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($url);

            if (!$response->successful()) {
                \Illuminate\Support\Facades\Log::error('NASA FIRMS API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to fetch data from satellite provider (NASA FIRMS)',
                ], 502); // Bad Gateway
            }

            $csvData = $response->body();
            $lines = explode("\n", trim($csvData));

            $normalizedData = [];

            // Ensure we have data lines (header + at least one row)
            if (count($lines) > 1) {
                $headers = str_getcsv($lines[0]);
                
                // Process each line
                for ($i = 1; $i < count($lines); $i++) {
                    if (empty(trim($lines[$i]))) continue;

                    $values = str_getcsv($lines[$i]);
                    
                    // Skip if column count mismatch
                    if (count($values) !== count($headers)) continue;

                    $row = array_combine($headers, $values);

                    // Normalize and Structure the Data
                    $normalizedData[] = [
                        'latitude' => (float) $row['latitude'],
                        'longitude' => (float) $row['longitude'],
                        'brightness' => isset($row['bright_ti4']) ? (float) $row['bright_ti4'] : null, // Temperature in Kelvin
                        'confidence' => $row['confidence'] ?? 'n', // n: nominal, h: high, l: low
                        'date' => \Carbon\Carbon::parse($row['acq_date'])->format('d/m/Y'), // dd/mm/yyyy
                        'time' => $row['acq_time'], // HHMM (UTC)
                        'satellite' => $row['satellite'] ?? 'VIIRS',
                        'frp' => isset($row['frp']) ? (float) $row['frp'] : null, // Fire Radiative Power (MW)
                        'source' => 'NASA FIRMS',
                        'type' => 'hotspot'
                    ];
                }
            }

            // Return clean JSON response
            return response()->json([
                'success' => true,
                'data' => $normalizedData,
                'meta' => [
                    'count' => count($normalizedData),
                    'days' => $days,
                    'region' => 'Bolivia',
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exception in FIRMS API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Internal Server Error while processing hotspot data',
            ], 500);
        }
    }
}
