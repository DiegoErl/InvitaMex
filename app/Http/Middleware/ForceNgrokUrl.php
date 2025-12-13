<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceNgrokUrl
{
    public function handle(Request $request, Closure $next)
    {
        // Forzar el esquema HTTPS
        if ($request->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        // Forzar el root URL correcto
        if ($request->header('X-Forwarded-Host')) {
            URL::forceRootUrl('https://' . $request->header('X-Forwarded-Host'));
        }

        return $next($request);
    }
}