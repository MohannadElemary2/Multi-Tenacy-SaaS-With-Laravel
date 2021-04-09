<?php

namespace App\Providers;

use App\Models\Tenants\PassportAuthCode;
use App\Models\Tenants\PassportClient;
use App\Models\Tenants\PassportPersonalAccessClient;
use App\Models\Tenants\PassportRefreshToken;
use App\Models\Tenants\PassportToken;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Modules\Client\Repositories\ClientRepository;
use Nwidart\Modules\Facades\Module;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // If Socket is opened for a tenant, Switch Connection and Environment
        if (request()->header('X-DOMAIN')) {
            $client = app(ClientRepository::class)->findBy('domain', request()->header('X-DOMAIN'))->first(['*'], false);

            if ($client) {
                $website = app(WebsiteRepository::class)->findById($client->website_id);
                app(Environment::class)->tenant($website);

                config(['database.default' => 'tenant']);
                Passport::useClientModel(PassportClient::class);
                Passport::useTokenModel(PassportToken::class);
                Passport::useAuthCodeModel(PassportAuthCode::class);
                Passport::usePersonalAccessClientModel(PassportPersonalAccessClient::class);
                Passport::useRefreshTokenModel(PassportRefreshToken::class);

                Config::set('auth.guards.api.provider', 'clients');

                Broadcast::routes(['prefix' => 'api', 'middleware' => 'auth:client-users-api']);
            }
        } else {
            Broadcast::routes();
        }


        $modules = Module::all();

        foreach ($modules as $module) {
            $path = $module->getPath() . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'channels.php';

            require $path;
        }

        require base_path('routes/channels.php');
    }
}
