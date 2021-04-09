<?php

namespace Modules\UserManagementSystem\Entities\Client;

use App\Http\Filters\Filterable;
use App\Traits\UsesTenantConnection;
use Spatie\Activitylog\Models\Activity as Model;

class Activity extends Model
{
    use Filterable, UsesTenantConnection;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

}