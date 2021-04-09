<?php

namespace Modules\UserManagementSystem\Filters;

use App\Http\Filters\Filter;

class RoleFilter extends Filter
{
    public function name($value = null)
    {
        return $this->builder->whereTranslationLike('name', "%$value%");
    }
}
