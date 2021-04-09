<?php

namespace Modules\UserManagementSystem\Entities\Client;

use App\Models\AuthBaseModel;
use App\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Passport\HasApiTokens;
use Modules\Hub\Entities\Client\Hub;
use Modules\Hub\Repositories\HubLocationRepository;
use Modules\Picking\Entities\Client\Batch;
use Modules\Picking\Entities\Client\DispatchingStation;
use Modules\Picking\Enums\BatchStatus;
use Modules\UserManagementSystem\Events\TenantUserAdded;
use Modules\UserManagementSystem\Notifications\PasswordChangedNotification;
use Modules\UserManagementSystem\Notifications\ResetPasswordNotification;
use Modules\UserManagementSystem\Notifications\VerifyEmail;
use Spatie\Permission\Traits\HasRoles;

class TenantUser extends AuthBaseModel
{
    use UsesTenantConnection, HasRoles, HasApiTokens;

    protected $table = "users";

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'time_zone',
        'is_super',
        'all_hubs',
        'last_login_at',
        'is_active',
        'created_by_id',
        'locale',
        'is_setup_wizard_finished'
    ];

    protected $casts = [
        'created_at'    => 'timestamp',
        'updated_at'    => 'timestamp',
        'last_login_at' => 'timestamp',
    ];

    protected $dispatchesEvents = [
        'created' => TenantUserAdded::class,
    ];


    /**
     * Send the email verification notification.
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function sendEmailVerificationNotification($domain = null, $companyName = null)
    {
        $this->notify(new VerifyEmail($domain, $companyName));
    }


    public function createdBy()
    {
        return $this->belongsTo(TenantUser::class, 'created_by_id')->withTrashed();
    }

    /**
     * User May Manage Hubs
     *
     * @return BelongsToMany
     * @author Mohannad Elemary
     */
    public function hubs()
    {
        return $this->belongsToMany(Hub::class, 'user_hubs', 'user_id', 'hub_id')->withTranslation();
    }

    /**
     * User Has Multiple Firebase Tokens
     *
     * @return HasMany
     * @author Mohannad Elemary
     */
    public function firebaseTokens()
    {
        return $this->hasMany(FirebaseToken::class, 'user_id');
    }

    /**
     * Check If User Is Managing a Specific Hub using hub location
     *
     * @return bool
     * @author Mohannad Elemary
     */
    public function hasControlOnHubUsingLocation($hubLocationID)
    {
        $hubID = contact(HubLocationRepository::class, 'find', $hubLocationID)->hub_id;

        return $this->all_hubs || in_array($hubID, $this->hubs->pluck('id')->toArray());
    }

    /**
     * Check If User Is Managing a Specific Hub
     *
     * @return bool
     * @author Mohannad Elemary
     */
    public function hasControlOnHub($hubID)
    {
        return $this->all_hubs || in_array($hubID, $this->hubs->pluck('id')->toArray());
    }

    /**
     * Check If User Is Managing All Hubs
     *
     * @return bool
     * @author Mohannad Elemary
     */
    public function isManagingAllHubs()
    {
        return $this->is_super || $this->all_hubs;
    }

    /**
     * Check If User Has Permissions To Any Hub Location Permissions
     *
     * @return bool
     * @author Mohannad Elemary
     */
    public function isHubLocationManager()
    {
        return $this->hasPermissionTo('view_hubLocations')
            || $this->hasPermissionTo('add_hubLocations')
            || $this->hasPermissionTo('edit_hubLocations')
            || $this->hasPermissionTo('delete_hubLocations')
            || $this->hasPermissionTo('sort_hubLocations');
    }

    /**
     * A model may have multiple roles.
     */
    public function roles(): MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        )
            ->withTranslation()
            ->with('permissions');
    }

    public function rolesBasic()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @param  string  $domain
     * @return void
     * 	@author Mohannad Elemary
     */
    public function sendPasswordResetNotification($token, $domain = null)
    {
        $this->notify(new ResetPasswordNotification($token, $domain));
    }

    /**
     * Send the password changed notification.
     *
     * @param  string  $domain
     * @return void
     * 	@author Mohannad Elemary
     */
    public function sendPasswordChangedNotification($domain = null)
    {
        $this->notify(new PasswordChangedNotification($domain));
    }

    /**
     * Check if User Can Access The Hub As a Dropdown Filter
     * Allowed Users:
     * 1) Users Who Can Add Users
     * 2) Users Who Can Edit Users
     * 3) Users Who Can View Inventory By Hub Locations
     *
     * @return boolean
     * @author Mohannad Elemary
     */
    public function canAccessHubByDropdown()
    {
        if (($this->hasPermissionTo('add_users')
            || $this->hasPermissionTo('edit_users')
            || $this->hasPermissionTo('viewQuantities_inventory')
            || $this->hasPermissionTo('editHubs_salesChannels')) && request()->dropdown) {
            return true;
        }

        return false;
    }

    public function pickerBatches()
    {
        return $this->hasMany(Batch::class, 'picker_id');
    }

    public function dispatchingStation()
    {
        return $this->hasOne(DispatchingStation::class, 'dispatcher_id');
    }

    /**
     * Check If Picker Has Currently Active Tasks
     *
     * @return bool
     * @author Mohannad Elemary
     */
    public function currentlyHasActiveTasks()
    {
        return $this->pickerBatches()->where('status', BatchStatus::PICKING)->exists();
    }
}
