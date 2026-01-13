<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirigir al usuario a Google para autenticación
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtener información del usuario de Google
     */
    public function handleGoogleCallback()
    {
        try {
            // Obtener datos del usuario de Google
            $googleUser = Socialite::driver('google')->user();
            
            // Buscar si el usuario ya existe por google_id
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if ($user) {
                // Usuario ya registrado con Google - solo hacer login
                Auth::login($user, true);
                
                return redirect()->intended(route('perfil'))
                    ->with('success', '¡Bienvenido de nuevo, ' . $user->firstName . '!');
            }
            
            // Verificar si existe un usuario con ese email (registrado por formulario)
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Usuario existe pero se registró con formulario
                // Vincular cuenta de Google y verificar email si no estaba verificado
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => $existingUser->email_verified_at ?? now(), // Verificar si no estaba
                ]);
                
                // ⭐ MARCAR COMO VERIFICADO si no lo estaba
                if (!$existingUser->hasVerifiedEmail()) {
                    $existingUser->markEmailAsVerified();
                }
                
                Auth::login($existingUser, true);
                
                return redirect()->intended(route('perfil'))
                    ->with('success', '¡Cuenta de Google vinculada exitosamente!');
            }
            
            // Usuario completamente nuevo - crear cuenta
            $nameParts = explode(' ', $googleUser->getName(), 2);
            $firstName = $nameParts[0] ?? 'Usuario';
            $lastName = $nameParts[1] ?? 'Google';
            
            $newUser = User::create([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null, // No tiene contraseña (usa Google)
                'email_verified_at' => now(), // ⭐ Email ya verificado por Google
                'role' => 'user',
                'is_active' => true,
            ]);
            
            // 
            $newUser->markEmailAsVerified();
            
            Auth::login($newUser, true);
            
            return redirect()->intended(route('perfil'))
                ->with('success', '¡Bienvenido a InvitaCleth, ' . $newUser->firstName . '!');
                
        } catch (Exception $e) {
            Log::error('Error en Google Login: ' . $e->getMessage());
            
            return redirect()->route('login')
                ->with('error', 'Hubo un error al iniciar sesión con Google. Intenta de nuevo.');
        }
    }
}