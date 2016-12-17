<?php

namespace FrontEnd\StartUp;


use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class RegisterEvents
{
    public function handle(ServiceProvider $serviceProvider, DispatcherContract $events)
    {
/*        $events->listen('image.uploaded', function ($image) {
            //
            print_r($image);
        });*/
    }
}