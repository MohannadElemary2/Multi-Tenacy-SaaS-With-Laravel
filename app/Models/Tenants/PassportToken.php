<?php

namespace App\Models\Tenants;

use App\Traits\UsesTenantConnection;
use Laravel\Passport\Token;

class PassportToken extends Token
{
    use UsesTenantConnection;
}