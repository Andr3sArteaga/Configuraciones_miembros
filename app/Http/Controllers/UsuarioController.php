<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Genero;
use App\Models\TiposSangre;
use App\Models\NivelesEntrenamiento;
use App\Models\Role;
use App\Models\EstadosSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::with(['genero', 'estados_sistema', 'role', 'niveles_entrenamiento'])
            ->orderBy('creado', 'desc')
            ->paginate(20);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $generos = Genero::where('activo', true)->get();
        $tiposSangre = TiposSangre::where('activo', true)->get();
        $nivelesEntrenamiento = NivelesEntrenamiento::where('activo', true)->orderBy('orden')->get();
        $roles = Role::where('activo', true)->get();
        $estados = EstadosSistema::where('tabla', 'usuarios')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('usuarios.create', compact('generos', 'tiposSangre', 'nivelesEntrenamiento', 'roles', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:usuarios,ci',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero_id' => 'required|uuid|exists:generos,id',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|max:150|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
            'tipo_sangre_id' => 'nullable|uuid|exists:tipos_sangre,id',
            'nivel_entrenamiento_id' => 'nullable|uuid|exists:niveles_entrenamiento,id',
            'entidad_perteneciente' => 'nullable|string|max:200',
            'rol_id' => 'required|uuid|exists:legacy_roles,id',
            'estado_id' => 'required|uuid|exists:estados_sistema,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'ci.required' => 'El CI es obligatorio',
            'ci.unique' => 'Este CI ya está registrado',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'genero_id.required' => 'Debe seleccionar un género',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'rol_id.required' => 'Debe seleccionar un rol',
            'estado_id.required' => 'Debe seleccionar un estado',
        ]);

        $usuario = new Usuario();
        $usuario->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $usuario->nombre = $validated['nombre'];
        $usuario->apellido = $validated['apellido'];
        $usuario->ci = $validated['ci'];
        $usuario->fecha_nacimiento = $validated['fecha_nacimiento'];
        $usuario->genero_id = $validated['genero_id'];
        $usuario->telefono = $validated['telefono'];
        $usuario->email = $validated['email'];
        $usuario->password = Hash::make($validated['password']);
        $usuario->tipo_sangre_id = $validated['tipo_sangre_id'];
        $usuario->nivel_entrenamiento_id = $validated['nivel_entrenamiento_id'];
        $usuario->entidad_perteneciente = $validated['entidad_perteneciente'];
        $usuario->rol_id = $validated['rol_id'];
        $usuario->estado_id = $validated['estado_id'];
        $usuario->debe_cambiar_password = true;

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Usuario::with([
            'genero',
            'tipos_sangre',
            'niveles_entrenamiento',
            'role',
            'estados_sistema',
            'miembros_equipos.equipo'
        ])->findOrFail($id);

        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);

        $generos = Genero::where('activo', true)->get();
        $tiposSangre = TiposSangre::where('activo', true)->get();
        $nivelesEntrenamiento = NivelesEntrenamiento::where('activo', true)->orderBy('orden')->get();
        $roles = Role::where('activo', true)->get();
        $estados = EstadosSistema::where('tabla', 'usuarios')
            ->where('activo', true)
            ->orderBy('orden')
            ->get();

        return view('usuarios.edit', compact('usuario', 'generos', 'tiposSangre', 'nivelesEntrenamiento', 'roles', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:usuarios,ci,' . $id,
            'fecha_nacimiento' => 'required|date|before:today',
            'genero_id' => 'required|uuid|exists:generos,id',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|max:150|unique:usuarios,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'tipo_sangre_id' => 'nullable|uuid|exists:tipos_sangre,id',
            'nivel_entrenamiento_id' => 'nullable|uuid|exists:niveles_entrenamiento,id',
            'entidad_perteneciente' => 'nullable|string|max:200',
            'rol_id' => 'required|uuid|exists:legacy_roles,id',
            'estado_id' => 'required|uuid|exists:estados_sistema,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'ci.required' => 'El CI es obligatorio',
            'ci.unique' => 'Este CI ya está registrado',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'genero_id.required' => 'Debe seleccionar un género',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'rol_id.required' => 'Debe seleccionar un rol',
            'estado_id.required' => 'Debe seleccionar un estado',
        ]);

        $usuario->nombre = $validated['nombre'];
        $usuario->apellido = $validated['apellido'];
        $usuario->ci = $validated['ci'];
        $usuario->fecha_nacimiento = $validated['fecha_nacimiento'];
        $usuario->genero_id = $validated['genero_id'];
        $usuario->telefono = $validated['telefono'];
        $usuario->email = $validated['email'];

        // Solo actualizar password si se proporcionó uno nuevo
        if ($request->filled('password')) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->tipo_sangre_id = $validated['tipo_sangre_id'];
        $usuario->nivel_entrenamiento_id = $validated['nivel_entrenamiento_id'];
        $usuario->entidad_perteneciente = $validated['entidad_perteneciente'];
        $usuario->rol_id = $validated['rol_id'];
        $usuario->estado_id = $validated['estado_id'];

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $nombre = $usuario->nombre . ' ' . $usuario->apellido;

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', "Usuario '{$nombre}' eliminado exitosamente");
    }

    /**
     * Cambiar estado del usuario (activar/desactivar)
     */
    public function cambiarEstado(Request $request, string $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'estado_id' => 'required|uuid|exists:estados_sistema,id',
        ]);

        $usuario->estado_id = $validated['estado_id'];
        $usuario->save();

        $estado = EstadosSistema::find($validated['estado_id']);

        return redirect()
            ->back()
            ->with('success', "Estado del usuario cambiado a '{$estado->nombre}' exitosamente");
    }

    /**
     * Reset password
     */
    public function resetPassword(string $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Generar password temporal basado en CI
        $passwordTemporal = 'Temp' . $usuario->ci;

        $usuario->password = Hash::make($passwordTemporal);
        $usuario->debe_cambiar_password = true;
        $usuario->save();

        return redirect()
            ->back()
            ->with('success', "Contraseña reseteada. Nueva contraseña temporal: {$passwordTemporal}");
    }

    /**
     * Get list of all user CIs for API Gateway
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCiList()
    {
        try {
            $cis = Usuario::pluck('ci')->toArray();
            
            return response()->json([
                'lista_ci' => $cis,
                'total' => count($cis)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la lista de CIs',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
