<?php

namespace Mcms\FrontEnd\UseCases;

/**
 * This is an example of a self instantiated class using a static interface
 * what this allows us to do is have a static API while maintaining a non static class
 * e.g. SelfCreatingStatic::perform(); Will go ahead and instantiate the class and call
 * the handle method which probably does some stuff
 * Class UseCase
 * @package FrontEnd\UseCases
 */
abstract class UseCaseAbstract
{
    /**
     * @return mixed
     */
    public static function perform(){
        return (new static)->handle();
    }

    abstract public function handle();
}