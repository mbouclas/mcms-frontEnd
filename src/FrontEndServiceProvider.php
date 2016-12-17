<?php

namespace Mcms\FrontEnd;

use Mcms\FrontEnd\StartUp\RegisterAdminPackage;
use Mcms\FrontEnd\StartUp\RegisterAuth;
use Mcms\FrontEnd\StartUp\RegisterDirectives;
use Mcms\FrontEnd\StartUp\RegisterEvents;
use Mcms\FrontEnd\StartUp\RegisterFacades;
use Mcms\FrontEnd\StartUp\RegisterMiddleware;
use Mcms\FrontEnd\StartUp\RegisterServiceProviders;
use Mcms\FrontEnd\StartUp\RegisterWidgets;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Installer;

/**
 * Class FrontEndServiceProvider
 * @package Mcms\FrontEnd
 */
class FrontEndServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Mcms\FrontEnd\Http\Controllers';

    /**
     * @var array
     */
    protected $policies = [
        'App\Team' => 'Mcms\FrontEnd\Policies\TeamMemberPolicy',
    ];

    public $packageName = 'front-end';

    /**
     * @var array
     */
    protected $commands = [
        \Mcms\FrontEnd\Console\Commands\Install::class,
        \Mcms\FrontEnd\Console\Commands\OptimizeImages::class,
        \Mcms\FrontEnd\Console\Commands\RefreshAssets::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(DispatcherContract $events, GateContract $gate, Router $router)
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('frontEnd.php'),
            __DIR__.'/../config/seo.php' => config_path('seo.php'),
            __DIR__.'/../config/editableRegions.php' => config_path('editableRegions.php'),
            __DIR__.'/../config/layouts.php' => config_path('layouts.php'),
            __DIR__.'/../config/formBuilder.php' => config_path('formBuilder.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../database/seeds/' => database_path('seeds')
        ], 'seeds');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views'),
        ],'views');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang'),
        ],'lang');

        $this->publishes([
            __DIR__.'/../resources/public' => public_path('front-end'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../application-stubs' => base_path('FrontEnd'),
        ], 'scaffold');

        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('front-end'),
        ], 'assets');

        $this->publishes([
            __DIR__ . '/../config/admin.package.json' => storage_path('app/front-end/admin.package.json'),
        ], 'admin-package');


        if (! $this->app->routesAreCached()) {
            $router->group([
                 'middleware' => 'web',
            ], function ($router) {
                require __DIR__.'/Http/routes.php';
            });

            $this->loadViewsFrom(__DIR__.'/../resources/views', 'frontEnd');
        }


        /**
         * Register Auth and policies exceptions
         */
        (new RegisterAuth())->handle($gate);
        
        /**
         * Register Events
         */
//        parent::boot($events);
        (new RegisterEvents())->handle($this,$events);

        /**
         * Register custom Blade directives
         */
        (new RegisterDirectives())->handle();
        /*
         * Register dependencies
        */
        (new RegisterServiceProviders())->handle();

        /*
         * Register middleware
         */
        (new RegisterMiddleware())->handle($this,$router);

        /**
         * Register any widgets
         */
        (new RegisterWidgets())->handle();

        /**
         * Register admin package
         */
        (new RegisterAdminPackage())->handle($this);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*
        * Register Commands
        */
        $this->commands($this->commands);

        /**
         * Register Facades
         */
        (new RegisterFacades())->handle($this);

        /**
         * Register installer
         */
        Installer::register(\Mcms\FrontEnd\Installer\Install::class);
    }
}
