<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoAsignado;
use App\Models\Usuario;
use App\Models\ComunariosApoyo;
use App\Models\Inscrito;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Curso::withCount(['cursos_asignados'])
            ->orderBy('creado', 'desc')
            ->paginate(10);

        return view('cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cursos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 200 caracteres.',
        ]);

        try {
            Curso::create([
                'id' => Str::uuid()->toString(),
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
            ]);

            return redirect()->route('cursos.index')
                ->with('success', 'Curso creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.index')
                ->with('error', 'Error al crear el curso: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $curso = Curso::findOrFail($id);

        // Obtener asignaciones sin relaciones eager loaded
        // Las cargaremos dinámicamente en la vista
        $asignaciones = CursoAsignado::where('curso_id', $id)
            ->orderBy('fecha_asignacion', 'desc')
            ->paginate(15);

        // Procesar asignaciones para cargar las entidades correctas
        $asignaciones->getCollection()->transform(function ($asignacion) {
            if ($asignacion->entidad_tipo === 'usuario') {
                $asignacion->entidad = Usuario::find($asignacion->entidad_id);
            } elseif ($asignacion->entidad_tipo === 'comunario') {
                $asignacion->entidad = ComunariosApoyo::find($asignacion->entidad_id);
            } else {
                $asignacion->entidad = Inscrito::find($asignacion->entidad_id);
            }
            return $asignacion;
        });

        // Determinar si el usuario autenticado ya está asignado como 'usuario'
        $authUsuarioAsignado = false;
        if (Auth::check()) {
            $authUsuarioAsignado = CursoAsignado::where('curso_id', $id)
                ->where('entidad_tipo', 'usuario')
                ->where('entidad_id', Auth::id())
                ->exists();
        }

        return view('cursos.show', compact('curso', 'asignaciones', 'authUsuarioAsignado'));
    }

    /**
     * Permitir al usuario autenticado auto-inscribirse al curso
     */
    public function inscribirme(Request $request, string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('cursos.show', $id)->with('error', 'Debe iniciar sesión para inscribirse.');
        }

        try {
            $curso = Curso::findOrFail($id);

            // Comprobar duplicado
            $existe = CursoAsignado::where('curso_id', $id)
                ->where('entidad_tipo', 'usuario')
                ->where('entidad_id', Auth::id())
                ->exists();

            if ($existe) {
                return redirect()->route('cursos.show', $id)
                    ->with('info', 'Ya estás inscrito en este curso');
            }

            // Crear asignación
            CursoAsignado::create([
                'id' => Str::uuid()->toString(),
                'curso_id' => $id,
                'entidad_tipo' => 'usuario',
                'entidad_id' => Auth::id(),
                'fecha_asignacion' => now()
            ]);

            return redirect()->route('cursos.show', $id)->with('success', 'Te has inscrito al curso correctamente');
        } catch (\Exception $e) {
            return redirect()->route('cursos.show', $id)
                ->with('error', 'Error al inscribirte: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $curso = Curso::findOrFail($id);
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $curso = Curso::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
        ]);

        $curso->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('cursos.index')
            ->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $curso = Curso::findOrFail($id);

            // Las asignaciones se eliminan en cascada por la FK
            $curso->delete();

            return redirect()->route('cursos.index')
                ->with('success', 'Curso eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.index')
                ->with('error', 'Error al eliminar el curso: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para asignar curso
     */
    public function asignar(string $id)
    {
        $curso = Curso::findOrFail($id);

        // Obtener usuarios activos
        $usuarios = Usuario::whereHas('estados_sistema', function ($query) {
            $query->where('codigo', 'ACTIVO');
        })
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        // Obtener comunarios
        $comunarios = ComunariosApoyo::orderBy('nombre')->get();

        // Obtener inscritos
        $inscritos = Inscrito::orderBy('nombres')->orderBy('apellidos')->get();

        // Obtener IDs ya asignados
        $usuariosAsignados = CursoAsignado::where('curso_id', $id)
            ->where('entidad_tipo', 'usuario')
            ->pluck('entidad_id')
            ->toArray();

        $comunariosAsignados = CursoAsignado::where('curso_id', $id)
            ->where('entidad_tipo', 'comunario')
            ->pluck('entidad_id')
            ->toArray();

        $inscritosAsignados = CursoAsignado::where('curso_id', $id)
            ->where('entidad_tipo', 'inscrito')
            ->pluck('entidad_id')
            ->toArray();

        return view('cursos.asignar', compact(
            'curso',
            'usuarios',
            'comunarios',
            'inscritos',
            'usuariosAsignados',
            'comunariosAsignados',
            'inscritosAsignados'
        ));
    }

    /**
     * Asignar curso a una entidad (usuario, comunario o inscrito)
     */
    public function storeAsignacion(Request $request, string $id)
    {
        $curso = Curso::findOrFail($id);

        $request->validate([
            'entidad_tipo' => 'required|in:usuario,comunario,inscrito',
        ]);

        try {
            DB::beginTransaction();

            // Si es inscrito, verificar si es nuevo o existente
            if ($request->entidad_tipo === 'inscrito') {
                // Verificar si se envió un ID de inscrito existente
                if ($request->has('entidad_id') && !empty($request->entidad_id)) {
                    // Es un inscrito existente
                    $entidadId = $request->entidad_id;

                    // Verificar que no exista ya la asignación
                    $existe = CursoAsignado::where('curso_id', $id)
                        ->where('entidad_tipo', 'inscrito')
                        ->where('entidad_id', $entidadId)
                        ->exists();

                    if ($existe) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Este inscrito ya tiene asignado este curso.');
                    }

                    // Validar que el inscrito exista
                    $inscrito = Inscrito::find($entidadId);
                    if (!$inscrito) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'El inscrito seleccionado no existe.');
                    }
                } else {
                    // Es un inscrito nuevo, validar campos y crear
                    $request->validate([
                        'inscrito_nombres' => 'required|string|max:150',
                        'inscrito_apellidos' => 'required|string|max:150',
                        'inscrito_ci' => 'required|string|max:50|unique:inscrito,ci',
                        'inscrito_telefono' => 'nullable|string|max:50',
                        'inscrito_correo' => 'required|email|max:150|unique:inscrito,correo',
                    ], [
                        'inscrito_nombres.required' => 'El nombre del inscrito es obligatorio',
                        'inscrito_apellidos.required' => 'Los apellidos del inscrito son obligatorios',
                        'inscrito_ci.required' => 'El CI del inscrito es obligatorio',
                        'inscrito_ci.unique' => 'Este CI ya está registrado',
                        'inscrito_correo.required' => 'El correo del inscrito es obligatorio',
                        'inscrito_correo.email' => 'El correo debe ser válido',
                        'inscrito_correo.unique' => 'Este correo ya está registrado',
                    ]);

                    // Crear inscrito
                    $inscrito = Inscrito::create([
                        'id' => Str::uuid()->toString(),
                        'nombres' => $request->inscrito_nombres,
                        'apellidos' => $request->inscrito_apellidos,
                        'ci' => $request->inscrito_ci,
                        'telefono' => $request->inscrito_telefono,
                        'correo' => $request->inscrito_correo,
                        'fecha_registro' => now()
                    ]);

                    $entidadId = $inscrito->id;
                }
            } else {
                // Para usuario o comunario, validar que exista el ID
                $request->validate([
                    'entidad_id' => 'required|uuid',
                ]);

                // Verificar que no exista ya la asignación
                $existe = CursoAsignado::where('curso_id', $id)
                    ->where('entidad_tipo', $request->entidad_tipo)
                    ->where('entidad_id', $request->entidad_id)
                    ->exists();

                if ($existe) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Esta entidad ya tiene asignado este curso.');
                }

                // Validar que la entidad exista
                if ($request->entidad_tipo === 'usuario') {
                    $entidad = Usuario::find($request->entidad_id);
                } else {
                    $entidad = ComunariosApoyo::find($request->entidad_id);
                }

                if (!$entidad) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'La entidad seleccionada no existe.');
                }

                $entidadId = $request->entidad_id;
            }

            // Crear la asignación
            CursoAsignado::create([
                'id' => Str::uuid()->toString(),
                'curso_id' => $id,
                'entidad_tipo' => $request->entidad_tipo,
                'entidad_id' => $entidadId,
            ]);

            DB::commit();

            // Mensaje de éxito según el tipo de asignación
            if ($request->entidad_tipo === 'inscrito') {
                $mensaje = ($request->has('entidad_id') && !empty($request->entidad_id))
                    ? 'Inscrito asignado al curso exitosamente.'
                    : 'Inscrito creado y asignado al curso exitosamente.';
            } else {
                $mensaje = 'Curso asignado exitosamente.';
            }

            return redirect()->route('cursos.asignar', $id)
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al asignar curso: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remover asignación de curso
     */
    public function removerAsignacion(string $cursoId, string $asignacionId)
    {
        Log::info('Intentando remover asignación', [
            'curso_id' => $cursoId,
            'asignacion_id' => $asignacionId
        ]);

        try {
            $asignacion = CursoAsignado::where('id', $asignacionId)
                ->where('curso_id', $cursoId)
                ->first();

            if (!$asignacion) {
                Log::warning('Asignación no encontrada');
                return redirect()->back()
                    ->with('error', 'Asignación no encontrada.');
            }

            Log::info('Asignación encontrada, eliminando...', [
                'asignacion' => $asignacion->toArray()
            ]);

            $asignacion->delete();

            Log::info('Asignación eliminada exitosamente');

            return redirect()->route('cursos.show', $cursoId)
                ->with('success', 'Asignación removida exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al remover asignación', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Error al remover la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Ver cursos de un usuario específico
     */
    public function cursosUsuario(string $usuarioId)
    {
        $usuario = Usuario::findOrFail($usuarioId);

        $cursosAsignados = CursoAsignado::where('entidad_tipo', 'usuario')
            ->where('entidad_id', $usuarioId)
            ->with('curso')
            ->orderBy('fecha_asignacion', 'desc')
            ->paginate(10);

        return view('cursos.usuario', compact('usuario', 'cursosAsignados'));
    }

    /**
     * Ver cursos de un comunario específico
     */
    public function cursosComunario(string $comunarioId)
    {
        $comunario = ComunariosApoyo::findOrFail($comunarioId);

        $cursosAsignados = CursoAsignado::where('entidad_tipo', 'comunario')
            ->where('entidad_id', $comunarioId)
            ->with('curso')
            ->orderBy('fecha_asignacion', 'desc')
            ->paginate(10);

        return view('cursos.comunario', compact('comunario', 'cursosAsignados'));
    }
}
