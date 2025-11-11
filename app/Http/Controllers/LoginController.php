<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir al perfil
        if (Auth::check()) {
            return redirect()->route('perfil');
        }
        
        return view('login');
    }

    public function login(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingresa un email válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Intentar autenticar
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => '¡Bienvenido de nuevo, ' . Auth::user()->firstName . '!',
                'redirect' => route('perfil')
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Las credenciales no coinciden con nuestros registros.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        // Invalidar la sesión completamente
        $request->session()->invalidate();
        
        // Regenerar el token CSRF
        $request->session()->regenerateToken();

        // Redirigir al login con headers para prevenir cache
        return redirect()->route('login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}