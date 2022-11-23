<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SupportTeamNotification implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $subject;
    protected $message;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $message, $type = 'SUPPORT')
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->type = $type;
    }

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
