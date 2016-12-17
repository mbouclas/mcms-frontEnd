<?php

namespace Mcms\FrontEnd\Listeners;

use Mcms\FrontEnd\Events\AppWasBooted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleAppBoot
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AppWasBooted  $event
     * @return void
     */
    public function handle(AppWasBooted $event)
    {
//        print_r($event->args);
    }
}
