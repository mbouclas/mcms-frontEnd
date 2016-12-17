<?php

namespace Mcms\FrontEnd\Http\ViewComposers;
use Mcms\Core\Services\Menu\MenuService;
use Illuminate\Contracts\View\View;


class MenuComposer
{
    protected $menu;

    public function __construct(MenuService $menuService)
    {
        // Dependencies automatically resolved by service container...
        $this->menu = $menuService;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $menus = $this->menu->all(['items']);
        $this->composerHeaderMenu($view, $menus);
        $this->composerFooterMenu($view, $menus);
    }

    /**
     * @param View $view
     * @param $menus
     */
    private function composerHeaderMenu(View $view, $menus)
    {
        $view->with('HeaderMenu', $menus->where('slug', 'header-menu')->first());
    }

    /**
     * @param View $view
     * @param $menus
     */
    private function composerFooterMenu(View $view, $menus)
    {
        $view->with('FooterMenu', $menus->where('slug', 'footer-menu')->first());
    }
}