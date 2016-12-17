<?php

namespace Mcms\FrontEnd\UserRegistration;


use Config;

use Mcms\Core\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Mail;

class SendMailViaConfig
{

    public function send($config, $user)
    {

        if ( ! Config::has($config)){
            return false;
        }

        $config = Config::get($config);

        $activationClass = $config['handle'];
        $message = (new $activationClass($user));

        if (Config::has('frontEnd.user.mailables.queue')){
            $message = $message->onQueue(Config::get('frontEnd.user.mailables.queue'));
        }


        //send mail
        Mail::to($user)
            ->queue($message);

        return true;
    }

    public function formAdminUser()
    {
        return  Config::get('mail.from');
    }

    public function sanitizeModelToArray($model)
    {
        $exclude = $model->getCasts();

        $arr = $model->toArray();
        foreach ($arr as $key => $val){
            if (array_key_exists($key, $exclude)){
                unset($arr[$key]);
            }
            $exclude[] = $key;
        }

        return $arr;
    }
}