<?php

namespace Mcms\FrontEnd\FormBuilder\Widgets;


use Arrilot\Widgets\AbstractWidget;

class Form extends AbstractWidget
{
    protected $config = [

    ];

    public function run()
    {

        return view("forms.widget");
    }
}