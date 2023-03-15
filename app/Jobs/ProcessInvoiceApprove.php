<?php

namespace App\Jobs;

use App\Notifications\InvoiceApprovalNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessInvoiceApprove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /***
     * @var User $User
     */
    protected $User;

    protected $invoiceNumber;

    protected $paymentRequestID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoiceNumber, $paymentRequestID, $User)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->paymentRequestID = $paymentRequestID;
        $this->User = $User;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->User->notify(new InvoiceApprovalNotification($this->invoiceNumber, $this->paymentRequestID, $this->User));
    }
}
