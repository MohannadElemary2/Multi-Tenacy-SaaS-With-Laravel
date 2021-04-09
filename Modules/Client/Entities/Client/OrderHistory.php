<?php

namespace Modules\Client\Entities\Client;

use App\Models\BaseModel;
use App\Traits\UsesTenantConnection;

class OrderHistory extends BaseModel
{
    use UsesTenantConnection;

    protected $table = 'orders_history';
    
    protected $fillable = [
        'day',
        'orders_count',
    ];
}
