<?php

namespace App\Models\Tenants;

use App\Traits\UsesTenantConnection;
use Laravel\Passport\AuthCode;

class PassportAuthCode extends AuthCode
{
    use UsesTenantConnection;
}