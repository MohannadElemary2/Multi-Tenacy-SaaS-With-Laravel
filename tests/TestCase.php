<?php

namespace Tests;

use App\Models\Tenants\PassportAuthCode;
use App\Models\Tenants\PassportClient;
use App\Models\Tenants\PassportPersonalAccessClient;
use App\Models\Tenants\PassportRefreshToken;
use App\Models\Tenants\PassportToken;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Illuminate\Routing\RouteCollection;
use Laravel\Passport\Passport;
use Modules\Admin\Entities\System\User;
use Modules\UserManagementSystem\Entities\Client\TenantUser;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Modules\Client\Entities\Client\HubHistory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    const CLIENT_USER_AUTH_GUARD = 'client-users-api';

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /**
     * Setting up tenant database
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function setUpTenantDB()
    {
        DB::setDefaultConnection(Config::get('database.testing_tenant'));

        $this->registerTenantRoutes();
        $this->migrateTenant();
        $this->seedStatistics();
    }

    public function preparingTenantEnvironment()
    {
        DB::setDefaultConnection(Config::get('database.testing_tenant'));
        $this->migrateTenant();
        $this->seedStatistics();
    }

    /**
     * Switch connection from tenant to system database
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function switchToSystemConnection()
    {
        DB::setDefaultConnection(Config::get('database.testing'));
    }

    /**
     * Registering tenants routes from all modules
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function registerTenantRoutes()
    {
        $router = $this->app->make(Router::class);
        $url = $this->app->make('url');
        $config = $this->app->make(Repository::class);
        $modules = Module::all();

        if ($config->get('tenancy.routes.replace-global')) {
            $router->setRoutes(new RouteCollection());
        }

        foreach ($modules as $module) {
            $path = $module->getPath() . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'tenants.php';
            $this->app->booted(function () use ($config, $router, $path, $url) {
                $router->middleware([])->group($path);

                $router->getRoutes()->refreshNameLookups();

                $url->setRoutes($router->getRoutes());
            });
        }
    }

    /**
     * Migrating tenants migrations
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function migrateTenant()
    {
        $modules = Module::all();

        foreach ($modules as $module) {
            $base = strlen(substr($module->getPath(), 0, strpos($module->getPath(), "Modules")));
            $base = substr($module->getPath(), $base);
            $path = $base . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR . 'tenant';

            Artisan::call('migrate', [
                '--database' => Config::get('database.testing_tenant'),
                '--path'     => $path
            ]);
        }
    }

    /**
     * Configuring Tenant Passport
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function configuringTenantPassport()
    {
        Passport::useClientModel(PassportClient::class);
        Passport::useTokenModel(PassportToken::class);
        Passport::useAuthCodeModel(PassportAuthCode::class);
        Passport::usePersonalAccessClientModel(PassportPersonalAccessClient::class);
        Passport::useRefreshTokenModel(PassportRefreshToken::class);

        Config::set('auth.guards.api.provider', 'clients');

        $this->artisan('passport:client', ['--password' => null, '--no-interaction' => true, '--provider' => 'clients']);
        $this->artisan('passport:keys', ['--no-interaction' => true]);
    }

    /**
     * Create tenant user
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function createTenantUser($overrides = [])
    {
        return factory(TenantUser::class)->create($overrides);
    }

    /**
     * Login as tenant user
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function loginAsTenantUser($user = null)
    {
        if (!($user instanceof UserContract)) {
            $user = $this->createTenantUser();
        }
        $this->actingAs($user, self::CLIENT_USER_AUTH_GUARD);
    }

    /**
     * Create system user
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function createSystemUser($overrides = [])
    {
        return factory(User::class)->create($overrides);
    }

    /**
     * Login as super admin system user
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function loginAsSystemUser($admin = null)
    {
        if (!($admin instanceof UserContract)) {
            $admin = factory(User::class)->create();
        }
        $this->actingAs($admin);
    }

    public function seedStatistics()
    {
        HubHistory::create([
            'day' => now()->format('Y-m-d')
        ]);
    }
}
