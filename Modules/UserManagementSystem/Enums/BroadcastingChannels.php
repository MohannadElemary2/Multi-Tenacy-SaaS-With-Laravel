<?php

namespace Modules\UserManagementSystem\Enums;

use BenSampo\Enum\Enum;

final class BroadcastingChannels extends Enum
{
    const USER_CHANGES = 'user_changes';
    const USER_DELETED = 'user_deleted';

}
