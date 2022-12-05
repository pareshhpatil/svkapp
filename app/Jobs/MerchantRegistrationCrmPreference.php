<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MerchantRegistrationCrmPreference implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $merchant_id;
    protected $billing;
    protected $customer;
    protected $paymentlink;
    protected $onlinepayment;
    protected $recurring;
    protected $bulkupload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($merchant_id, $billing, $customer, $paymentlink, $onlinepayment, $recurring, $bulkupload)
    {
        $this->merchant_id = $merchant_id;
        $this->billing = $billing;
        $this->customer = $customer;
        $this->paymentlink = $paymentlink;
        $this->onlinepayment = $onlinepayment;
        $this->recurring = $recurring;
        $this->bulkupload = $bulkupload;
        //
    }

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }
}
