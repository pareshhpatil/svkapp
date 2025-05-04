<?php

namespace App\Listeners;

use App\Events\PassengerStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendPassengerStatusNotification;

class HandlePassengerStatusChange
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
     * @param  \App\Events\PassengerStatusChanged  $event
     * @return void
     */
    public function handle(PassengerStatusChanged $event)
    {
        dispatch(new SendPassengerStatusNotification($event->passenger_id, $event->status));
    }
}
