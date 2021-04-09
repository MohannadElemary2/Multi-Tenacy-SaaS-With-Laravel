<?php

namespace App\Http\Middleware;

use Closure;

class Swagger
{
    public function handle($request, Closure $next)
    {
        if($request->jsonFile == 'tenant.json' || $request->d == 'tenant') {
            $this->configSwagger();
        }
        return $next($request);
    }

    protected function configSwagger()
    {
        config(['l5-swagger.paths.docs_json' => 'tenant.json']);
        config(['l5-swagger.paths.docs' => storage_path('api-tenant-docs')]);
    }
}