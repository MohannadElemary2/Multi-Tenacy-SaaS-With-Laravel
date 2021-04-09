<?php

namespace Modules\Client\Providers;

use Modules\Client\Providers\AuthServiceProvider;
use Hyn\Tenancy\Commands\RunCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;

class ClientServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Client';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'client';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register Commands to be used in the module
        $this->commands([
            RunCommand::class,
            InstallCommand::class,
            KeysCommand::class,
            ClientCommand::class
        ]);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path($this->moduleName, 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
