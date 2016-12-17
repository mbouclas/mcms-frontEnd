<?php


namespace Mcms\FrontEnd\FormBuilder\Contracts;
use Illuminate\Http\Request;
use Mcms\FrontEnd\Models\FormBuilder;

/**
 * Interface FormBuilderProvider
 * @package Mcms\FrontEnd\FormBuilder\Contracts
 */
interface FormBuilderProvider
{
    /**
     * @return mixed
     */
    public function register();

    /**
     * @return mixed
     */
    public function controller();

    /**
     * @return mixed
     */
    public function url();

    /**
     * @return mixed
     */
    public function isValid();

    /**
     * @return boolean
     */
    public function validate(array $fieldValues);

    /**
     * @param Request $request
     * @param FormBuilder $form
     * @return mixed
     */
    public function process(Request $request, FormBuilder $form);

    /**
     * @return mixed
     */
    public function result();
}