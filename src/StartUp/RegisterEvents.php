<?php

namespace Mcms\FrontEnd\StartUp;

use Config;
use Mcms\FrontEnd\Services\ImageOptimizer;
use Mcms\FrontEnd\UserRegistration\SendMailViaConfig;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Support\ServiceProvider;

/**
 * Register your events here
 * Class RegisterEvents
 * @package Mcms\FrontEnd\StartUp
 */
class RegisterEvents
{
    /**
     * @param ServiceProvider $serviceProvider
     * @param DispatcherContract $events
     */
    public function handle(ServiceProvider $serviceProvider, DispatcherContract $events)
    {
        $events->listen('user.email.send.approved', function ($user) {
            if (Config::has('frontEnd.user.mailables.approved')){
                $mailer = new SendMailViaConfig();
                $mailer->send('frontEnd.user.mailables.approved', $user);
            }
        });

/*        $events->listen('image.uploaded', function ($image) {
            if ( ! isset($image['copies']) || ! $image['copies'] || ! is_array($image['copies'])){
                if ( ! isset($image['data']['path'])){//seriously invalid
                    return;
                }

                ImageOptimizer::optimize($image['data']['path'], true);
                return;
            }

            //we have copies
            foreach ($image['copies'] as $copy) {
                ImageOptimizer::optimize($copy['path'], true);
            }
        });*/
    }
}