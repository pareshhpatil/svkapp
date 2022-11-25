<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Model\Payment;
use App\Http\Controllers\API\APIController;
use Exception;
use Log;
use App\Jobs\EInvoiceCreation;

class PaymentController extends APIController
{

    public function status(Request $request)
    {

        if (!isset($request->APIdata['id'])) {
            return $this->APIResponse('ER01008');
        }
        $id = $request->APIdata['id'];

        $paymentModel = new Payment();

        $valid_mode = array('MERCHANT_TRANS_ID' => 1, 'SWIPEZ_TRANS_ID' => 2, 'SWIPEZ_REQ_ID' => 3);
        if (isset($valid_mode[$request->APIdata['transaction_type']])) {
            $mode = $valid_mode[$request->APIdata['transaction_type']];
        } else {
            return $this->APIResponse('ER02020');
        }

        try {
            $result = $paymentModel->getPaymentStatus($request->merchant_id, $request->user_id, $id, $mode);
            if (empty($result)) {
                return $this->APIResponse('ER02021');
            }
            return $this->APIResponse('', $result);
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . '[A0011]Error while get transaction status id:[' . $id . '] Merchant id : ' . $request->merchant_id . ' Error:' . $e->getMessage());
            return $this->APIResponse('ER01008');
        }
    }

    public function einvoiceQueue(Request $request)
    {
        $data = file_get_contents('php://input');
        $array = json_decode($data, 1);
        $source = $array['source'];
        foreach ($array['data'] as $row) {
            EInvoiceCreation::dispatch($row['id'], $source, $row['notify'])->onQueue(env('SQS_EINVOICE_QUEUE'));
        }
    }
}
