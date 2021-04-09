<?php

namespace Modules\UserManagementSystem\Providers;

use Illuminate\Auth\Passwords\PasswordResetServiceProvider as PasswordsPasswordResetServiceProvider;
use Modules\UserManagementSystem\Auth\Passwords\PasswordBrokerManager;

class PasswordResetServiceProvider extends  PasswordsPasswordResetServiceProvider
{
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }
}
