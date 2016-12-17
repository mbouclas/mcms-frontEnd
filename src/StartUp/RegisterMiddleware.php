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
        $router->middleware('auth', \Mcms\FrontEnd\Http\Middleware\Authenticate::class);
        $router->middleware('auth.basic', \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class);
        $router->middleware('guest', \Mcms\FrontEnd\Http\Middleware\RedirectIfAuthenticated::class);
        $router->middleware('localize', \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class);
    }
}