<?php

namespace Modules\Client\Entities\System;

use App\Models\BaseModel;
use App\Traits\UsesSystemConnection;
use Modules\Client\Events\ClientAdded;

class Client extends BaseModel
{
    use UsesSystemConnection;

    protected $dispatchesEvents = [
        'created' => ClientAdded::class,
    ];

    protected $fillable = [
        'company_name',
        'email',
        'phone',
        'domain',
        'is_active',
        'website_id',
    ];
}
