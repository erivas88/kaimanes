<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Permitir todas las solicitudes sin restricciones.
        return $next($request);
    }
}
