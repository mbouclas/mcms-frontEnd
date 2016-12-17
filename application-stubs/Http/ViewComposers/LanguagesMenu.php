<?php

namespace FrontEnd\Http\ViewComposers;

use Illuminate\Contracts\View\View;


class LanguagesMenu
{
    protected $languages;

    public function __construct()
    {
        $this->languages = \LaravelLocalization::getSupportedLocales();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('SupportedLocales', $this->languages);

    }
}