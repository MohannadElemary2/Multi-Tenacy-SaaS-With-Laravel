<?php

namespace Modules\Client\Entities\Client;

use App\Models\BaseModel;
use App\Traits\UsesTenantConnection;

class ProductHistoryActivity extends BaseModel
{
    use UsesTenantConnection;
    
    protected $table = 'product_history_activities';
    
    protected $fillable = [
        'day',
        'product_id',
    ];
}
