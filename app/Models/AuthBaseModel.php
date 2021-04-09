<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\HasModulesRelationsHandler;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;

class AuthBaseModel extends Authenticatable
{
    use Filterable, SoftDeletes, Notifiable, HasApiTokens, HasModulesRelationsHandler;
    // , LogsActivity;

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;
    protected static $ignoreChangedAttributes = ['password'];

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
     * Auto Hashing Passwords
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? bcrypt($value) : null;
    }
}
