<?php

namespace Modules\UserManagementSystem\Enums;

use BenSampo\Enum\Enum;

final class PredefinedRoles extends Enum
{
    const PICKER = [
        'role' => [
            'guard_name' => 'client-users-api',
            'en' => [
                'name' => 'Picker'
            ],
        ],
        'permissions' => [
            [
                'name' => 'picker_picking'
            ]
        ]
    ];

    const DISPATCHER = [
        'role' => [
            'guard_name' => 'client-users-api',
            'en' => [
                'name' =>  'Dispatcher'
            ],
        ],
        'permissions' => [
            [
                'name' => 'dispatcher_dispatching'
            ]
        ]

    ];
}
