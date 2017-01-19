<?php

namespace Mcms\FrontEnd\Services;

use Config;
use Mcms\Core\Helpers\ConfigFiles;
use Mcms\FrontEnd\Models\EditableRegion;
use Illuminate\Support\Collection;

/**
 * Class EditableRegions
 * @package Mcms\FrontEnd\Services
 */
class EditableRegions
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

    protected $currentRegion = null;

    protected $model;
    protected $filterMode;

    /**
     * EditableRegions constructor.
     */
    public function __construct(EditableRegion $model)
    {
        $this->registry = new Collection([]);
        $this->model = $model;
        $this->init();
    }

    /**
     * @return $this
     */
    protected function init(){
        $regions = Config::get('editableRegions');
        //get the items from the db
        $items = $this->model->get();
        $items = $items->groupBy('layout');

        foreach ($regions as $name => $region){
            foreach ($region as $key=>$sub) {
                $region[$key]['items'] = [];
                $tmp = (isset($items[$name])) ? $items[$name]->where('region',$key) : null;
                if ($tmp){
                    foreach ($tmp as $item) {
                        $r = $item->toArray();
                        $region[$key]['items'] = $r['items'];
                        $region[$key]['settings'] = $r['settings'];
                    }
                }
            }

            $this->add($name, $region);
        }

        return $this;
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
            if ( ! is_array($filters)){
                $result = $result->where('name', $filters);
                $this->resultSet =  $result->first();
                $this->filterMode = 'single';
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
     * @param $name
     * @return $this|null
     */
    public function region($name)
    {
        if (!isset($this->resultSet['regions'][$name])){
            return null;
        }

        $this->resultSet = $this->resultSet['regions'][$name];
        $this->currentRegion = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function get($justItems = false, $processConfigs = false)
    {
        $ret = (is_null($this->resultSet)) ? $this->registry : $this->resultSet;

        if ($processConfigs){
            foreach ($ret as $region => $item) {
                foreach ($item['regions'] as $key => $area) {
                    if (isset($area['structuredData'])){
                        $item['regions'][$key] = $this->processConfigs($area, 'structuredData');
                    }
                    if (isset($area['options'])){
                        $item['regions'][$key] = $this->processConfigs($area, 'options');
                    }
                }
            }
        }

        if ( ! $justItems){
            return $ret;
        }

        if ($this->filterMode == 'single'){
            return $this->simplifyItems($ret);
        }

        //assume an array of stuff
        foreach ($ret as $index => $item) {
            $ret[$item['name']] = $this->simplifyItems($item);
        }

        return $ret;
    }

    private function processConfigs($area, $key)
    {
        if (! isset($area[$key]) || empty($area[$key])) {
            return $area;
        }

        $configs = $area[$key];
        foreach ($configs as $index => $item) {
            if (!is_array($item)) {
                //try to execute the class
                $configs[$index] = (new $item)->handle($area);
            }
        }
        $area[$key] = $configs;

        return $area;
    }

    private function simplifyItems($ret){
        $simplified = [];

        foreach ($ret['regions'] as $item) {
            $simplified[$item['slug']] = ($item['type'] == 'generic') ? $this->simplify($item['items']) : $item['items'];
        }

        return $simplified;
    }

    public function processRegions($regions = [])
    {
        foreach ($this->resultSet['regions'] as $region => $items) {
            if (count($regions) == 0 || in_array($region, $regions)) {
                $this->resultSet['regions'][$region] = $this->process($items, true, true);
            }
        }
        return $this;
    }

    /**
     * If the type is class, execute
     *
     * @return $this
     */
    public function process($resultSet = null, $returnResult = false, $withItems = false)
    {
        if ( ! $resultSet){
            $resultSet = &$this->resultSet;//assign by reference to directly edit the value later on
        }

        if (is_object($resultSet) && is_a($resultSet, get_class($resultSet))){
            foreach ($resultSet as $index => $item){
                foreach ($item['regions'] as $key => $subItem) {
                    $resultSet[$index]['regions'][$key] = $this->process($subItem, true);
                }
            }

            $this->resultSet = $resultSet;
            return $this;
        }

        if (isset($resultSet['type']) && $resultSet['type'] == 'class'){
            $class = \App::make($resultSet['class']);
            $resultSet['items'] = $class->handle($resultSet);//this is essentially assigning by reference the $this->resultSet
        }

        //now check if we have any items to process (class based items)
        //This is explicit cause it is really - reallly expensive
        if ($withItems){

            $toProcess = new Collection();
            foreach ($resultSet['items'] as $index => $item) {
                if ($item['type'] == 'item'){
                    //group them into arrays to avoid lots of expensive queries
                    $item['item']['index'] = $index;
                    $toProcess->push($item['item']);
                }
            }


            if ($toProcess->count() > 0){
                //execute the queries
                foreach ($toProcess->groupBy('model') as $model => $items){
                    $results = (new $model)->whereIn('id', $items->pluck('item_id'))->get();
                    //now replace the originals with the result
                    foreach ($items as $item) {
                        $found = $results->where('id', $item['item_id']);
                        if ($found){
                            $resultSet['items'][$item['index']]['item'] = $found->first();
                        }
                    }
                }

            }
        }

        return ($returnResult) ? $resultSet : $this;
    }

    /**
     * @param $name
     * @param array $config
     * @return $this
     */
    protected function add($name, array $config = []){
        $this->registry->push(new Collection([
            'name' => $name,
            'regions' => new Collection($config)
        ]));

        return $this;
    }

    /**
     * @param $name
     * @param $config
     * @return mixed
     */
    public static function register($name, $config)
    {
        self::instance()->add($name, Config::get($config));

        return self::$instance;
    }

    /**
     * filters can either be a string, like the name of the collection, or an array
     * @example $filters as array $filters = ['name'=>'core','type'=>'generic']
     *
     * @param null $filters
     * @return Collection
     */
    public static function registry($filters = null)
    {
        return self::instance()->filter($filters)->get();
    }

    /**
     * @return EditableRegions
     */
    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function update($id, array $region)
    {
        //save to db
        //first drop everything
        $toCreate = [];
        $toDelete =[];
        $toSave = $region;
        $toSave['regions'] = [];

        foreach ($region['regions'] as $key => $sub) {
            $toDelete[] = $key;
            $toCreate[] = [
                'layout' => $id,
                'region' => $key,
                'items' => $sub['items'] ?: [],
                'settings' => isset($sub['settings']) ? $sub['settings'] : [],
            ];
        }

        //first delete
        $this->model->where('layout',$id)->whereIn('region',$toDelete)->delete();

        foreach ($toCreate as $item) {

            $this->model->create($item);
        }

        foreach ($region['regions'] as $key => $sub) {
            $region['regions'][$key]['items'] = [];
            $region['regions'][$key]['settings'] = [];
        }

        $config = new ConfigFiles('editableRegions');
        $config->contents[$id] = $region['regions'];
        $config->save();
    }

    public function toJson($mode = 0)
    {
        return json_encode($this->resultSet, $mode);
    }

    public function simplify($region)
    {
        $regionItems = new Collection();
        foreach ($region as $item) {
            $regionItems->push($item['item']);
        }

        return $regionItems;
    }
}