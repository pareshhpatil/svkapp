<?php

namespace App\Listeners;

use App\Events\RideStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendRideStatusNotification;

class HandleRideStatusChange
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
     * @param  \App\Events\RideStatusChanged  $event
     * @return void
     */
    public function handle(RideStatusChanged $event)
    {
        dispatch(new SendRideStatusNotification($event->ride_id, $event->status));
    }
}
