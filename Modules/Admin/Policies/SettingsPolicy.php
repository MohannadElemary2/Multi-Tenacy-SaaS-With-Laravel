<?php

namespace Modules\Admin\Policies;

use App\Enums\GeneralConstants;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Entities\System\User;

class SettingsPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user) {
            return true;
        }
    }

    public function viewAny(?User $user = null)
    {
        return request()->header(GeneralConstants::X_API_KEY_HEADER_KEY) == config('app.x_api_key');
    }
}
