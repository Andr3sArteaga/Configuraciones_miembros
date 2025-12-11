<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\EstadosSistema;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin sees all teams
            $equipos = Equipo::with(['estados_sistema', 'miembros', 'reporte'])
                ->orderBy('creado', 'desc')
                ->paginate(20);

            // Get estados for the create modal
            $estados = EstadosSistema::where('tabla', 'equipos')
                ->where('activo', true)
                ->orderBy('orden')
                ->get();

            // Get available usuarios for the create modal
            $usuarios = Usuario::with(['niveles_entrenamiento', 'estados_sistema'])
                ->orderBy('nombre')
                ->orderBy('apellido')
                ->get();

            // Empty array for create mode
            $miembrosAsignados = [];

            // Provide reportes to allow selection in team creation modal
            $reportes = Reporte::whereNotNull('ubicacion')
                ->orderBy('fecha_hora', 'desc')
                ->get();

            return view('equipos.index', compact('equipos', 'estados', 'usuarios', 'miembrosAsignados', 'reportes'));
        } else {
            // Usuario/Voluntario sees only their team
            $equipo = $user->equipo();

            if (!$equipo) {
                return view('equipos.sin-equipo');
            }

            // Load relationships
            $equipo->load(['estados_sistema', 'miembros', 'reporte']);

            return view('equipos.mi-equipo', compact('equipo'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener estados de equipos
        $estados = EstadosSistema::where('tabla', 'equipos')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        // Provide reportes to help pick one if the user opens a create-only page
        $reportes = Reporte::whereNotNull('ubicacion')
            ->orderBy('fecha_hora', 'desc')
            ->get();

        return view('equipos.create', compact('estados', 'reportes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_equipo' => 'required|string|max:100',
            'estado_id' => 'required|uuid|exists:estados_sistema,id',
            'reporte_id' => 'nullable|uuid|exists:reportes,id',
            'codigo_seguimiento' => 'nullable|string|max:20',
            'insumos_necesarios' => 'nullable|string',
            'miembros' => 'nullable|array',
            'miembros.*' => 'uuid|exists:usuarios,id',
            'lider_id' => 'nullable|uuid|exists:usuarios,id',
            'comunarios' => 'nullable|array',
            'comunarios.*.nombre' => 'required_with:comunarios|string|max:100',
            'comunarios.*.edad' => 'required_with:comunarios|integer|min:18|max:100',
        ], [
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio',
            'nombre_equipo.max' => 'El nombre del equipo no puede exceder 100 caracteres',
            'estado_id.required' => 'Debe seleccionar un estado',
            'estado_id.exists' => 'El estado seleccionado no es válido',
            'reporte_id.exists' => 'El reporte seleccionado no es válido',
            'miembros.array' => 'Los miembros deben ser un array válido',
            'miembros.*.uuid' => 'Cada miembro debe tener un ID válido',
            'miembros.*.exists' => 'Uno o más miembros seleccionados no existen',
            'lider_id.uuid' => 'El ID del líder debe ser válido',
            'lider_id.exists' => 'El líder seleccionado no existe',
            'comunarios.*.nombre.required_with' => 'El nombre del comunario es obligatorio',
            'comunarios.*.edad.required_with' => 'La edad del comunario es obligatoria',
            'comunarios.*.edad.min' => 'Los comunarios deben ser mayores de 18 años',
        ]);

        try {
            DB::beginTransaction();

            // Crear el ID del equipo
            $equipoId = \Ramsey\Uuid\Uuid::uuid4()->toString();

            // Calcular cantidad de integrantes
            $cantidadMiembros = ($request->filled('miembros') && is_array($request->miembros)) ? count($request->miembros) : 0;
            $cantidadComunarios = ($request->filled('comunarios') && is_array($request->comunarios)) ? count($request->comunarios) : 0;
            $totalIntegrantes = $cantidadMiembros + $cantidadComunarios;

            // Insertar con reporte asociado (reporte_id opcional)
            $reporteId = $request->filled('reporte_id') ? $validated['reporte_id'] : null;
            DB::statement(
                "INSERT INTO equipos (id, nombre_equipo, estado_id, cantidad_integrantes, reporte_id, creado)
                     VALUES (?, ?, ?, ?, ?, NOW())",
                [$equipoId, $validated['nombre_equipo'], $validated['estado_id'], $totalIntegrantes, $reporteId]
            );


            // Agregar miembros al equipo si se proporcionaron
            if ($request->filled('miembros') && is_array($request->miembros)) {
                $liderId = $request->filled('lider_id') ? $validated['lider_id'] : null;

                foreach ($request->miembros as $usuarioId) {
                    $miembroId = \Ramsey\Uuid\Uuid::uuid4()->toString();
                    $esLider = ($liderId && $usuarioId === $liderId);

                    DB::statement(
                        "INSERT INTO miembros_equipo (id, id_equipo, id_usuario, es_lider, fecha_ingreso)
                         VALUES (?, ?, ?, ?, NOW())",
                        [$miembroId, $equipoId, $usuarioId, $esLider]
                    );
                }
            }

            // Insertar comunarios de apoyo si existen
            if ($request->filled('comunarios') && is_array($request->comunarios)) {
                foreach ($request->comunarios as $comunario) {
                    // Validar estructura básica del comunario
                    if (isset($comunario['nombre']) && isset($comunario['edad'])) {
                        DB::table('comunarios_apoyo')->insert([
                            'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                            'nombre' => $comunario['nombre'],
                            'edad' => $comunario['edad'],
                            'entidad_perteneciente' => $comunario['entidad'] ?? null,
                            'equipoid' => $equipoId,
                            'creado' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('equipos.index')
                ->with('success', 'Equipo creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el equipo: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipo = Equipo::with(['estados_sistema', 'miembros', 'comunarios_apoyos'])->findOrFail($id);

        return view('equipos.show', compact('equipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $equipo = Equipo::with(['miembros', 'comunarios_apoyos'])->findOrFail($id);

        // Obtener estados de equipos
        $estados = EstadosSistema::where('tabla', 'equipos')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        // Get available usuarios for the modal (include users without nivel_entrenamiento)
        $usuarios = Usuario::with(['niveles_entrenamiento', 'estados_sistema'])
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        // Get IDs of current members
        $miembrosAsignados = $equipo->miembros->pluck('id')->toArray();

        // Get current leader ID (if any)
        $liderAsignado = $equipo->miembros->firstWhere('pivot.es_lider', true);
        $liderAsignadoId = $liderAsignado ? $liderAsignado->id : null;

        // Extraer latitud/longitud del equipo (o del reporte relacionado) si está disponible
        $latitud = $equipo->latitud;
        $longitud = $equipo->longitud;

        // Get existing tracking code
        $codigoSeguimiento = $equipo->codigo_seguimiento;

        $reportes = Reporte::whereNotNull('ubicacion')->orderBy('fecha_hora', 'desc')->get();

        return view('equipos.edit', compact('equipo', 'estados', 'usuarios', 'miembrosAsignados', 'latitud', 'longitud', 'liderAsignadoId', 'reportes', 'codigoSeguimiento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $equipo = Equipo::findOrFail($id);

        $validated = $request->validate([
            'nombre_equipo' => 'required|string|max:100',
            'estado_id' => 'required|uuid|exists:estados_sistema,id',
            'reporte_id' => 'nullable|uuid|exists:reportes,id',
            'codigo_seguimiento' => 'nullable|string|max:20',
            'insumos_necesarios' => 'nullable|string',
            'miembros' => 'nullable|array',
            'miembros.*' => 'uuid|exists:usuarios,id',
            'lider_id' => 'nullable|uuid|exists:usuarios,id',
        ], [
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio',
            'nombre_equipo.max' => 'El nombre del equipo no puede exceder 100 caracteres',
            'estado_id.required' => 'Debe seleccionar un estado',
            'estado_id.exists' => 'El estado seleccionado no es válido',
            'reporte_id.exists' => 'El reporte seleccionado no es válido',
            'latitud.numeric' => 'La latitud debe ser un número',
            'latitud.min' => 'La latitud debe estar entre -90 y 90',
            'latitud.max' => 'La latitud debe estar entre -90 y 90',
            'longitud.numeric' => 'La longitud debe ser un número',
            'longitud.min' => 'La longitud debe estar entre -180 y 180',
            'longitud.max' => 'La longitud debe estar entre -180 y 180',
            'miembros.array' => 'Los miembros deben ser un array válido',
            'miembros.*.uuid' => 'Cada miembro debe tener un ID válido',
            'miembros.*.exists' => 'Uno o más miembros seleccionados no existen',
            'lider_id.uuid' => 'El ID del líder debe ser válido',
            'lider_id.exists' => 'El líder seleccionado no existe',
            'comunarios' => 'nullable|array',
            'comunarios.*.nombre' => 'required_with:comunarios|string|max:100',
            'comunarios.*.edad' => 'required_with:comunarios|integer|min:18|max:100',
            'comunarios.*.entidad' => 'nullable|string|max:150',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar campos básicos
            $equipo->nombre_equipo = $validated['nombre_equipo'];
            $equipo->estado_id = $validated['estado_id'];
            $equipo->codigo_seguimiento = $request->input('codigo_seguimiento');

            // Actualizar reporte
            $reporteId = $request->filled('reporte_id') ? $validated['reporte_id'] : null;
            $equipo->reporte_id = $reporteId;

            // Save basic fields
            $equipo->save();

            // Update PostGIS location directly via SQL
            $lat = $request->input('latitud');
            $lng = $request->input('longitud');

            if ($lat && $lng) {
                DB::statement(
                    "UPDATE equipos SET ubicacion = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?",
                    [$lng, $lat, $equipo->id]
                );
            } else if (!$reporteId) {
                // If neither report nor coordinates, maybe clear location? 
                // DB::statement("UPDATE equipos SET ubicacion = NULL WHERE id = ?", [$equipo->id]);
            }

            // Actualizar miembros del equipo
            // Si viene el flag sync_relations o si existen miembros en el request
            if ($request->has('sync_relations') || $request->has('miembros')) {
                // Eliminar miembros actuales
                DB::table('miembros_equipo')->where('id_equipo', $equipo->id)->delete();

                $cantidadMiembros = 0;

                // Agregar nuevos miembros
                if (is_array($request->miembros) && count($request->miembros) > 0) {
                    $liderId = $request->filled('lider_id') ? $validated['lider_id'] : null;
                    $cantidadMiembros = count($request->miembros);

                    foreach ($request->miembros as $usuarioId) {
                        $miembroId = \Ramsey\Uuid\Uuid::uuid4()->toString();
                        $esLider = ($liderId && $usuarioId === $liderId);

                        DB::statement(
                            "INSERT INTO miembros_equipo (id, id_equipo, id_usuario, es_lider, fecha_ingreso)
                             VALUES (?, ?, ?, ?, NOW())",
                            [$miembroId, $equipo->id, $usuarioId, $esLider]
                        );
                    }
                }

                // Actualizar contador de integrantes (se hará al final)
                // DB::statement("UPDATE equipos SET cantidad_integrantes = ? WHERE id = ?", [$cantidadMiembros, $equipo->id]);
            }

            // Actualizar comunarios del equipo
            if ($request->has('sync_relations') || $request->has('comunarios')) {
                // Eliminar comunarios asociados a este equipo
                DB::table('comunarios_apoyo')->where('equipoid', $equipo->id)->delete();

                if (is_array($request->comunarios) && count($request->comunarios) > 0) {
                    foreach ($request->comunarios as $comunario) {
                        if (isset($comunario['nombre']) && isset($comunario['edad'])) {
                            DB::table('comunarios_apoyo')->insert([
                                'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                                'nombre' => $comunario['nombre'],
                                'edad' => $comunario['edad'],
                                'entidad_perteneciente' => $comunario['entidad'] ?? null,
                                'equipoid' => $equipo->id,
                                'creado' => now(),
                            ]);
                        }
                    }
                }
            }

            // Recalcular y actualizar cantidad total de integrantes (miembros + comunarios)
            $cantMiembros = DB::table('miembros_equipo')->where('id_equipo', $equipo->id)->count();
            $cantComunarios = DB::table('comunarios_apoyo')->where('equipoid', $equipo->id)->count();
            $totalIntegrantes = $cantMiembros + $cantComunarios;

            DB::statement("UPDATE equipos SET cantidad_integrantes = ? WHERE id = ?", [$totalIntegrantes, $equipo->id]);

            DB::commit();

            return redirect()
                ->route('equipos.index')
                ->with('success', 'Equipo actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el equipo: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipo = Equipo::findOrFail($id);
        $nombre = $equipo->nombre_equipo;

        $equipo->delete();

        return redirect()
            ->route('equipos.index')
            ->with('success', "Equipo '{$nombre}' eliminado exitosamente");
    }

    /**
     * Agregar un comunario de apoyo a un equipo.
     */
    public function agregarComunario(Request $request, string $id)
    {
        $equipo = Equipo::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'edad' => 'required|integer|min:18|max:100',
            'entidad_perteneciente' => 'nullable|string|max:150',
        ], [
            'nombre.required' => 'El nombre del comunario es obligatorio.',
            'edad.required' => 'La edad es obligatoria.',
            'edad.integer' => 'La edad debe ser un número.',
            'edad.min' => 'El comunario debe ser mayor de 18 años.',
        ]);

        try {
            DB::table('comunarios_apoyo')->insert([
                'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                'nombre' => $validated['nombre'],
                'edad' => $validated['edad'],
                'entidad_perteneciente' => $validated['entidad_perteneciente'] ?? null,
                'equipoid' => $equipo->id,
                'creado' => now(),
            ]);

            return redirect()->route('equipos.show', $equipo->id)
                ->with('success', 'Comunario de apoyo agregado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('equipos.show', $equipo->id)
                ->with('error', 'Error al agregar el comunario: ' . $e->getMessage());
        }
    }

    /**
     * Remover un comunario de apoyo de un equipo.
     */
    public function removerComunario(string $id, string $comunarioId)
    {
        try {
            DB::table('comunarios_apoyo')->where('id', $comunarioId)->where('equipoid', $id)->delete();

            return redirect()->route('equipos.show', $id)
                ->with('success', 'Comunario de apoyo removido exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('equipos.show', $id)
                ->with('error', 'Error al remover el comunario: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint para obtener equipos con ubicación
     */
    public function api()
    {
        try {
            $equipos = Equipo::with(['estados_sistema', 'reporte'])
                ->whereNotNull('reporte_id')
                ->get()
                ->map(function ($equipo) {
                    return [
                        'id' => $equipo->id,
                        'nombre_equipo' => $equipo->nombre_equipo,
                        'cantidad_integrantes' => $equipo->cantidad_integrantes,
                        // Usar ubicacion del reporte asociado (equipos no almacenan ubicación directamente)
                        'ubicacion' => $equipo->reporte ? $equipo->reporte->ubicacion : null,
                        'estado' => $equipo->estados_sistema ? [
                            'nombre' => $equipo->estados_sistema->nombre,
                            'codigo' => $equipo->estados_sistema->codigo,
                            'color' => $equipo->estados_sistema->color
                        ] : null
                    ];
                });

            return response()->json($equipos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener equipos',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    /**
     * API endpoint to get deployed teams (history)
     * Ordered by creation date descending
     */
    public function deployed()
    {
        try {
            $equipos = Equipo::with(['estados_sistema', 'reporte'])
                ->whereNotNull('reporte_id')
                ->orderBy('creado', 'desc')
                ->get()
                ->map(function ($equipo) {
                    return [
                        'id' => $equipo->id,
                        'nombre_equipo' => $equipo->nombre_equipo,
                        'cantidad_integrantes' => $equipo->cantidad_integrantes,
                        'ubicacion' => $equipo->reporte ? $equipo->reporte->ubicacion : null,
                        'latitud' => $equipo->reporte ? $equipo->reporte->latitud : null,
                        'longitud' => $equipo->reporte ? $equipo->reporte->longitud : null,
                        'estado' => $equipo->estados_sistema ? [
                            'nombre' => $equipo->estados_sistema->nombre,
                            'codigo' => $equipo->estados_sistema->codigo,
                            'color' => $equipo->estados_sistema->color
                        ] : null,
                        'fecha_despliegue' => $equipo->creado->toIso8601String(),
                        'tiempo_transcurrido' => $equipo->creado->diffForHumans()
                    ];
                });

            return response()->json($equipos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener equipos desplegados',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Count para el numero de equipos
     */
    public function count()
    {
        $count = Equipo::count();
        return response()->json(['count' => $count]);
    }
}
