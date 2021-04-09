<?php

namespace App\Models\Tenants;

use App\Traits\UsesTenantConnection;
use Laravel\Passport\RefreshToken;

class PassportRefreshToken extends RefreshToken
{
    use UsesTenantConnection;
}