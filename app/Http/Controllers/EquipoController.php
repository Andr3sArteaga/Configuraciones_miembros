<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\EstadosSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipos = Equipo::with('estados_sistema')
            ->orderBy('creado', 'desc')
            ->paginate(20);

        // Get estados for the create modal
        $estados = EstadosSistema::where('tabla', 'equipos')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('equipos.index', compact('equipos', 'estados'));
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

        return view('equipos.create', compact('estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_equipo' => 'required|string|max:100',
            'estado_id' => 'required|uuid|exists:estados_sistema,id',
            'latitud' => 'nullable|numeric|min:-90|max:90',
            'longitud' => 'nullable|numeric|min:-180|max:180',
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
        ]);

        // Crear el equipo
        $equipo = new Equipo();
        $equipo->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $equipo->nombre_equipo = $validated['nombre_equipo'];
        $equipo->estado_id = $validated['estado_id'];
        $equipo->cantidad_integrantes = 0;

        // Si se proporcionaron coordenadas, crear el punto geográfico
        if ($request->filled('latitud') && $request->filled('longitud')) {
            $lat = $validated['latitud'];
            $lng = $validated['longitud'];
            // Formato WKT (Well-Known Text) para PostGIS
            $equipo->ubicacion = DB::raw("ST_GeogFromText('POINT({$lng} {$lat})')");
        }

        $equipo->save();

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipo = Equipo::with('estados_sistema')->findOrFail($id);

        return view('equipos.show', compact('equipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $equipo = Equipo::findOrFail($id);

        // Obtener estados de equipos
        $estados = EstadosSistema::where('tabla', 'equipos')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        // Extraer latitud y longitud si existe ubicación
        $latitud = $equipo->latitud;
        $longitud = $equipo->longitud;

        return view('equipos.edit', compact('equipo', 'estados', 'latitud', 'longitud'));
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
            'latitud' => 'nullable|numeric|min:-90|max:90',
            'longitud' => 'nullable|numeric|min:-180|max:180',
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
        ]);

        // Actualizar campos básicos
        $equipo->nombre_equipo = $validated['nombre_equipo'];
        $equipo->estado_id = $validated['estado_id'];

        // Actualizar ubicación si se proporcionaron coordenadas
        if ($request->filled('latitud') && $request->filled('longitud')) {
            $lat = $validated['latitud'];
            $lng = $validated['longitud'];
            DB::statement("UPDATE equipos SET ubicacion = ST_GeogFromText('POINT({$lng} {$lat})') WHERE id = ?", [$equipo->id]);
        } elseif (!$request->filled('latitud') && !$request->filled('longitud')) {
            // Si ambos campos están vacíos, eliminar la ubicación
            DB::statement("UPDATE equipos SET ubicacion = NULL WHERE id = ?", [$equipo->id]);
        }

        $equipo->save();

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo actualizado exitosamente');
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
        $equipos = Equipo::with('estados_sistema')
            ->whereNotNull('ubicacion')
            ->get()
            ->map(function ($equipo) {
                return [
                    'id' => $equipo->id,
                    'nombre_equipo' => $equipo->nombre_equipo,
                    'cantidad_integrantes' => $equipo->cantidad_integrantes,
                    'ubicacion' => $equipo->ubicacion,
                    'estado' => $equipo->estados_sistema ? [
                        'nombre' => $equipo->estados_sistema->nombre,
                        'codigo' => $equipo->estados_sistema->codigo,
                        'color' => $equipo->estados_sistema->color
                    ] : null
                ];
            });

        return response()->json($equipos);
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
