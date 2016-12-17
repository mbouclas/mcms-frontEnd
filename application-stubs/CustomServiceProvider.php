<?php

namespace FrontEnd;

use FrontEnd\StartUp\RegisterEvents;
use FrontEnd\StartUp\RegisterWidgets;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    protected $policies = [];
    protected $listeners = [];
    protected $commands = [];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(DispatcherContract $events, GateContract $gate, Router $router)
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }
        (new RegisterWidgets())->handle();
        (new RegisterEvents())->handle($this,$events);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}