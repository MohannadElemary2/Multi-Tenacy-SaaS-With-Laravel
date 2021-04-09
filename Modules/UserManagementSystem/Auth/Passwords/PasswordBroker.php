<?php

namespace Modules\UserManagementSystem\Auth\Passwords;

use Illuminate\Auth\Passwords\PasswordBroker as PasswordsPasswordBroker;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;

class PasswordBroker extends PasswordsPasswordBroker implements PasswordBrokerContract
{
}
