<?php

namespace App\Http\Controllers;

use App\Models\ComunariosApoyo;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComunarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunarios = ComunariosApoyo::with('equipo')
            ->orderBy('creado', 'desc')
            ->paginate(20);

        return view('comunarios.index', compact('comunarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $equipos = Equipo::orderBy('nombre_equipo')->get();
        return view('comunarios.create', compact('equipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'edad' => 'required|integer|min:18|max:100',
            'entidad_perteneciente' => 'nullable|string|max:150',
            'equipoid' => 'required|uuid|exists:equipos,id',
        ], [
            'nombre.required' => 'El nombre del comunario es obligatorio.',
            'edad.required' => 'La edad es obligatoria.',
            'edad.integer' => 'La edad debe ser un número.',
            'edad.min' => 'El comunario debe ser mayor de 18 años.',
            'edad.max' => 'La edad no puede ser mayor a 100 años.',
            'equipoid.required' => 'Debe seleccionar un equipo.',
            'equipoid.exists' => 'El equipo seleccionado no existe.',
        ]);

        try {
            DB::table('comunarios_apoyo')->insert([
                'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                'nombre' => $validated['nombre'],
                'edad' => $validated['edad'],
                'entidad_perteneciente' => $validated['entidad_perteneciente'] ?? null,
                'equipoid' => $validated['equipoid'],
                'creado' => now(),
            ]);

            return redirect()->route('comunarios.index')
                ->with('success', 'Comunario de apoyo agregado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al agregar el comunario: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comunario = ComunariosApoyo::with('equipo')->findOrFail($id);
        return view('comunarios.show', compact('comunario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $comunario = ComunariosApoyo::findOrFail($id);
        $equipos = Equipo::orderBy('nombre_equipo')->get();
        return view('comunarios.edit', compact('comunario', 'equipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comunario = ComunariosApoyo::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'edad' => 'required|integer|min:18|max:100',
            'entidad_perteneciente' => 'nullable|string|max:150',
            'equipoid' => 'required|uuid|exists:equipos,id',
        ], [
            'nombre.required' => 'El nombre del comunario es obligatorio.',
            'edad.required' => 'La edad es obligatoria.',
            'edad.integer' => 'La edad debe ser un número.',
            'edad.min' => 'El comunario debe ser mayor de 18 años.',
            'edad.max' => 'La edad no puede ser mayor a 100 años.',
            'equipoid.required' => 'Debe seleccionar un equipo.',
            'equipoid.exists' => 'El equipo seleccionado no existe.',
        ]);

        try {
            DB::table('comunarios_apoyo')
                ->where('id', $id)
                ->update([
                    'nombre' => $validated['nombre'],
                    'edad' => $validated['edad'],
                    'entidad_perteneciente' => $validated['entidad_perteneciente'] ?? null,
                    'equipoid' => $validated['equipoid'],
                    'modificado' => now(),
                ]);

            return redirect()->route('comunarios.index')
                ->with('success', 'Comunario de apoyo actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el comunario: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $comunario = ComunariosApoyo::findOrFail($id);
            $nombre = $comunario->nombre;

            DB::table('comunarios_apoyo')->where('id', $id)->delete();

            return redirect()->route('comunarios.index')
                ->with('success', "Comunario '{$nombre}' eliminado exitosamente.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el comunario: ' . $e->getMessage());
        }
    }
}
