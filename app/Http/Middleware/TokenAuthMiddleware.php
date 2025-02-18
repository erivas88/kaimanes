<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenAuthMiddleware
{
    private $internalToken = 'mi_token_fijo_super_secreto';

    public function handle(Request $request, Closure $next)
    {
        // Permitir solicitudes internas (desde el mismo servidor)
        if ($this->isInternalRequest($request)) {
            return $next($request);
        }

        // Validar token para solicitudes externas
        $token = $request->header('X-Internal-Token');
        if ($token !== $this->internalToken) {
            return response()->json(['message' => 'No autorizado'], 401);
        }

        return $next($request);
    }

    private function isInternalRequest(Request $request)
    {
        $serverIp = $request->server('SERVER_ADDR');
        $clientIp = $request->server('REMOTE_ADDR');

        // Permitir si la IP cliente es localhost o 127.0.0.1 (interno)
        return in_array($clientIp, ['127.0.0.1','10.0.0.75' ,'::1']);
    }
}
