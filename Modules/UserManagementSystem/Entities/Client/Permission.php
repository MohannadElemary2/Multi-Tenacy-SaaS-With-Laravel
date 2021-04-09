<?php

namespace Modules\UserManagementSystem\Entities\Client;

use Spatie\Permission\Models\Permission as Model;
use App\Http\Filters\Filterable;
use App\Traits\UsesTenantConnection;

class Permission extends Model
{
    use Filterable, UsesTenantConnection;

    protected $appends = [
        'translated_name'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Return Translated name of the static permissions
     *
     * @return string
     * @author Mohannad Elemary
     */
    public function getTranslatedNameAttribute()
    {
        return __('usermanagementsystem/permissions.' . $this->name, [], request()->header('Accept-Language'));
    }
}
