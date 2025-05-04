<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\RideStatusChanged;
use App\Events\PassengerStatusChanged;
use App\Listeners\HandleRideStatusChange;
use App\Listeners\HandlePassengerStatusChange;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    protected $listen = [
        RideStatusChanged::class => [
            HandleRideStatusChange::class,
        ],
        PassengerStatusChanged::class => [
            HandlePassengerStatusChange::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
