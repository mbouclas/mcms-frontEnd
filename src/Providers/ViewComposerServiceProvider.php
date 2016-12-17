<?php

namespace Mcms\FrontEnd\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer(
            'profile', 'Mcms\FrontEnd\Http\ViewComposers\MenuComposer'
        );

        // Using Closure based composers...
        view()->composer('dashboard', function ($view) {

        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}