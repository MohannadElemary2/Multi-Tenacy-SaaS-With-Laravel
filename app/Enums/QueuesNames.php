<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class QueuesNames extends Enum
{
    const EMAILS = 'emails';
    const INTEGRATIONS = 'integrations';
    const ORDERS = 'orders';
    const SOCKET = 'socket';
    const NOTIFICATIONS = 'notifications';
}
