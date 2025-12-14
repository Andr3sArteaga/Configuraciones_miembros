<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\TiposIncidente;
use App\Models\NivelesGravedad;
use App\Models\EstadosSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Reportes ciudadanos no están vinculados a usuarios específicos
        // Todos los usuarios autenticados pueden ver todos los reportes
        $reportes = Reporte::orderBy('fecha_hora', 'desc')
            ->paginate(20);

        // Cargar manualmente todas las relaciones para evitar problemas con UUID en whereIn
        $tiposIncidente = TiposIncidente::all()->keyBy('id');
        $nivelesGravedad = NivelesGravedad::all()->keyBy('id');
        $estadosSistema = EstadosSistema::all()->keyBy('id');

        // Asociar relaciones a cada reporte
        foreach ($reportes as $reporte) {
            if ($reporte->tipo_incidente_id && isset($tiposIncidente[$reporte->tipo_incidente_id])) {
                $reporte->setRelation('tipos_incidente', $tiposIncidente[$reporte->tipo_incidente_id]);
            }
            if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
                $reporte->setRelation('niveles_gravedad', $nivelesGravedad[$reporte->gravedad_id]);
            }
            if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
                $reporte->setRelation('estados_sistema', $estadosSistema[$reporte->estado_id]);
            }
        }

        return view('reportes.index', compact('reportes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposIncidente = TiposIncidente::where('activo', true)->orderBy('nombre')->get();
        $nivelesGravedad = NivelesGravedad::where('activo', true)->orderBy('orden')->get();

        return view('reportes.create', compact('tiposIncidente', 'nivelesGravedad'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_reportante' => 'required|string|max:200',
            'telefono_contacto' => 'nullable|string|max:20',
            'fecha_hora' => 'required|date',
            'nombre_lugar' => 'nullable|string|max:200',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'tipo_incidente_id' => 'nullable|uuid|exists:tipos_incidente,id',
            'gravedad_id' => 'nullable|uuid|exists:niveles_gravedad,id',
            'comentario_adicional' => 'nullable|string',
            'cant_bomberos' => 'nullable|integer|min:0',
            'cant_paramedicos' => 'nullable|integer|min:0',
            'cant_veterinarios' => 'nullable|integer|min:0',
            'cant_autoridades' => 'nullable|integer|min:0',
        ]);

        // Obtener estado "pendiente" para reportes
        $estadoPendiente = EstadosSistema::where('tabla', 'reportes')
            ->where('codigo', 'pendiente')
            ->first();

        // Construir el punto geográfico si hay coordenadas
        $ubicacion = null;
        if ($request->latitud && $request->longitud) {
            $ubicacion = "POINT({$request->longitud} {$request->latitud})";
        }

        $reporte = Reporte::create([
            'nombre_reportante' => $request->nombre_reportante,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_hora' => $request->fecha_hora,
            'nombre_lugar' => $request->nombre_lugar,
            'ubicacion' => $ubicacion ? DB::raw("ST_GeogFromText('SRID=4326;{$ubicacion}')") : null,
            'tipo_incidente_id' => $request->tipo_incidente_id,
            'gravedad_id' => $request->gravedad_id,
            'comentario_adicional' => $request->comentario_adicional,
            'cant_bomberos' => $request->cant_bomberos ?? 0,
            'cant_paramedicos' => $request->cant_paramedicos ?? 0,
            'cant_veterinarios' => $request->cant_veterinarios ?? 0,
            'cant_autoridades' => $request->cant_autoridades ?? 0,
            'estado_id' => $estadoPendiente->id ?? null,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reporte creado exitosamente.',
                'id' => $reporte->id, // Send ID back for 2nd step
            ], 201);
        }

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reporte = Reporte::with('animal_report')->findOrFail($id);

        // Cargar relaciones manualmente para evitar problema de UUID con whereIn
        $tiposIncidente = TiposIncidente::all()->keyBy('id');
        $nivelesGravedad = NivelesGravedad::all()->keyBy('id');
        $estadosSistema = EstadosSistema::all()->keyBy('id');

        if ($reporte->tipo_incidente_id && isset($tiposIncidente[$reporte->tipo_incidente_id])) {
            $reporte->setRelation('tipos_incidente', $tiposIncidente[$reporte->tipo_incidente_id]);
        }
        if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
            $reporte->setRelation('niveles_gravedad', $nivelesGravedad[$reporte->gravedad_id]);
        }
        if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
            $reporte->setRelation('estados_sistema', $estadosSistema[$reporte->estado_id]);
        }

        $condicionesAnimales = [
            1 => 'Herido leve',
            2 => 'Herido grave',
            3 => 'Inconsciente',
            4 => 'Deshidratado',
            5 => 'Quemaduras',
            6 => 'Desorientado / shock',
            7 => 'Atascado / atrapado',
            8 => 'Difícil acceso',
            9 => 'Desconocido'
        ];

        return view('reportes.show', compact('reporte', 'condicionesAnimales'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reporte = Reporte::with('animal_report')->findOrFail($id);
        $tiposIncidente = TiposIncidente::where('activo', true)->orderBy('nombre')->get();
        $nivelesGravedad = NivelesGravedad::where('activo', true)->orderBy('orden')->get();
        $estados = EstadosSistema::where('tabla', 'reportes')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        // Add condition mapping for animal reports
        $condicionesAnimales = [
            1 => 'Herido leve',
            2 => 'Herido grave',
            3 => 'Inconsciente',
            4 => 'Deshidratado',
            5 => 'Quemaduras',
            6 => 'Desorientado / shock',
            7 => 'Atascado / atrapado',
            8 => 'Difícil acceso',
            9 => 'Desconocido'
        ];

        return view('reportes.edit', compact('reporte', 'tiposIncidente', 'nivelesGravedad', 'estados', 'condicionesAnimales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reporte = Reporte::findOrFail($id);

        $request->validate([
            'nombre_reportante' => 'required|string|max:200',
            'telefono_contacto' => 'nullable|string|max:20',
            'fecha_hora' => 'required|date',
            'nombre_lugar' => 'nullable|string|max:200',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'tipo_incidente_id' => 'nullable|uuid|exists:tipos_incidente,id',
            'gravedad_id' => 'nullable|uuid|exists:niveles_gravedad,id',
            'comentario_adicional' => 'nullable|string',
            'cant_bomberos' => 'nullable|integer|min:0',
            'cant_paramedicos' => 'nullable|integer|min:0',
            'cant_veterinarios' => 'nullable|integer|min:0',
            'cant_autoridades' => 'nullable|integer|min:0',
            'estado_id' => 'nullable|uuid|exists:estados_sistema,id',
        ]);

        // Construir el punto geográfico si hay coordenadas
        $ubicacion = null;
        if ($request->latitud && $request->longitud) {
            $ubicacion = "POINT({$request->longitud} {$request->latitud})";
        }

        $data = [
            'nombre_reportante' => $request->nombre_reportante,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_hora' => $request->fecha_hora,
            'nombre_lugar' => $request->nombre_lugar,
            'tipo_incidente_id' => $request->tipo_incidente_id,
            'gravedad_id' => $request->gravedad_id,
            'comentario_adicional' => $request->comentario_adicional,
            'cant_bomberos' => $request->cant_bomberos ?? 0,
            'cant_paramedicos' => $request->cant_paramedicos ?? 0,
            'cant_veterinarios' => $request->cant_veterinarios ?? 0,
            'cant_autoridades' => $request->cant_autoridades ?? 0,
            'estado_id' => $request->estado_id,
        ];

        if ($ubicacion) {
            DB::table('reportes')
                ->where('id', $id)
                ->update(array_merge($data, [
                    'ubicacion' => DB::raw("ST_GeogFromText('SRID=4326;{$ubicacion}')")
                ]));
        } else {
            $reporte->update($data);
        }

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $reporte = Reporte::findOrFail($id);
            $reporte->delete();

            return redirect()->route('reportes.index')
                ->with('success', 'Reporte eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('reportes.index')
                ->with('error', 'Error al eliminar el reporte.');
        }
    }

    /**
     * Show public form for quick reports (for guests)
     */
    public function formularioPublico()
    {
        $tiposIncidente = TiposIncidente::where('activo', true)->orderBy('nombre')->get();
        $nivelesGravedad = NivelesGravedad::where('activo', true)->orderBy('orden')->get();

        return view('reportes.publico', compact('tiposIncidente', 'nivelesGravedad'));
    }

    /**
     * Store a public quick report (for guests)
     */
    public function storePublico(Request $request)
    {
        $request->validate([
            'nombre_reportante' => 'required|string|max:200',
            'telefono_contacto' => 'nullable|string|max:20',
            'nombre_lugar' => 'nullable|string|max:200',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'tipo_incidente_id' => 'nullable|uuid|exists:tipos_incidente,id',
            'gravedad_id' => 'nullable|uuid|exists:niveles_gravedad,id',
            'comentario_adicional' => 'nullable|string',
        ]);

        // Obtener estado "pendiente" para reportes
        $estadoPendiente = EstadosSistema::where('tabla', 'reportes')
            ->where('codigo', 'pendiente')
            ->first();

        // Construir el punto geográfico
        $ubicacion = "POINT({$request->longitud} {$request->latitud})";

        $reporte = Reporte::create([
            'nombre_reportante' => $request->nombre_reportante,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_hora' => now(),
            'nombre_lugar' => $request->nombre_lugar,
            'ubicacion' => DB::raw("ST_GeogFromText('SRID=4326;{$ubicacion}')"),
            'tipo_incidente_id' => $request->tipo_incidente_id,
            'gravedad_id' => $request->gravedad_id,
            'comentario_adicional' => $request->comentario_adicional,
            'cant_bomberos' => 0,
            'cant_paramedicos' => 0,
            'cant_veterinarios' => 0,
            'cant_autoridades' => 0,
            'estado_id' => $estadoPendiente->id ?? null,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reporte enviado exitosamente.',
                'id' => $reporte->id,
            ], 201);
        }

        return redirect()->route('focos-calor.index')
            ->with('success', 'Reporte enviado exitosamente. Gracias por su colaboración.');
    }

    /**
     * API endpoint para Rescate de animales
     */
    public function apiReportesRescateAnimales(Request $request)
    {
        try {
            // Load all severity levels for reference
            $nivelesGravedad = NivelesGravedad::all()->keyBy('id');

            $query = Reporte::query();

            // Order by fecha_hora descending (most recent first)
            $query->orderBy('fecha_hora', 'desc');

            // Get all reportes
            $reportes = $query->get();

            // Transform the data to match classmate's format
            $data = $reportes->map(function ($reporte) use ($nivelesGravedad) {
                // Extract latitude and longitude from ubicacion (PostGIS point)
                $latitud = null;
                $longitud = null;
                
                if ($reporte->ubicacion && isset($reporte->ubicacion['coordinates'])) {
                    // GeoJSON format: [longitude, latitude]
                    $longitud = $reporte->ubicacion['coordinates'][0];
                    $latitud = $reporte->ubicacion['coordinates'][1];
                }

                // Get severity level name
                $nivelGravedad = null;
                if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
                    $nivelGravedad = $nivelesGravedad[$reporte->gravedad_id]->nombre;
                }

                return [
                    'id' => $reporte->id,
                    'nombre_reportante' => $reporte->nombre_reportante,
                    'telefono_contacto' => $reporte->telefono_contacto,
                    'fecha_hora' => $reporte->fecha_hora ? $reporte->fecha_hora->toIso8601String() : null,
                    'nombre_lugar' => $reporte->nombre_lugar,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'comentario_adicional' => $reporte->comentario_adicional,
                    'nivel_gravedad' => $nivelGravedad,
                    'creado' => $reporte->creado ? $reporte->creado->toIso8601String() : null,
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los reportes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint for mobile app - Get all reportes with filters
     */
    public function api(Request $request)
    {
        try {
            $query = Reporte::query();

            // Filter by estado_id if provided
            if ($request->has('estado_id')) {
                $query->where('estado_id', $request->estado_id);
            }

            // Filter by tipo_incidente_id if provided
            if ($request->has('tipo_incidente_id')) {
                $query->where('tipo_incidente_id', $request->tipo_incidente_id);
            }

            // Filter by gravedad_id if provided
            if ($request->has('gravedad_id')) {
                $query->where('gravedad_id', $request->gravedad_id);
            }

            // Filter by date range if provided
            if ($request->has('fecha_desde')) {
                $query->where('fecha_hora', '>=', $request->fecha_desde);
            }
            if ($request->has('fecha_hasta')) {
                $query->where('fecha_hora', '<=', $request->fecha_hasta);
            }

            // Order by fecha_hora descending (most recent first)
            $query->orderBy('fecha_hora', 'desc');

            // Get pagination parameters
            $perPage = $request->input('per_page', 20);
            $reportes = $query->paginate($perPage);

            // Load relationships manually to avoid UUID issues
            $tiposIncidente = TiposIncidente::all()->keyBy('id');
            $nivelesGravedad = NivelesGravedad::all()->keyBy('id');
            $estadosSistema = EstadosSistema::all()->keyBy('id');

            // Transform the data to include relationships
            $reportes->getCollection()->transform(function ($reporte) use ($tiposIncidente, $nivelesGravedad, $estadosSistema) {
                // Convert to array
                $reporteArray = $reporte->toArray();

                // Add relationship data
                if ($reporte->tipo_incidente_id && isset($tiposIncidente[$reporte->tipo_incidente_id])) {
                    $reporteArray['tipo_incidente'] = $tiposIncidente[$reporte->tipo_incidente_id]->toArray();
                }
                if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
                    $reporteArray['nivel_gravedad'] = $nivelesGravedad[$reporte->gravedad_id]->toArray();
                }
                if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
                    $reporteArray['estado'] = $estadosSistema[$reporte->estado_id]->toArray();
                }

                // Format ubicacion if exists
                if ($reporte->ubicacion) {
                    $reporteArray['ubicacion'] = $reporte->ubicacion;
                }

                return $reporteArray;
            });

            return response()->json([
                'success' => true,
                'data' => $reportes->items(),
                'pagination' => [
                    'total' => $reportes->total(),
                    'per_page' => $reportes->perPage(),
                    'current_page' => $reportes->currentPage(),
                    'last_page' => $reportes->lastPage(),
                    'from' => $reportes->firstItem(),
                    'to' => $reportes->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los reportes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export reportes as PDF
     */
    public function exportPdf()
    {
        // Get all reportes with relationships
        $reportes = Reporte::orderBy('fecha_hora', 'desc')->get();
        
        // Load relationships manually
        $tiposIncidente = TiposIncidente::all()->keyBy('id');
        $nivelesGravedad = NivelesGravedad::all()->keyBy('id');
        $estadosSistema = EstadosSistema::all()->keyBy('id');

        // Associate relationships to each reporte
        foreach ($reportes as $reporte) {
            if ($reporte->tipo_incidente_id && isset($tiposIncidente[$reporte->tipo_incidente_id])) {
                $reporte->setRelation('tipos_incidente', $tiposIncidente[$reporte->tipo_incidente_id]);
            }
            if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
                $reporte->setRelation('niveles_gravedad', $nivelesGravedad[$reporte->gravedad_id]);
            }
            if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
                $reporte->setRelation('estados_sistema', $estadosSistema[$reporte->estado_id]);
            }
        }

        // Check if Dompdf is available
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.reportes-pdf', compact('reportes'));
            return $pdf->download('reportes-rapidos-' . now()->format('Y-m-d') . '.pdf');
        }

        // Fallback: Return HTML view that can be printed to PDF
        return view('reportes.reportes-pdf', compact('reportes'));
    }

    /**
     * Export reportes as CSV
     */
    public function exportCsv()
    {
        // Get all reportes with relationships
        $reportes = Reporte::orderBy('fecha_hora', 'desc')->get();
        
        // Load relationships manually
        $tiposIncidente = TiposIncidente::all()->keyBy('id');
        $nivelesGravedad = NivelesGravedad::all()->keyBy('id');
        $estadosSistema = EstadosSistema::all()->keyBy('id');

        // Associate relationships to each reporte
        foreach ($reportes as $reporte) {
            if ($reporte->tipo_incidente_id && isset($tiposIncidente[$reporte->tipo_incidente_id])) {
                $reporte->setRelation('tipos_incidente', $tiposIncidente[$reporte->tipo_incidente_id]);
            }
            if ($reporte->gravedad_id && isset($nivelesGravedad[$reporte->gravedad_id])) {
                $reporte->setRelation('niveles_gravedad', $nivelesGravedad[$reporte->gravedad_id]);
            }
            if ($reporte->estado_id && isset($estadosSistema[$reporte->estado_id])) {
                $reporte->setRelation('estados_sistema', $estadosSistema[$reporte->estado_id]);
            }
        }

        // Create CSV filename
        $filename = 'reportes-rapidos-' . now()->format('Y-m-d-His') . '.csv';

        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Create callback to generate CSV
        $callback = function() use ($reportes) {
            $file = fopen('php://output', 'w');
            
            // Don't use BOM - Excel on Windows handles Windows-1252 better
            // We'll convert from UTF-8 to Windows-1252 for each field

            // CSV Headers - using semicolon as delimiter for better Excel compatibility
            $headers = [
                'No.',
                'Fecha/Hora',
                'Reportante',
                'Telefono',
                'Lugar',
                'Latitud',
                'Longitud',
                'Tipo de Incidente',
                'Nivel de Gravedad',
                'Estado',
                'Cant. Bomberos',
                'Cant. Paramedicos',
                'Cant. Veterinarios',
                'Cant. Autoridades',
                'Comentario Adicional',
                'Fecha Creacion'
            ];
            
            // Convert headers to Windows-1252
            $headers = array_map(function($header) {
                return mb_convert_encoding($header, 'Windows-1252', 'UTF-8');
            }, $headers);
            
            fputcsv($file, $headers, ';');

            // Add data rows
            $rowNumber = 1;
            foreach ($reportes as $reporte) {
                // Extract coordinates from ubicacion
                $latitud = null;
                $longitud = null;
                if ($reporte->ubicacion && isset($reporte->ubicacion['coordinates'])) {
                    $longitud = $reporte->ubicacion['coordinates'][0];
                    $latitud = $reporte->ubicacion['coordinates'][1];
                }

                $row = [
                    $rowNumber++, // Simple row number instead of UUID
                    $reporte->fecha_hora ? $reporte->fecha_hora->format('d/m/Y H:i:s') : '',
                    $reporte->nombre_reportante,
                    $reporte->telefono_contacto ?? '',
                    $reporte->nombre_lugar ?? '',
                    $latitud ?? '',
                    $longitud ?? '',
                    $reporte->tipos_incidente->nombre ?? '',
                    $reporte->niveles_gravedad->nombre ?? '',
                    $reporte->estados_sistema->nombre ?? 'Sin estado',
                    $reporte->cant_bomberos,
                    $reporte->cant_paramedicos,
                    $reporte->cant_veterinarios,
                    $reporte->cant_autoridades,
                    $reporte->comentario_adicional ?? '',
                    $reporte->creado ? $reporte->creado->format('d/m/Y H:i:s') : ''
                ];
                
                // Convert each field to Windows-1252 for proper accent display
                $row = array_map(function($field) {
                    if (is_string($field)) {
                        return mb_convert_encoding($field, 'Windows-1252', 'UTF-8');
                    }
                    return $field;
                }, $row);
                
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

