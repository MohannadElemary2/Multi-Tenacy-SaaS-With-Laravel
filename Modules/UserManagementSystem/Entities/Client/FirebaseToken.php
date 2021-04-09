<?php

namespace Modules\UserManagementSystem\Entities\Client;

use App\Models\AuthBaseModel;
use App\Traits\UsesTenantConnection;

class FirebaseToken extends AuthBaseModel
{
    use UsesTenantConnection;

    protected $fillable = [
        'user_id',
        'lang',
        'token',
        'platform',
    ];
}
