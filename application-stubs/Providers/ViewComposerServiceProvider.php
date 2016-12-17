<?php

namespace FrontEnd\Providers;

use Mcms\FrontEnd\Providers\ViewComposerServiceProvider as BaseViewComposerServiceProvider;

class ViewComposerServiceProvider extends BaseViewComposerServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer('partials.header', 'FrontEnd\Http\ViewComposers\MenuComposer');
        view()->composer('partials.languagesSelector', 'FrontEnd\Http\ViewComposers\LanguagesMenu');

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