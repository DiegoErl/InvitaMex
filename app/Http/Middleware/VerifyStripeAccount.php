<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyStripeAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->hasStripeAccount()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Debes conectar tu cuenta de Stripe primero',
                    'redirect' => route('stripe.connect')
                ], 403);
            }
            
            return redirect()->route('stripe.connect')
                ->with('error', 'Debes conectar tu cuenta de Stripe para acceder a esta función');
        }

        return $next($request);
    }
}