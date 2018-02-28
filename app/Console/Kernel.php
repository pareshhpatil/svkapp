<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Apirequestsend',
        'App\Console\Commands\bulkuploadsave',
        'App\Console\Commands\bulkuploadsend',
        'App\Console\Commands\remindersend',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('apiinvoice:send')
                ->withoutOverlapping()
                ->hourly();
        $schedule->command('bulkinvoice:send')
                ->withoutOverlapping()
                ->hourly();
        $schedule->command('bulkinvoice:save')
                ->withoutOverlapping()
                ->everyFiveMinutes();
        $schedule->command('reminder:send')
                ->dailyAt('09:00');
        // $schedule->command('log:demo')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands() {
        require base_path('routes/console.php');
    }

}
