<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/welcome';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'ci' => ['required', 'string', 'max:20', 'unique:usuarios,ci'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'genero_id' => ['required', 'uuid', 'exists:generos,id'],
            'tipo_sangre_id' => ['required', 'uuid', 'exists:tipos_sangre,id'],
            'rol_id' => [
                'required',
                'uuid',
                'exists:legacy_roles,id',
                function ($attribute, $value, $fail) {
                    $role = \App\Models\Role::find($value);
                    if (!$role || !in_array($role->codigo, ['BOMBERO', 'PARAMEDICO', 'VETERINARIO'])) {
                        $fail('El rol seleccionado no es válido.');
                    }
                },
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Usuario
     */
    protected function create(array $data)
    {
        return Usuario::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'ci' => $data['ci'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'genero_id' => $data['genero_id'],
            'tipo_sangre_id' => $data['tipo_sangre_id'],
            'rol_id' => $data['rol_id'],
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'debe_cambiar_password' => true,
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        $request->session()->regenerate();
        $request->session()->save();

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     * Regenerate session to ensure user stays logged in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $request->session()->regenerate();
        $request->session()->save();
    }
}
