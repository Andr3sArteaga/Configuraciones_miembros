<?php

namespace App\Http\Controllers;

use App\Models\Inscrito;
use App\Models\Curso;
use App\Models\CursoAsignado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InscritoController extends Controller
{
    /**
     * Mostrar listado de todos los inscritos
     */
    public function index()
    {
        $inscritos = Inscrito::with(['cursos_asignados.curso'])->get();
        $cursos = Curso::all();
        return view('inscritos.index', compact('inscritos', 'cursos'));
    }

    /**
     * Mostrar formulario para crear inscrito
     */
    public function create()
    {
        $cursos = Curso::all();
        return view('inscritos.create', compact('cursos'));
    }

    /**
     * Guardar nuevo inscrito y asignarlo a un curso
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:150',
            'apellidos' => 'required|string|max:150',
            'ci' => 'required|string|max:50|unique:inscrito,ci',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'required|email|max:150|unique:inscrito,correo',
            'curso_id' => 'required|exists:cursos,id'
        ], [
            'nombres.required' => 'El nombre es obligatorio',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'ci.required' => 'El CI es obligatorio',
            'ci.unique' => 'Este CI ya está registrado',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado',
            'curso_id.required' => 'Debe seleccionar un curso',
            'curso_id.exists' => 'El curso seleccionado no existe'
        ]);

        try {
            DB::beginTransaction();

            // Crear inscrito
            $inscrito = Inscrito::create([
                'id' => Str::uuid(),
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'fecha_registro' => now()
            ]);

            // Asignar inscrito al curso
            CursoAsignado::create([
                'id' => Str::uuid(),
                'curso_id' => $request->curso_id,
                'entidad_id' => $inscrito->id,
                'entidad_tipo' => 'inscrito',
                'fecha_asignacion' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inscrito creado y asignado al curso exitosamente',
                'inscrito' => $inscrito
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear inscrito: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar formulario para editar inscrito
     */
    public function edit($id)
    {
        $inscrito = Inscrito::with('cursos_asignados.curso')->findOrFail($id);
        $cursos = Curso::all();
        return response()->json([
            'inscrito' => $inscrito,
            'cursos' => $cursos
        ]);
    }

    /**
     * Actualizar inscrito
     */
    public function update(Request $request, $id)
    {
        $inscrito = Inscrito::findOrFail($id);

        $request->validate([
            'nombres' => 'required|string|max:150',
            'apellidos' => 'required|string|max:150',
            'ci' => 'required|string|max:50|unique:inscrito,ci,' . $id,
            'telefono' => 'nullable|string|max:50',
            'correo' => 'required|email|max:150|unique:inscrito,correo,' . $id
        ], [
            'nombres.required' => 'El nombre es obligatorio',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'ci.required' => 'El CI es obligatorio',
            'ci.unique' => 'Este CI ya está registrado',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado'
        ]);

        try {
            $inscrito->update([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'correo' => $request->correo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscrito actualizado exitosamente',
                'inscrito' => $inscrito
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar inscrito: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar inscrito (también elimina sus asignaciones de cursos)
     */
    public function destroy($id)
    {
        try {
            $inscrito = Inscrito::findOrFail($id);

            // Las asignaciones se eliminan automáticamente por CASCADE en la BD
            $inscrito->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inscrito eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar inscrito: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear inscrito desde la vista de un curso específico
     */
    public function storeFromCurso(Request $request, $cursoId)
    {
        $request->validate([
            'nombres' => 'required|string|max:150',
            'apellidos' => 'required|string|max:150',
            'ci' => 'required|string|max:50|unique:inscrito,ci',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'required|email|max:150|unique:inscrito,correo'
        ], [
            'nombres.required' => 'El nombre es obligatorio',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'ci.required' => 'El CI es obligatorio',
            'ci.unique' => 'Este CI ya está registrado',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado'
        ]);

        try {
            // Verificar que el curso existe
            Curso::findOrFail($cursoId);

            DB::beginTransaction();

            // Crear inscrito
            $inscrito = Inscrito::create([
                'id' => Str::uuid(),
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'ci' => $request->ci,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'fecha_registro' => now()
            ]);

            // Asignar inscrito al curso
            CursoAsignado::create([
                'id' => Str::uuid(),
                'curso_id' => $cursoId,
                'entidad_id' => $inscrito->id,
                'entidad_tipo' => 'inscrito',
                'fecha_asignacion' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inscrito creado y asignado al curso exitosamente',
                'inscrito' => $inscrito
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear inscrito: ' . $e->getMessage()
            ], 500);
        }
    }
}
