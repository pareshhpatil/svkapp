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
        //'App\Console\Commands\Expensesave',
        'App\Console\Commands\FoodFranchiseSale',
        'App\Console\Commands\FoodFranchiseInvoice',
        'App\Console\Commands\PaymentRequestReminder',
        'App\Console\Commands\CreateSiteMap',
        'App\Console\Commands\HighValuePayments',
        'App\Console\Commands\FoodFranchiseInvoiceReport',
        'App\Console\Commands\AutocollectPayments',
        'App\Console\Commands\MigrateStoredProcedures'
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('expense:save')
        //   ->withoutOverlapping()
        //   ->everyFiveMinutes();
        $schedule->command('franchise:sale')
            ->dailyAt('11:30');
        $schedule->command('contactrequest:reminder')
            ->dailyAt('9:15');
        $schedule->command('franchise:invoice')
            ->monthlyOn(1, '12:20');
        $schedule->command('franchise:invoicereport')
            ->monthlyOn(4, '15:30');
        $schedule->command('franchise:invoice')
            ->weeklyOn(4, '12:15');
        $schedule->command('create:sitemap')
            ->dailyAt('03:00');
        $schedule->command('getPayments:email')
            ->dailyAt('03:30');
        $schedule->command('autocollect:payments')
            ->dailyAt('9:30');
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
