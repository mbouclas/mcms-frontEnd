<?php

namespace Mcms\FrontEnd\FormBuilder;


use Config;
use File;
use Mcms\Core\Helpers\FileSystem;
use Mcms\FrontEnd\FormBuilder\Contracts\FormBuilder;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;

class Schema implements FormBuilder
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Collection
     */
    protected $schema;
    /**
     * @var array
     */
    public $config;
    /**
     * @var FormBuilderConfigurator
     */
    public $configurator;

    public function __construct($name, FormBuilderConfigurator $configurator)
    {
        $this->name = $name;
        $this->configurator = $configurator;
        $this->config = $this->configurator->get();
        //load the schema
        $this->load();
    }

    /**
     * Create a new schema
     *
     * @param array $options
     * @return mixed
     */
    public function create(array $options = [])
    {
        // TODO: Implement create() method.
    }

    /**
     * Add a fields to the schema
     *
     * @param array $field
     * @return mixed
     */
    public function addField(array $field)
    {
        // TODO: Implement addField() method.
    }

    /**
     * Remove a field from the schema
     *
     * @param array $field
     * @return mixed
     */
    public function removeField(array $field)
    {
        // TODO: Implement removeField() method.
    }

    /**
     * Add a property to a field
     *
     * @param array $param
     * @param string $key
     * @return mixed
     */
    public function addToField(array $param, $key = 'params')
    {
        // TODO: Implement addToField() method.
    }

    /**
     * remove a property from a field
     *
     * @param null $key
     * @param string $location
     * @return mixed
     */
    public function removeFromField($key = null, $location = 'params')
    {
        // TODO: Implement removeFromField() method.
    }

    /**
     * Find a property in the schema fields
     *
     * @param $field
     * @param $key
     * @param string $location
     * @return mixed
     */
    public function find($field, $key, $location = 'params')
    {
        // TODO: Implement find() method.
    }

    /**
     * Returns the schema
     *
     * @return Collection
     */
    public function get()
    {
        return $this->schema;
    }

    public function toArray()
    {
        $arr = [];
        foreach ($this->schema as $item) {
            $tmp = $item;
            $tmp['params'] = [];
            foreach ($item['params'] as $param){
                $tmp['params'][$param['varName']] = $param;
            }
            $arr[] = $tmp;
        }

        return $arr;
    }

    /**
     * Clone the schema into a new one
     *
     * @param $filename
     * @return mixed
     */
    public function cloneSchema($filename)
    {
        // TODO: Implement cloneSchema() method.
    }

    /**
     * Save the schema to a file
     *
     * @param string|null $filename
     * @param array|null $schema
     * @return $this
     */
    public function save($filename = null, $schema = null)
    {
        if ( ! $schema){
            $schema = $this->schema->toArray();
        }

        if ( ! $filename){
            $filename = $this->schemaFileName();
        }
        $FsHelper = new FileSystem(new \Illuminate\Filesystem\Filesystem());
        $out = '<?php
            return ';
        $out .= $FsHelper->var_export54($schema);
        $out .= ';';
        File::put($filename, $out);

        return $this;
    }

    /**
     * Delete the schema from disk
     *
     * @param null $filename
     * @return mixed
     */
    public function delete($filename = null)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Load a schema from disk.
     *
     * @return mixed
     */
    public function load()
    {

        if (File::exists($this->schemaFileName())){
            $schema = require($this->schemaFileName());
        }
        else {
            //file not found, load the default schema
            $schema = Config::get('admin.components');
            //save it to file
            $this->save(null, $schema);
        }

        $this->schema = $this->convertSchemaArrayToCollection($schema);

        return $this;
    }

    private function schemaFileName()
    {
        return $this->configurator->getPath($this->name);
    }

    private function convertSchemaArrayToCollection($schema)
    {
        $collection = new Collection();

        foreach ($schema as $varName => $item) {
            //now lets loop through and create sub-collections
            foreach ($item as $key => $value) {
                if (is_array($value)){
                    $item[$key] = $this->convertSchemaArrayToCollection($value);
                }
            }
            $item['varName'] = $varName;
            $collection->push($item);
        }

        return $collection;
    }
}