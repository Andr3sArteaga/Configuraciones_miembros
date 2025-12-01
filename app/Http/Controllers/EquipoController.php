<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\EstadosSistema;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
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
            'reporte_id' => 'required|uuid|exists:reportes,id',
            'miembros' => 'nullable|array',
            'miembros.*' => 'uuid|exists:usuarios,id',
            'lider_id' => 'nullable|uuid|exists:usuarios,id',
        ], [
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio',
            'nombre_equipo.max' => 'El nombre del equipo no puede exceder 100 caracteres',
            'estado_id.required' => 'Debe seleccionar un estado',
            'estado_id.exists' => 'El estado seleccionado no es válido',
            'reporte_id.required' => 'Debe seleccionar el reporte asociado al equipo',
            'reporte_id.exists' => 'El reporte seleccionado no es válido',
            'miembros.array' => 'Los miembros deben ser un array válido',
            'miembros.*.uuid' => 'Cada miembro debe tener un ID válido',
            'miembros.*.exists' => 'Uno o más miembros seleccionados no existen',
            'lider_id.uuid' => 'El ID del líder debe ser válido',
            'lider_id.exists' => 'El líder seleccionado no existe',
        ]);

        try {
            DB::beginTransaction();

            // Crear el ID del equipo
            $equipoId = \Ramsey\Uuid\Uuid::uuid4()->toString();

            // Calcular cantidad de integrantes
            $cantidadMiembros = ($request->filled('miembros') && is_array($request->miembros)) ? count($request->miembros) : 0;

            // Insertar con reporte asociado (reporte_id obligatorio)
            DB::statement(
                "INSERT INTO equipos (id, nombre_equipo, estado_id, cantidad_integrantes, reporte_id, creado)
                     VALUES (?, ?, ?, ?, ?, NOW())",
                [$equipoId, $validated['nombre_equipo'], $validated['estado_id'], $cantidadMiembros, $validated['reporte_id']]
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
        $equipo = Equipo::with(['estados_sistema', 'miembros'])->findOrFail($id);

        return view('equipos.show', compact('equipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $equipo = Equipo::with('miembros')->findOrFail($id);

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

        $reportes = Reporte::whereNotNull('ubicacion')->orderBy('fecha_hora', 'desc')->get();

        return view('equipos.edit', compact('equipo', 'estados', 'usuarios', 'miembrosAsignados', 'latitud', 'longitud', 'liderAsignadoId', 'reportes'));
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
            'reporte_id' => 'required|uuid|exists:reportes,id',
            'miembros' => 'nullable|array',
            'miembros.*' => 'uuid|exists:usuarios,id',
            'lider_id' => 'nullable|uuid|exists:usuarios,id',
        ], [
            'nombre_equipo.required' => 'El nombre del equipo es obligatorio',
            'nombre_equipo.max' => 'El nombre del equipo no puede exceder 100 caracteres',
            'estado_id.required' => 'Debe seleccionar un estado',
            'estado_id.exists' => 'El estado seleccionado no es válido',
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
        ]);

        try {
            DB::beginTransaction();

            // Actualizar campos básicos
            $equipo->nombre_equipo = $validated['nombre_equipo'];
            $equipo->estado_id = $validated['estado_id'];

            // Actualizar reporte asociado
            if ($request->filled('reporte_id')) {
                DB::statement("UPDATE equipos SET reporte_id = ? WHERE id = ?", [$validated['reporte_id'], $equipo->id]);
            }

            $equipo->save();

            // Actualizar miembros del equipo
            if ($request->has('miembros')) {
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

                // Actualizar contador de integrantes
                DB::statement("UPDATE equipos SET cantidad_integrantes = ? WHERE id = ?", [$cantidadMiembros, $equipo->id]);
            }

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
     * Count para el numero de equipos
     */
    public function count()
    {
        $count = Equipo::count();
        return response()->json(['count' => $count]);
    }
}
