<?php

namespace Modules\Client\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsPolicy
{
    use HandlesAuthorization;

    const EDIT_TIME_ZONE = 'editTimeZone_settings';
    const EDIT_LOCALE = 'editLocale_settings';

    public function before($user)
    {
        if ($user->is_super) {
            return true;
        }
    }

    public function viewAny($user)
    {
        return true;
    }

    public function editTimeZone($user)
    {
        return  $user->hasPermissionTo(self::EDIT_TIME_ZONE);
    }

    public function editLocale($user)
    {
        return  $user->hasPermissionTo(self::EDIT_LOCALE);
    }
}
