<?php

namespace Mcms\FrontEnd\Services;


use Config;
use Illuminate\Support\Collection;

/**
 * Class LayoutManager
 * @package Mcms\FrontEnd\Services
 */
class LayoutManager
{
    /**
     * @var
     */
    private static $instance;
    /**
     * @var Collection
     */
    protected $registry;
    /**
     * @var
     */
    protected $resultSet;

    protected $filtered = false;

    /**
     * LayoutManager constructor.
     */
    public function __construct()
    {
        $this->registry = new Collection([]);
        $this->init();
    }

    /**
     * @return $this
     */
    protected function init(){
        $layouts = Config::get('layouts');
        foreach ($layouts as $layout){
            $this->add($layout);
        }

        return $this;
    }

    /**
     * @param null $filters
     * @param bool $process
     * @return Collection|mixed
     */
    public static function registry($filters = null, $process = false)
    {
        return self::instance()
            ->filter($filters)
            ->get($process);
    }

    /**
     * @example: $filters can be a string, or an array of key-values. If the value is an array it self we will search
     * with whereIn
     *
     * @param null $filters
     * @return $this
     */
    public function filter($filters = null)
    {
        $result = $this->resultSet =  $this->registry;

        if ($filters){
            $this->filtered = true;
            if ( ! is_array($filters)){
                $result = $result->where('varName', $filters);
                $this->resultSet =  $result->first();

                return $this;
            }

            foreach ($filters as $key=>$filter) {
                $mode = (is_array($filter)) ? 'whereIn' : 'where';
                $this->resultSet = $result->{$mode}($key, $filter);
            }
        }

        return $this;
    }

    /**
     * @param array $layout
     * @return mixed
     */
    public static function register(array $layout)
    {
        self::instance()->add($layout);

        return self::$instance;
    }

    /**
     * @param array $layout
     * @return $this
     */
    protected function add(array $layout){
        $this->registry->push(new Collection($layout));

        return $this;
    }

    /**
     * $process can be true/false or configs
     *
     * @param bool|string $process
     * @return Collection|mixed
     */
    public function get($process = false)
    {
        $result =  (is_null($this->resultSet) && !$this->filtered) ? $this->registry : $this->resultSet;

        if (! $result){
            return new Collection();
        }

        if ( ! $process){
            return $result;
        }

        if ($process === 'configs'){
            //lets check if any of the configs are a class
            foreach ($result as $index => $item) {
                $result[$index] = $this->processConfigs($item);
            }

            return $result;
        }

        //check if this is a single result returned with ->first()
        if (isset($result['varName'])){
            return $this->process($result);
        }

        foreach ($result as $index => $item) {
            $result[$index] = $this->process($item);
        }

        return $result;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function process($item)
    {

        //it is not a class
        if ( ! isset($item['beforeRender']) || is_null($item['beforeRender']) || $item['beforeRender'] == ''){
            return $item;
        }

        if ( ! class_exists($item['beforeRender'])){
            return $item;
        }

        $item['handler'] = (new $item['beforeRender']($item));

        return $item;
    }

    /**
     * @return LayoutManager
     */
    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function processConfigs($layout)
    {
        if ( ! isset($layout['config'])) {
            return $layout;
        }

        $configs = $layout['config'];
        foreach ($configs as $index => $item) {
            if (!is_array($item)) {
                //try to execute the class
                $configs[$index] = (new $item)->handle($layout);
            }
        }
        $layout['config'] = $configs;

        return $layout;
    }
}