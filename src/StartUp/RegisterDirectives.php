<?php

namespace Mcms\FrontEnd\StartUp;


use Mcms\Core\Widgets\Widget;
use Mcms\FrontEnd\FormBuilder\Directives\Form;

/**
 * Register all your Blade directives here
 * Class RegisterDirectives
 * @package Mcms\FrontEnd\StartUp
 */
class RegisterDirectives
{
    /**
     *
     */
    public function handle()
    {
        Form::registerDirective();//will register the @Form directive
    }
}