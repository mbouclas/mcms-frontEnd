<?php
    return [
        'configurator' => Mcms\FrontEnd\FormBuilder\BaseFormBuilderConfigurator::class,
        'schema' => [
            'default' => 'admin'
        ],
        'route' => [
            'name' => 'postForm',
            'config' => [
                'as' => 'formBuilder-post',
                'uses'=> 'Mcms\FrontEnd\Http\Controllers\Admin\FormBuilderController@process'
            ],
            'middleware' => ['web'],
        ],
        'providers' => [
            \Mcms\FrontEnd\FormBuilder\Providers\Mail::class,
            \Mcms\FrontEnd\FormBuilder\Providers\Mailchimp::class,
            \Mcms\FrontEnd\FormBuilder\Providers\DataBase::class,
        ]
    ];