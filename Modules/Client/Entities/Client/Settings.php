<?php

namespace Modules\Client\Entities\Client;

use App\Models\BaseModel;
use App\Traits\UsesTenantConnection;

class Settings extends BaseModel
{
    use UsesTenantConnection;
    
    protected $fillable = [
    	'value'
    ];
}
