<?php

namespace Mcms\FrontEnd\FormBuilder\Contracts;

/**
 * Interface FormBuilder
 * @package Mcms\FrontEnd\FormBuilder\Contracts
 */
interface FormBuilder
{
    /**
     * Create a new schema
     *
     * @param array $options
     * @return mixed
     */
    public function create(array $options = []);

    /**
     * Add a fields to the schema
     *
     * @param array $field
     * @return mixed
     */
    public function addField(array $field);

    /**
     * Remove a field from the schema
     *
     * @param array $field
     * @return mixed
     */
    public function removeField(array $field);

    /**
     * Add a property to a field
     *
     * @param array $param
     * @param string $key
     * @return mixed
     */
    public function addToField(array $param, $key = 'params');

    /**
     * remove a property from a field
     *
     * @param null $key
     * @param string $location
     * @return mixed
     */
    public function removeFromField($key = null, $location = 'params');

    /**
     * Find a property in the schema fields
     *
     * @param $field
     * @param $key
     * @param string $location
     * @return mixed
     */
    public function find($field, $key, $location = 'params');

    /**
     * Returns the schema
     *
     * @return mixed
     */
    public function get();

    /**
     * Clone the schema into a new one
     *
     * @param $filename
     * @return mixed
     */
    public function cloneSchema($filename);

    /**
     * Save the schema to a file
     *
     * @param null $filename
     * @return mixed
     */
    public function save($filename = null);

    /**
     * Delete the schema from disk
     *
     * @param null $filename
     * @return mixed
     */
    public function delete($filename = null);

    /**
     * Load a schema from disk.
     *
     * @return mixed
     */
    public function load();
}