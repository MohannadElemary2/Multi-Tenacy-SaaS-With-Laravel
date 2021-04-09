<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\HasModulesRelationsHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class BaseModel extends Model
{
    use Filterable, SoftDeletes, HasModulesRelationsHandler;
    //  , LogsActivity;

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected static function boot()
    {
        parent::boot();
    }

    /**
     * Log Every Activity Happening to Any Model When Create / Update / Delete
     * Check If The Module That Having The Action is On The Whitelist
     *
     * @return void
     * @author Mohannad Elemary
     */
    protected static function booted()
    {
        static::creating(
            function ($model) {
                if (!in_array(get_class($model), config('activitylog.allowed_models_to_log'))) {
                    if (method_exists($model, 'disableLogging')) {
                        $model->disableLogging();
                    }
                }
            }
        );

        static::updating(
            function ($model) {
                if (!in_array(get_class($model), config('activitylog.allowed_models_to_log'))) {
                    if (method_exists($model, 'disableLogging')) {
                        $model->disableLogging();
                    }
                }
            }
        );

        static::deleting(
            function ($model) {
                if (!in_array(get_class($model), config('activitylog.allowed_models_to_log'))) {
                    if (method_exists($model, 'disableLogging')) {
                        $model->disableLogging();
                    }
                }
            }
        );
    }

    /**
     * Save model without firing events
     * @param array $options
     * @return mixed
     * @author Mohannad Elemary
     */
    public function saveWithoutEvents(array $options=[])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    public function getLogIdentifierAttribute()
    {
        return $this->name;
    }

    public function scopeAddScopes($query, $scopes)
    {
        foreach ($scopes as $scope) {
            $query->{$scope}();
        }
        
        return $query;
    }
}
