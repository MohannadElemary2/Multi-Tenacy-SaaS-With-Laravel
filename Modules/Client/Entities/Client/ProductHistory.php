<?php

namespace Modules\Client\Entities\Client;

use App\Models\BaseModel;
use App\Traits\UsesTenantConnection;

class ProductHistory extends BaseModel
{
    use UsesTenantConnection;
    
    protected $table = 'products_history';

    protected $fillable = [
        'day',
        'active_products',
    ];
}
