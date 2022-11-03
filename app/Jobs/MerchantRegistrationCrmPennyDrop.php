<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MerchantRegistrationCrmPennyDrop implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $merchant_id;
    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($merchant_id, $status)
    {
        $this->merchant_id = $merchant_id;
        $this->status = $status;
    }

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
