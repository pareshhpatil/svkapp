<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\ImportPassenger',
        'App\Console\Commands\ImportRoster',
        'App\Console\Commands\RideReminder',
        'App\Console\Commands\RideEndReminder',
        'App\Console\Commands\BookingReminder',
        'App\Console\Commands\BackupDatabase',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('import:passenger')->everyFiveMinutes();
        $schedule->command('import:roster')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('ride:reminder')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('rideend:reminder')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('booking:reminder')->dailyAt('16:00')->withoutOverlapping();
        $schedule->command('backup:mysql')->dailyAt('14:00')->withoutOverlapping();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
