<?php

namespace Mcms\FrontEnd\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AppWasBooted extends Event
{
    use SerializesModels;

    public $args;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        //
        $this->args = $args;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
