<?php

namespace App\Models\Tenants;

use App\Traits\UsesTenantConnection;
use Laravel\Passport\PersonalAccessClient;

class PassportPersonalAccessClient extends PersonalAccessClient
{
    use UsesTenantConnection;
}