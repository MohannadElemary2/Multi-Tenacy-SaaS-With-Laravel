<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait HasModulesRelationsHandler
{
    /**
     * Overriding Laravel's Relations Behaviour as The Relation Can not
     * Be existing as its module cannot be exists
     *
     * @param string $method
     * @return mixed
     * @author Mohannad Elemary
     */
    protected function getRelationshipFromMethod($method)
    {
        $relation = $this->$method();

        // If The relation is returing null or empty array, its module is not exists
        // And if it's array, the corresponding relation is returing a collection. So,
        // return an empty one.
        if (!$relation) {
            if (is_array($relation)) {
                return new Collection();
            }
                
            return;
        }

        return parent::getRelationshipFromMethod($method);
    }

    /**
     * Override belongsTo relation behaviour
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $ownerKey
     * @param string $relation
     * @return BelongsTo
     * @author Mohannad Elemary
     */
    public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        if (!class_exists($related)) {
            return null;
        }

        return parent::belongsTo($related, $foreignKey, $ownerKey, $relation);
    }

    /**
     * Override hasMany relation behaviour
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $localKey
     * @return HasMany
     * @author Mohannad Elemary
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        if (!class_exists($related)) {
            return [];
        }

        return parent::hasMany($related, $foreignKey, $localKey);
    }

    /**
     * Override belongsToMany relation behaviour
     *
     * @param string $related
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string $parentKey
     * @param string $relatedKey
     * @param string $relation
     * @return BelongsToMany
     * @author Mohannad Elemary
     */
    public function belongsToMany(
        $related,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $relation = null
    ) {
        if (!class_exists($related)) {
            return [];
        }

        return parent::belongsToMany(
            $related,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relation
        );
    }
}
