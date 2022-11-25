<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MerchantServiceAddToCRM implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $merchant_id;
    protected $title;
    protected $merchant_name;
    protected $description;
    protected $registration_channel;
    protected $due_date;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $merchant_id, $merchant_name, $title, $description, $registration_channel = 'web', $due_date = null)
    {
        //
        $this->type = $type;
        $this->merchant_id = $merchant_id;
        $this->merchant_name = $merchant_name;
        $this->title = $title;
        $this->description = $description;
        $this->registration_channel = $registration_channel;
        $this->due_date = $due_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
