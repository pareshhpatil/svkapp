<?php

namespace App\Helpers\Merchant;

use App\Jobs\ProcessInvoiceApprove;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;

/**
 * @author Nitish
 */
class InvoiceHelper
{
    public function sendInvoiceForApprovalNotification($paymentRequestID)
    {
        $merchantID = Encrypt::decode(Session::get('merchant_id'));
        ProcessInvoiceApprove::dispatch($paymentRequestID, $merchantID)->onQueue('promotion-sms-dev');
    }
}