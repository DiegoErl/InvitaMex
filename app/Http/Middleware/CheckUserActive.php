<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // NO verificar en la ruta de logout
        if ($request->is('logout') || $request->routeIs('logout')) {
            return $next($request);
        }

        // NO verificar en la ruta de suspensión
        if ($request->is('account-suspended') || $request->routeIs('account.suspended')) {
            return $next($request);
        }

        // Solo verificar si hay un usuario autenticado
        if (Auth::check()) {
            $user = Auth::user();
            
            // Verificar si el usuario está suspendido
            if (!$user->is_active) {
                // Si la petición es AJAX, retornar JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Tu cuenta ha sido suspendida',
                        'suspended' => true
                    ], 403);
                }
                
                // Si es una petición normal, redirigir a página de suspensión
                return redirect()->route('account.suspended');
            }
        }

        return $next($request);
    }
}