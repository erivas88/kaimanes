<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectTokenMiddleware
{
    private $internalToken = 'mi_token_fijo_super_secreto';

    public function handle(Request $request, Closure $next)
    {
        // Solo insertar el token si la solicitud es interna (AJAX)
        if ($request->ajax() || $request->wantsJson()) {
            $request->headers->set('X-Internal-Token', $this->internalToken);
        }

        return $next($request);
    }
}
