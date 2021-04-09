<?php

namespace App\Providers;

use App\Models\Tenants\PassportAuthCode;
use App\Models\Tenants\PassportClient;
use App\Models\Tenants\PassportPersonalAccessClient;
use App\Models\Tenants\PassportRefreshToken;
use App\Models\Tenants\PassportToken;
use Hyn\Tenancy\Environment;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Change Passport Models to Tenant Models if the current user is a tenant
        if (app(Environment::class)->tenant()) {
            Passport::useClientModel(PassportClient::class);
            Passport::useTokenModel(PassportToken::class);
            Passport::useAuthCodeModel(PassportAuthCode::class);
            Passport::usePersonalAccessClientModel(PassportPersonalAccessClient::class);
            Passport::useRefreshTokenModel(PassportRefreshToken::class);

            Config::set('auth.guards.api.provider', 'clients');
        }
        Passport::routes();
    }
}
