<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Hyn\Tenancy\Contracts\CurrentHostname;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use Nwidart\Modules\Facades\Module;
use Illuminate\Routing\RouteCollection;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerModulesRoutes();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function registerModulesRoutes()
    {
        // Handling multiple tenant routes for modules
        $hostname = $this->app->make(CurrentHostname::class);
        $router = $this->app->make(Router::class);
        $url = $this->app->make('url');
        $config = $this->app->make(Repository::class);
        $modules = Module::all();

        if ($hostname) {
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
    }
}
