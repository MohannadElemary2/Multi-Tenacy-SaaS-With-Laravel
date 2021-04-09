<?php

namespace App\Traits;

use Hyn\Tenancy\Database\Connection;
use Illuminate\Support\Facades\App;

trait UsesTenantConnection
{
    public function getConnectionName()
    {
        if (App::runningUnitTests()) {
            return config('database.testing_tenant');
        }
        return app(Connection::class)->tenantName();
    }
} 