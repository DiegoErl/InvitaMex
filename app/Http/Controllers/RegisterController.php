<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered; // ⭐ AGREGAR

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
{
    // Validación
    $validator = Validator::make($request->all(), [
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:2',
        'password' => 'required|string|min:8|confirmed',
        'terms' => 'required|accepted',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Crear usuario
    $user = User::create([
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'email' => $request->email,
        'phone' => $request->phone,
        'country' => $request->country,
        'password' => Hash::make($request->password),
    ]);

    // Enviar email de verificación
    event(new Registered($user));

    // Login automático
    Auth::login($user);

    // ⭐ SIEMPRE devolver JSON, nada de redirects
    return response()->json([
        'success' => true,
        'message' => '¡Bienvenido a InvitaCleth, ' . $user->firstName . '!',
        'redirect' => route('perfil'),
        'user' => [
            'id' => $user->id,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email' => $user->email,
        ]
    ], 201);
}

}
