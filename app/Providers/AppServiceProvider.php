<?php

namespace App\Providers;

use App\Services\GoogleMaps\GoogleMapsAPIClient;
use App\Services\GoogleMaps\Services\GeocodingService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Hyn\Tenancy\Environment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GeocodingService::class, function ($app) {
            return new GeocodingService(new GoogleMapsAPIClient());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(255);

        URL::forceScheme(ENV('HTTP_PROTOCOL', 'https'));

        $env = app(Environment::class);

        if ($fqdn = optional($env->hostname())->fqdn) {
            config(['database.default' => $env->tenant()->managed_by_database_connection]);
        }
    }
}
