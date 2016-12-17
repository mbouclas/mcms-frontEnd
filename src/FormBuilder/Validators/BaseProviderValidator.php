<?php

namespace Mcms\FrontEnd\FormBuilder\Validators;


use Mcms\FrontEnd\Exceptions\InvalidProviderConfiguration;
use Illuminate\Support\Collection;
use Validator;

class BaseProviderValidator
{
    public $fields;

    public function __construct(array $fields)
    {
        $this->fields = [];
        foreach ($fields as $field) {
            $this->fields[$field['varName']] = (isset($field['validator'])) ? $field['validator'] : 'required';
        }
    }

    public function check(array $item)
    {
        $check = Validator::make($item, $this->fields);

        if ($check->fails()) {
            throw new InvalidProviderConfiguration($check->errors());
        }

        return true;
    }
}