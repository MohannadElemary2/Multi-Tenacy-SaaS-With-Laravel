<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply all relevant filters.
     *
     * @param  Builder $query
     * @param  Filter  $filter
     * @return Builder
     */
    public function scopeFilter(Builder $query, Filter $filter): Builder
    {
        return $filter->apply($query);
    }
}
