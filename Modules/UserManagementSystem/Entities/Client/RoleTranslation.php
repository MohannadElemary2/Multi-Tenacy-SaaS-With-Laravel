<?php

namespace Modules\UserManagementSystem\Entities\Client;

use App\Models\BaseModel;

class RoleTranslation extends BaseModel
{
    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];
}
