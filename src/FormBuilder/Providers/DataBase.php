<?php

namespace Mcms\FrontEnd\FormBuilder\Providers;


use Mcms\FrontEnd\FormBuilder\Contracts\FormBuilderProvider;
use Mcms\FrontEnd\FormBuilder\FormLogService;
use Mcms\FrontEnd\Models\FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DataBase implements FormBuilderProvider
{

    public $label = 'Database';
    public $description = 'Stores the form in the database';
    public $varName = 'database';
    public $config = [];
    protected $Service;

    public function __construct()
    {
        $this->Service = new FormLogService();
    }

    /**
     * @return Collection
     */
    public function register()
    {
        return new Collection([
            'label' => $this->label,
            'description' => $this->description,
            'varName' => $this->varName,
            'config' => $this->config,
            'class' => get_class($this),
        ]);
    }

    /**
     * @return mixed
     */
    public function controller()
    {
        // TODO: Implement controller() method.
    }

    /**
     * @return mixed
     */
    public function url()
    {
        // TODO: Implement url() method.
    }

    /**
     * @return mixed
     */
    public function isValid()
    {
        // TODO: Implement isValid() method.
    }

    /**
     * @return mixed
     */
    public function validate(array $fieldValues)
    {
        // TODO: Implement validate() method.
    }

    /**
     * @return mixed
     */
    public function process(Request $request, FormBuilder $form)
    {
        return $this->Service->store([
            'form_id' => $form->id,
            'data' => $request->all()
        ]);
    }

    /**
     * @return mixed
     */
    public function result()
    {
        // TODO: Implement result() method.
    }
}