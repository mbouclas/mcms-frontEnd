<?php

namespace Mcms\FrontEnd\FormBuilder;

use Event;
use Mcms\FrontEnd\Exceptions\InvalidProviderConfiguration;
use Mcms\FrontEnd\Models\FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class FormBuilderService
 * @package Mcms\FrontEnd\FormBuilder
 */
class FormBuilderService
{
    /**
     * @var FormBuilder
     */
    public $model;
    /**
     * @var \Mcms\FrontEnd\FormBuilder\FormBuilderConfigurator
     */
    public $configurator;

    /**
     * @var Collection
     */
    public $config;

    /**
     * FormBuilderService constructor.
     */
    public function __construct()
    {
        $configurator = \Config::get('formBuilder.configurator');
        $this->configurator = new $configurator();
        $this->config = $this->configurator->get();
        $this->model = new FormBuilder();
    }

    public function bySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * @param $name
     * @return Schema
     */
    public function schema($name)
    {
        return new Schema($name, $this->configurator);
    }

    public function providers()
    {
        return new Providers();
    }

    /**
     * @param $id
     * @param array $item
     * @return FormBuilder|array
     */
    public function update($id, array $item)
    {
        $Item = $this->model->find($id);

        //run validations for provider config fields
        $errorsFound = $this->validateMeta($item);
        if (is_array($errorsFound)){
            return $errorsFound;
        }

        $Item->update($item);
        Event::fire('form.updated',$Item);
        \Artisan::call('view:clear');
        return $Item;
    }

    /**
     * @param array $item
     * @return FormBuilder|array
     */
    public function store(array $item)
    {
        //run validations for provider config fields
        $errorsFound = $this->validateMeta($item);
        if (is_array($errorsFound)){
            return $errorsFound;
        }
        $item['slug'] = camel_case($item['slug']);//in case the user fucked up
        $Item = $this->model->create($item);
        Event::fire('form.created',$Item);

        return $Item;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function destroy($id)
    {
        $Item = $this->model->find($id);
        Event::fire('form.deleted',$Item);

        return $Item->delete();
    }

    /**
     * @param array $providers
     * @param Request $request
     * @param FormBuilder $form
     */
    public function process(array $providers, Request $request, FormBuilder $form)
    {
        //we need to get the providers to process it
        $providers = $this->providers()->load()->get()->whereIn('varName', $providers);

        foreach ($providers as $provider) {
            (new $provider['class'])->process($request, $form);
        }
    }

    private function validateMeta($item)
    {
        if (isset($item['meta']['providerConfig'])){
            $validators = [];
            $errors = [];
            foreach ($this->model->providers($item['provider']) as $providerInstance){
                $validators[$providerInstance->varName] = $providerInstance;
            }

            foreach ($item['meta']['providerConfig'] as $provider => $values) {
                if ( ! isset($validators[$provider])){
                    continue;
                }

                try {
                    $validators[$provider]->validate($values);
                }
                catch (InvalidProviderConfiguration $e){

                    $errorMessage = json_decode($e->getMessage(), true);
                    $errors[$provider][key($errorMessage)] = $errorMessage[key($errorMessage)];
                }
            }

            if (count($errors) > 0){
                return $errors;
            }
        }

        return true;
    }
}