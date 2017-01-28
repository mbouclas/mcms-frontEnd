<?php

namespace Mcms\FrontEnd\StartUp;



use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Class RegisterMiddleware
 * @package Mcms\FrontEnd\StartUp
 */
class RegisterMiddleware
{

    /**
     * Register all your middleware here
     * @param ServiceProvider $serviceProvider
     * @param Router $router
     */
    public function handle(ServiceProvider $serviceProvider, Router $router)
    {
        $router->aliasMiddleware('auth', \Mcms\FrontEnd\Http\Middleware\Authenticate::class);
        $router->aliasMiddleware('auth.basic', \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class);
        $router->aliasMiddleware('guest', \Mcms\FrontEnd\Http\Middleware\RedirectIfAuthenticated::class);
        $router->aliasMiddleware('localize', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class);
    }
}