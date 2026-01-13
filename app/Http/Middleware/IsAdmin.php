<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        // Verificar si el usuario es admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta área. Solo administradores.');
        }

        return $next($request);
    }
}