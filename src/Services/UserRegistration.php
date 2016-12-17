<?php

namespace Mcms\FrontEnd\Services;


use Config;

class UserRegistration
{
    public function before($user)
    {
        if ( ! Config::has('frontEnd.user.register.before')){
            return false;
        }

        $class = Config::get('frontEnd.user.register.before');

        try {
            return (new $class())->handle($user);
        } catch (\Exception $e){
            return false;
        }
    }

    public function after($user)
    {
        if ( ! Config::has('frontEnd.user.register.after')){
            return false;
        }

        $class = Config::get('frontEnd.user.register.after');

        try {
            return (new $class())->handle($user);
        } catch (\Exception $e){
            return false;
        }
    }

    public function onVerify($code)
    {
        if ( ! Config::has('frontEnd.user.register.after')){
            return false;
        }

        $class = Config::get('frontEnd.user.register.after');
        try {
            return (new $class())->onVerify($code);
        } catch (\Exception $e){
            return false;
        }
    }
}