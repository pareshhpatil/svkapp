<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Libraries\Helpers;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Libraries\MailWrapper;
use Exception;
use Log;


class MerchantSendMail implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $subject;
    protected $message;
    protected $type;
    protected $to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $message, $type = 'MAIL_VENDOR')
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->type = $type;
    }

    public function handle()
    {
        if ($this->type == 'MAIL_VENDOR') {
            $blade_file_name = 'gst_vendor';
            Helpers::sendMail($this->to,  $blade_file_name, $this->message, $this->subject);
        }
        if ($this->type  == 'MAIL_BOOKING_CALENDAR') {
            $blade_file_name = 'booking_cancellation';
            Helpers::sendMail($this->to,  $blade_file_name, $this->message, $this->subject);
        }
    }

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
