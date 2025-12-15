<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    use AuthenticatesUsers;

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


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    protected function redirectTo()
    {
        return '/welcome';  // Ruta pública que funciona para auth y guest
    }

    /**
     * Attempt to log the user into the application.
     * Agregado logging para debugging en producción.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        Log::info('Login attempt', [
            'email' => $request->email,
            'has_password' => !empty($request->password),
            'session_id' => session()->getId(),
            'ip' => $request->ip(),
        ]);

        $result = $this->guard()->attempt(
            $this->credentials($request), 
            $request->filled('remember')
        );

        if ($result) {
            Log::info('Login successful', ['email' => $request->email]);
        } else {
            Log::warning('Login failed', ['email' => $request->email]);
        }

        return $result;
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

        Log::info('Login response sent', [
            'email' => $request->email,
            'redirect_to' => $this->redirectPath(),
        ]);

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
