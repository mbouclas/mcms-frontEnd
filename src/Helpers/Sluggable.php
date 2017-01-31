<?php

namespace Mcms\FrontEnd\Helpers;


use Config;
use Mcms\Core\Helpers\Strings;
use Mcms\FrontEnd\Services\LayoutManager;
use Route;

trait Sluggable
{
    public function generateSlug($item = null)
    {
        $stringHelpers = new Strings;

        return $stringHelpers->vksprintf($this->getSlug(), ( ! $item) ? $this->toArray() : $item);
    }

    public function getSlug(){
/*        $slugPattern = $this->slugPattern;

        if (isset($this['settings']['Layout']) && isset($this['settings']['Layout']['id'])){
            //grab that layout
            $layout = LayoutManager::registry($this['settings']['Layout']['id']);
            if (isset($layout['settings']) && isset($layout['settings']['slug_pattern'])){
                $slugPattern = $layout['settings']['slug_pattern'];
            }
        }

        return $slugPattern;*/
        return str_replace(url('/'), '', $this->createUrl());
    }

    public function createUrl()
    {
        $this->setRoute();

        $routes = Route::getRoutes();
        $params = [];
        foreach ($routes->getByName($this->route)->parameterNames() as $parameterName) {
            $params[$parameterName] = $this->{$parameterName};
        }
        return route($this->route, $params);
    }

    public function setRoute($route = null)
    {
        if ($route){
            $this->route = $route;
            return;
        }

        $default = $this->defaultRoute;
        $layoutId = ( ! isset($this->settings['Layout']['id'])) ? $default : $this->settings['Layout']['id'];
        $layout = LayoutManager::registry($layoutId, true);
        $this->route = (isset($layout['settings']['route'])) ? $layout['settings']['route'] : $default;
    }

    public function slugPattern()
    {
        $this->setRoute();
        $routes = Route::getRoutes();
        return $routes->getByName($this->route)->uri();
    }
}