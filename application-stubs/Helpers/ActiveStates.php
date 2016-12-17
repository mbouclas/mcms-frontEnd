<?php

namespace FrontEnd\Helpers;


use Request;

/**
 * Used to check the active state of a link in blade
 * @example class="{{ \FrontEnd\Helpers\ActiveStates::set_active($item->permalink ?: $item->link) }}"
 * if you want to check that the category on the menu is the same as an active product/page
 * then in the controller set the path
 * @example ActiveStates::setPage(parse_url(route('articles',['slug' => $article->categories->first()->slug]))['path']);
 *
 * Class ActiveStates
 * @package FrontEnd\Helpers
 */
class ActiveStates
{
    /**
     * @var
     */
    private static $instance;
    /**
     * @var
     */
    protected static $page;

    /**
     * @param $path
     * @param string $active
     * @return string
     */
    public static function set_active($path, $active = 'active')
    {
        if ( ! is_null(self::$page)){
            return (self::$page == $path) ? $active : '';
        }

        $path = ltrim($path, '/');
        return Request::is($path) ? $active : '';
    }

    /**
     * @param $page
     */
    public static function setPage($page)
    {
        self::$page = $page;
    }

    /**
     * @return ActiveStates
     */
    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}