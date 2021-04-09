<?php

namespace Modules\UserManagementSystem\Entities\Client;

use Spatie\Permission\Models\Role as Model;
use App\Http\Filters\Filterable;
use App\Traits\UsesTenantConnection;
use Astrotomic\Translatable\Translatable;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Guard;

class Role extends Model
{
    use Filterable, UsesTenantConnection, SoftDeletes, LogsActivity, Translatable, CascadeSoftDeletes;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'guard_name'
    ];

    protected $cascadeDeletes = ['translations'];

    protected $casts = [
        'created_at' => 'timestamp'
    ];

    public $dropdownAttributes = [
        'id',
        'name'
    ];

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getPermissionNamesAttribute()
    {
        $permissions = $this
            ->permissions()
            ->pluck('name')
            ->toArray();

        foreach ($permissions as $key => $permission) {
            $permissions[$key] = __('usermanagementsystem/permissions.' . $permission, [], request()->header('Accept-Language'));
        }
        return implode(", ", $permissions);
    }

    /**
     * Override Spatie Permissions Create to handle Translations
     *
     * @param array $attributes
     * @return mixed
     * @author Mohannad Elemary
     */
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        return static::query()->create($attributes);
    }

    /**
     * Retreive the number of users associated with each role
     *
     * @param Builder $query
     * @return Builder
     * @author Mohannad Elemary
     */
    public function scopeWithUsersCount($query)
    {
        return $query->withCount('users');
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            TenantUser::class,
            'model',
            config('permission.table_names.model_has_roles'),
            'role_id',
            config('permission.column_names.model_morph_key')
        );
    }
}
