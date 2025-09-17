<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\HomeController;

class GenerateRoute implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $model;
    private $created_date;
    private $notification_send;

    public function __construct(
        public int $roster_id
    ) {}

    public function handle()
    {
        $roster_id = $this->roster_id;
        $HomeController = new HomeController();
        $HomeController->generateRoute($roster_id);
    }
}
