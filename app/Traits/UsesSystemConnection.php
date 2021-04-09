<?php

namespace App\Traits;

use Hyn\Tenancy\Database\Connection;
use Illuminate\Support\Facades\App;

trait UsesSystemConnection
{
    public function getConnectionName()
    {
        if (App::runningUnitTests()) {
            return config('database.testing');
        }
        return app(Connection::class)->systemName();
    }
}