<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoAsignado;
use App\Models\CourseStage;
use App\Models\CourseResource;
use App\Models\Usuario;
use App\Models\ComunariosApoyo;
use App\Models\Inscrito;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'objetivos' => 'nullable|string',
            'inicio_programado' => 'required|date|after_or_equal:today',
            'fin_programado' => 'required|date|after_or_equal:inicio_programado',
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 200 caracteres.',
            'inicio_programado.required' => 'La fecha de inicio es obligatoria.',
            'fin_programado.required' => 'La fecha de finalización es obligatoria.',
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->check() ? auth()->user()->id : null;

            // Crear curso base
            $curso = Curso::create([
                'id' => Str::uuid()->toString(),
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'objetivos' => $validated['objetivos'] ?? null,
                'inicio_programado' => $validated['inicio_programado'],
                'fin_programado' => $validated['fin_programado'],
                // No manejamos estados lógicos complejos, simplemente se considera publicado
                'visibilidad' => 'usuarios',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            // Crear etapas (stages_order proviene del wizard)
            $stageModules = $request->input('stages_order', []);
            $totalStages = count($stageModules);

            foreach ($stageModules as $index => $moduleName) {
                $stageNumber = $index + 1;

                $stage = CourseStage::create([
                    'id' => Str::uuid()->toString(),
                    'curso_id' => $curso->id,
                    'stage_number' => $stageNumber,
                    'titulo_autogenerado' => 'Etapa ' . $stageNumber,
                    'module_name' => $moduleName,
                    'descripcion' => null,
                    'duracion_minutos' => null,
                    'delivery_mode' => null,
                    'is_final_stage' => $stageNumber === $totalStages,
                    'orden' => $stageNumber,
                ]);

                // Recursos asociados a la etapa (videos, documentos, lecturas, material extra)
                $key = $stageNumber;
                $videoUrl = $request->input("stages.$key.video");
                $readingUrl = $request->input("stages.$key.reading");
                $extraText = $request->input("stages.$key.extra");

                if ($videoUrl) {
                    CourseResource::create([
                        'id' => Str::uuid()->toString(),
                        'stage_id' => $stage->id,
                        'resource_type' => 'video',
                        'titulo' => 'Video ' . $stageNumber,
                        'resource_url' => $videoUrl,
                    ]);
                }

                if ($readingUrl) {
                    CourseResource::create([
                        'id' => Str::uuid()->toString(),
                        'stage_id' => $stage->id,
                        'resource_type' => 'lectura',
                        'titulo' => 'Lectura ' . $stageNumber,
                        'resource_url' => $readingUrl,
                    ]);
                }

                if ($extraText) {
                    CourseResource::create([
                        'id' => Str::uuid()->toString(),
                        'stage_id' => $stage->id,
                        'resource_type' => 'material_extra',
                        'titulo' => 'Material extra ' . $stageNumber,
                        'descripcion' => $extraText,
                    ]);
                }

                if ($request->hasFile("stages.$key.doc")) {
                    $file = $request->file("stages.$key.doc");
                    $path = $file->store('cursos/recursos', 'public');

                    CourseResource::create([
                        'id' => Str::uuid()->toString(),
                        'stage_id' => $stage->id,
                        'resource_type' => 'documento',
                        'titulo' => $file->getClientOriginalName(),
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('cursos.index')
                ->with('success', 'Curso creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        // Load course with stages and resources
        $curso = Curso::with(['stages.resources'])->findOrFail($id);

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
     * API: Permitir al usuario autenticado inscribirse al curso (para móvil)
     */
    public function apiInscribirme(Request $request, string $id)
    {
        try {
            // Verificar autenticación
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe iniciar sesión para inscribirse.'
                ], 401);
            }

            $curso = Curso::findOrFail($id);

            // Comprobar si ya está inscrito
            $existe = CursoAsignado::where('curso_id', $id)
                ->where('entidad_tipo', 'usuario')
                ->where('entidad_id', Auth::id())
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya estás inscrito en este curso',
                    'ya_inscrito' => true
                ], 400);
            }

            // Crear asignación
            CursoAsignado::create([
                'id' => Str::uuid()->toString(),
                'curso_id' => $id,
                'entidad_tipo' => 'usuario',
                'entidad_id' => Auth::id(),
                'fecha_asignacion' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Te has inscrito al curso correctamente',
                'curso' => [
                    'id' => $curso->id,
                    'nombre' => $curso->nombre,
                    'descripcion' => $curso->descripcion,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al inscribirte al curso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $curso = Curso::with(['stages.resources'])->findOrFail($id);
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $curso = Curso::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'objetivos' => 'nullable|string',
            'inicio_programado' => 'required|date|after_or_equal:today',
            'fin_programado' => 'required|date|after_or_equal:inicio_programado',
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->check() ? auth()->user()->id : null;

            // Actualizar datos base del curso
            $curso->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'objetivos' => $validated['objetivos'] ?? null,
                'inicio_programado' => $validated['inicio_programado'],
                'fin_programado' => $validated['fin_programado'],
                'updated_by' => $userId,
            ]);

            // Actualizar recursos por cada etapa existente
            $stages = $curso->stages()->orderBy('orden')->get();

            foreach ($stages as $stage) {
                $key = $stage->stage_number; // los campos vienen indexados por número de etapa

                $videoUrl = $request->input("stages.$key.video");
                $readingUrl = $request->input("stages.$key.reading");
                $extraText = $request->input("stages.$key.extra");

                // VIDEO
                $videoRes = $stage->resources()->where('resource_type', 'video')->first();
                if ($videoUrl) {
                    if (!$videoRes) {
                        $videoRes = new CourseResource([
                            'id' => Str::uuid()->toString(),
                            'resource_type' => 'video',
                            'titulo' => 'Video ' . $key,
                        ]);
                        $videoRes->stage_id = $stage->id;
                    }
                    $videoRes->resource_url = $videoUrl;
                    $videoRes->save();
                } elseif ($videoRes) {
                    $videoRes->delete();
                }

                // LECTURA
                $readingRes = $stage->resources()->where('resource_type', 'lectura')->first();
                if ($readingUrl) {
                    if (!$readingRes) {
                        $readingRes = new CourseResource([
                            'id' => Str::uuid()->toString(),
                            'resource_type' => 'lectura',
                            'titulo' => 'Lectura ' . $key,
                        ]);
                        $readingRes->stage_id = $stage->id;
                    }
                    $readingRes->resource_url = $readingUrl;
                    $readingRes->save();
                } elseif ($readingRes) {
                    $readingRes->delete();
                }

                // MATERIAL EXTRA
                $extraRes = $stage->resources()->where('resource_type', 'material_extra')->first();
                if ($extraText) {
                    if (!$extraRes) {
                        $extraRes = new CourseResource([
                            'id' => Str::uuid()->toString(),
                            'resource_type' => 'material_extra',
                            'titulo' => 'Material extra ' . $key,
                        ]);
                        $extraRes->stage_id = $stage->id;
                    }
                    $extraRes->descripcion = $extraText;
                    $extraRes->save();
                } elseif ($extraRes) {
                    $extraRes->delete();
                }

                // DOCUMENTO (archivo)
                $docRes = $stage->resources()->where('resource_type', 'documento')->first();
                if ($request->hasFile("stages.$key.doc")) {
                    // Borrar archivo anterior si existe
                    if ($docRes && $docRes->file_path) {
                        Storage::disk('public')->delete($docRes->file_path);
                    }

                    $file = $request->file("stages.$key.doc");
                    $path = $file->store('cursos/recursos', 'public');

                    if (!$docRes) {
                        $docRes = new CourseResource([
                            'id' => Str::uuid()->toString(),
                            'resource_type' => 'documento',
                        ]);
                        $docRes->stage_id = $stage->id;
                    }

                    $docRes->titulo = $file->getClientOriginalName();
                    $docRes->file_path = $path;
                    $docRes->save();
                }
            }

            DB::commit();

            return redirect()->route('cursos.index')
                ->with('success', 'Curso actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cursos.index')
                ->with('error', 'Error al actualizar el curso: ' . $e->getMessage())
                ->withInput();
        }
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

    /**
     * API: Obtener cursos para el mapa
     */
    public function api()
    {
        try {
            $cursos = Curso::with(['stages.resources'])
                ->withCount('cursos_asignados')
                ->get()
                ->map(function ($curso) {
                    return [
                        'id' => $curso->id,
                        'nombre' => $curso->nombre,
                        'descripcion' => $curso->descripcion,
                        'objetivos' => $curso->objetivos,
                        'fecha_inicio' => $curso->inicio_programado ? $curso->inicio_programado->format('Y-m-d') : null,
                        'fecha_fin' => $curso->fin_programado ? $curso->fin_programado->format('Y-m-d') : null,
                        'cantidad_asignados' => $curso->cursos_asignados_count,
                        'fecha_creacion' => $curso->creado ? $curso->creado->format('Y-m-d H:i:s') : null,
                        'etapas' => $curso->stages->map(function ($stage) {
                            return [
                                'id' => $stage->id,
                                'numero_etapa' => $stage->stage_number,
                                'titulo' => $stage->titulo_autogenerado,
                                'nombre_modulo' => $stage->module_name,
                                'descripcion' => $stage->descripcion,
                                'duracion_minutos' => $stage->duracion_minutos,
                                'modalidad' => $stage->delivery_mode,
                                'es_etapa_final' => $stage->is_final_stage,
                                'recursos' => $stage->resources->map(function ($resource) {
                                    return [
                                        'id' => $resource->id,
                                        'tipo' => $resource->resource_type,
                                        'titulo' => $resource->titulo,
                                        'url' => $resource->resource_url,
                                        'archivo' => $resource->file_path ? asset('storage/' . $resource->file_path) : null,
                                        'descripcion' => $resource->descripcion,
                                        'requiere_confirmacion' => $resource->requires_ack,
                                    ];
                                }),
                            ];
                        }),
                    ];
                });

            return response()->json($cursos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener cursos',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }
 
}
