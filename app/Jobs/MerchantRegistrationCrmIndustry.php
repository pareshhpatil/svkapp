<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MerchantRegistrationCrmIndustry implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $merchant_id;
    protected $industry;
    protected $customer;
    protected $employee;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($merchant_id, $industry, $customer, $employee)
    {
        $this->merchant_id = $merchant_id;
        $this->industry = $industry;
        $this->customer = $customer;
        $this->employee = $employee;
        //
    }

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
