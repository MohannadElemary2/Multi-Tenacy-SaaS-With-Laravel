<?php

namespace Modules\Client\Entities\Client;

use App\Models\BaseModel;
use App\Traits\UsesTenantConnection;

class HubHistory extends BaseModel
{
    use UsesTenantConnection;

    protected $table = 'hubs_history';
    
    protected $fillable = [
        'day',
        'hubs_count',
    ];
}
