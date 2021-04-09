<?php

namespace App\Models\Tenants;

use App\Traits\UsesTenantConnection;
use Laravel\Passport\Client;

class PassportClient extends Client
{
    use UsesTenantConnection;
}