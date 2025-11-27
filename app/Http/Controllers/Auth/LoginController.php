<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /*
    |--------------------------------------------------------------------------
    | Login API Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the API and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function loginAPI(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario por email
        $user = Usuario::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Crear token named "api-token"
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    protected function redirectTo()
    {
        return '/welcome';
    }

    /**
     *  Este método se encarga de redirigir después del login
     *  pero forzamos que siempre use redirectTo(), ignorando intended()
     *  esto porque queremos que siempre vaya a /welcome
     *  y sobreescribimos el método del trait AuthenticatesUsers
     *  aunque esto no es lo ideal, es una solución rápida.
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        // 👇 Forzar que siempre se use redirectTo(), ignorando intended()
        return redirect($this->redirectPath());
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
