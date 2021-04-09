<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PassportGrantTypes extends Enum
{
    const NON_PASSWORD_GRANT = 0;
    const PASSWORD_GRANT = 1;
}