<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:job';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        TestJob::dispatch();
        //TestJob::dispatch()->onQueue('promotion-sms-dev')->onConnection('sqs');
    }
}