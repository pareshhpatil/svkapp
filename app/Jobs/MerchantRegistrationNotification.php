<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MerchantRegistrationNotification implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $merchant_id;
    protected $company;
    protected $email;
    protected $mobile;
    protected $first_name;
    protected $last_name;
    protected $campaign;
    protected $service_id;
    protected $registration_channel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($merchant_id, $company, $email, $mobile, $first_name, $last_name, $campaign, $service_id,$registration_channel='web') {
        $this->merchant_id = $merchant_id;
        $this->company = $company;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->campaign = $campaign;
        $this->service_id = $service_id;
        $this->registration_channel = $registration_channel;
        //
    }

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }

   
}
