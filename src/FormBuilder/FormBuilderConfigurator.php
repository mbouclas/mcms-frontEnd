<?php

namespace Mcms\FrontEnd\FormBuilder;
use Illuminate\Routing\Router;


/**
 * Class FormBuilderConfigurator
 * @package Mcms\FrontEnd\FormBuilder
 */
abstract class FormBuilderConfigurator
{
    public $router;

    /**
     * @var mixed
     */
    protected $config;

    /**
     * FormBuilderConfigurator constructor.
     */
    public function __construct()
    {
        $this->config = \Config::get('formBuilder');
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->config;
    }

    /**
     * @param $schemaName
     * @return string
     */
    public function getPath($schemaName)
    {
        return config_path("schema". ucfirst($schemaName) . ".php");
    }

    /**
     *  Create a route where the forms posts at
     */
    public function createRoute(Router $router)
    {
        //check if we have a route in our config
        $route = ( ! isset($this->config['route'])) ? $this->defaultRoute() : $this->config['route'];
        $middleware = (isset($route['middleware'])) ? $route['middleware'] : null;
        $router->post($route['name'], $route['config'])->middleware($middleware);

        return $this;
    }

    private function defaultRoute()
    {
        return [
            'name' => 'postForm',
            'config' => [
                'as' => 'formBuilder-post',
                'uses'=> 'Mcms\FrontEnd\Http\Controllers\Admin\FormBuilderController@process'
            ],
            'middleware' => ['web'],
        ];
    }
}