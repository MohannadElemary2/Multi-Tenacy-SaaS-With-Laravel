<?php

namespace Modules\Admin\Filters;

use App\Http\Filters\Filter;

class SettingsFilter extends Filter
{
    /**
     * Filter Settings by group
     *
     * @param string $value
     * @return Builder
     * @author Mohannad Elemary
     */
    public function group($value = null)
    {
        return $this->builder->where('group', '=', $value);
    }
}
