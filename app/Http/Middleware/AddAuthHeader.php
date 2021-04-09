<?php

namespace App\Http\Middleware;

use Closure;

class AddAuthHeader
{
    public function handle($request, Closure $next)
    {
        if (!$request->bearerToken()) {
            if ($request->hasCookie('_token')) {
                $token = $request->cookie('_token');
                $request->headers->add(['Authorization' => 'Bearer ' . $token]);
            }
        }

        return $next($request);
    }
}
