<?php

namespace Modules\UserManagementSystem\Auth\Passwords;

use App\Traits\UsesTenantConnection;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Auth\Passwords\PasswordBrokerManager as PasswordsPasswordBrokerManager;
use Illuminate\Contracts\Auth\PasswordBrokerFactory as FactoryContract;
use Illuminate\Support\Str;

class PasswordBrokerManager extends PasswordsPasswordBrokerManager implements FactoryContract
{

    use UsesTenantConnection;


    /**
     * Create a token repository instance based on the given configuration.
     *
     * @param  array  $config
     * @return \Illuminate\Auth\Passwords\TokenRepositoryInterface
     */
    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $connection = $this->getConnectionName();

        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire'],
            $config['throttle'] ?? 0
        );
    }
}
