<?php

use App\Http\Controllers\PaymentWrapperController;

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class Payment extends Api
{

    private $errorlist = NULL;

    function __construct($type = null)
    {
        if ($type != null) {
            $this->webrequest = false;
        }
        parent::__construct('Merchant');
        $this->version = 'v1';
    }

    public function received()
    {
        $req_time = date("Y-m-d H:i:s");
        $from_date = $this->jsonArray['from_date'];
        $to_date = $this->jsonArray['to_date'];
        require_once MODEL . 'merchant/TransactionModel.php';
        $transactionModel = new TransactionModel();
        require_once CONTROLLER . 'Paymentvalidator.php';

        $array1 = $this->getPaymentReceivedJson();
        $this->compairJsonArray($array1, $this->jsonArray);

        $_POST['from_date'] = $from_date;
        $_POST['to_date'] = $to_date;
        $_POST['to_cdate'] = $to_date;

        $validator = new Paymentvalidator($transactionModel);
        $validator->validatePaymentReceivedAPI();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == FALSE) {
            $datetime1 = new DateTime($from_date);
            $datetime2 = new DateTime($to_date);
            $interval = $datetime1->diff($datetime2);
            $diff = $interval->format('%a');
            if ($diff > 31) {
                return $this->printJsonResponse($req_time, 'ER02010');
            } else {
                try {
                    $result = $transactionModel->getPaidInvoiceDetails($this->user_id, $from_date, $to_date);
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    SwipezLogger::error(__CLASS__, '[A0010]Error while get payment request details payment request id:[' . $payment_req_id . '] Error:' . $e->getMessage());
                    return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
                }
            }
        } else {
            foreach ($hasErrors as $key => $value) {
                $path = ($path_ != NULL) ? $path_ . "." . $key : $key;
                switch ($key) {
                    case 'from_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_cdate':
                        $this->createErrorlist('ER02009', $key, $path);
                        break;
                }
            }
        }

        return $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    public function status()
    {
        $req_time = date("Y-m-d H:i:s");
        $type = $this->jsonArray['transaction_type'];
        $id = $this->jsonArray['id'];
        require_once MODEL . 'merchant/TransactionModel.php';
        $transactionModel = new TransactionModel();

        $array1 = $this->getPaymentStatusJson();
        $this->compairJsonArray($array1, $this->jsonArray);

        switch ($type) {
            case 'MERCHANT_TRANS_ID':
                $mode = 1;
                break;
            case 'SWIPEZ_TRANS_ID':
                $mode = 2;
                break;
            case 'SWIPEZ_REQ_ID':
                $mode = 3;
                break;
            default:
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02020');
                break;
        }

        try {
            $result = $transactionModel->getTransactionStatus($this->merchant_id, $this->user_id, $id, $mode);
            if (empty($result)) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02021');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[A0011]Error while get transaction status id:[' . $id . '] Mode: ' . $mode . ' Error:' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
        }


        return $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    public function settelementlist()
    {
        $req_time = date("Y-m-d H:i:s");
        $from_date = $this->jsonArray['from_date'];
        $to_date = $this->jsonArray['to_date'];
        require_once MODEL . 'merchant/ReportModel.php';
        $transactionModel = new ReportModel();
        require_once CONTROLLER . 'Paymentvalidator.php';

        $array1 = $this->getSettlementListJson();
        $this->compairJsonArray($array1, $this->jsonArray);

        $_POST['from_date'] = $from_date;
        $_POST['to_date'] = $to_date;
        $_POST['to_cdate'] = $to_date;

        $validator = new Paymentvalidator($transactionModel);
        $validator->validatePaymentReceivedAPI();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == FALSE) {
            $datetime1 = new DateTime($from_date);
            $datetime2 = new DateTime($to_date);
            $interval = $datetime1->diff($datetime2);
            $diff = $interval->format('%a');
            if ($diff > 31) {
                return $this->printJsonResponse($req_time, 'ER02010');
            } else {
                try {
                    $franchise_id = ($this->jsonArray['franchise_id'] > 0) ? $this->jsonArray['franchise_id'] : 0;
                    $result = $transactionModel->get_ReportPaymentsettlementSummary($this->merchant_id, $from_date, $to_date, $franchise_id);
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    SwipezLogger::error(__CLASS__, '[A0010]Error while get SETTLEMENT LIST merchant id:[' . $this->merchant_id . '] Error:' . $e->getMessage());
                    return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER01008');
                }
            }
        } else {
            foreach ($hasErrors as $key => $value) {
                $path = ($path_ != NULL) ? $path_ . "." . $key : $key;
                switch ($key) {
                    case 'from_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_date':
                        $this->createErrorlist('ER02002', $key, $path);
                        break;
                    case 'to_cdate':
                        $this->createErrorlist('ER02009', $key, $path);
                        break;
                }
            }
        }
        $report = $result;
        $result = array();
        $int = 0;
        foreach ($report as $row) {
            $result[$int]['settlement_date'] = $row['settlement_date'];
            $result[$int]['bank_reference'] = $row['bank_reference'];
            $result[$int]['calculated_settlement'] = $row['settlement_amount'];
            $result[$int]['settlement_amount'] = $row['requested_settlement_amount'];
            $result[$int]['total_capture'] = $row['total_capture'];
            $result[$int]['total_tdr'] = $row['total_tdr'];
            $result[$int]['total_tax'] = $row['total_service_tax'];
            $result[$int]['franchise_id'] = $row['franchise_id'];
            $result[$int]['franchise_name'] = $row['franchise_name'];
            $int++;
        }
        return $this->printJsonResponse($req_time, $error_code, $result, $this->errorlist);
    }

    function refund()
    {
        try {
            $req_time = date("Y-m-d H:i:s");
            $array1 = $this->getRefundJson();
            $this->compairJsonArray($array1, $this->jsonArray);
            $error_code = 0;
            require_once MODEL . 'merchant/TransactionModel.php';
            $transactionModel = new TransactionModel();
            $transaction_id = $this->jsonArray['transaction_id'];
            $amount = $this->jsonArray['amount'];
            $reason = $this->jsonArray['reason'];
            $info = $transactionModel->getTransactionDetail($transaction_id, $this->merchant_id);
            if (isset($info['pg_ref_no1'])) {
                $info['pg_ref_no'] = $info['pg_ref_no1'];
            }
            if (isset($info['pg_ref_1'])) {
                $info['pg_ref_no2'] = $info['pg_ref_1'];
            }
            
            if (empty($info) || $info['status'] != 1) {
                return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02021');
            } else {
                $payment = new PaymentWrapperController();
                $pg_detail = $this->common->getSingleValue('payment_gateway', 'pg_id', $info['pg_id']);
                $is_settled = $this->common->getRowValue('id', 'payment_transaction_settlement', 'transaction_id', $transaction_id);
                if ($pg_detail['type'] == 2) {
                    $vendor_id = $this->common->getRowValue('vendor_id', 'merchant_fee_detail', 'fee_detail_id', $info['fee_id']);
                    $vendor = $this->common->getSingleValue('vendor', 'vendor_id',  $vendor_id);
                    $balance = $vendormodel->unsettleBalance($vendor['merchant_id'], $info['fee_id']);
                    if ($balance > $data['amount']) {
                        require_once MODEL . 'merchant/VendorModel.php';
                        $vendormodel = new VendorModel();
                        $pg_vendor_id = $vendor['pg_vendor_id'];
                        $data['amount'] = $info['amount'];
                        $data['narrative'] = 'Refund transaction';
                        $adjustment_id = $vendormodel->saveAdjustment($vendor['merchant_id'], $data, 'DEBIT', $pg_vendor_id, $vendor['pg_vendor_id']);
                        $cashfree->adjustVendorBalance($pg_vendor_id, $adjustment_id, 'DEBIT', $data['narrative'],  $data['amount']);
                    } else {
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02043');
                    }
                }
                $pg_type = $pg_detail['pg_type'];
                if ($is_settled == false) {
                    $swipez_settled = 0;
                } else {
                    $swipez_settled = 1;
                }

                if ($pg_type == 7 || $pg_type == 4) {
                    $settlement_type = 1;
                } else {
                    $settlement_type = 2;
                }
                $refund_id = $transactionModel->refundSave($transaction_id, $this->merchant_id, $info['amount'], $amount, $info['pg_id'], $settlement_type, $swipez_settled, $reason, $this->user_id);
                
                if ($refund_id > 0) {
                    switch ($pg_type) {
                        case 3:
                            $result = $payment->payuRefund($pg_detail['pg_val7'], $info['pg_ref_no'], $amount);
                            $reference_id = $result['refund_id'];
                            break;
                        case 4:

                            break;
                        case 7:
                            $result = $payment->cashfreeRefund($pg_detail['pg_val1'], $pg_detail['pg_val2'], $transaction_id, $info['pg_ref_no'], $amount, $reason);
                            $response_message = $result['message'];
                            $reference_id = $result['refund_id'];
                            break;
                        case 11:
                            $result = $payment->stripeRefund($pg_detail['pg_val1'], $pg_detail['pg_val2'], $info['pg_ref_no2'], $amount, $reason, $pg_detail['pg_val7']);
                            $reference_id = $result;
                            break;
                        case 10:
                            $result = $payment->razorpayRefund($pg_detail['pg_val1'], $pg_detail['pg_val2'], $info['pg_ref_no2'], $amount, $reason, $pg_detail['pg_val7']);
                            $reference_id = $result;
                            break;
                        case 9:
                            $result = $payment->cashfreeRefund($pg_detail['pg_val1'], $pg_detail['pg_val2'], $transaction_id, $info['pg_ref_no'], $amount, $reason);
                            $response_message = $result['message'];
                            $reference_id = $result['refund_id'];
                            break;
                        case 2:
                            $result = $payment->paytmRefund($pg_detail['pg_val2'], $pg_detail['pg_val1'], $transaction_id, $refund_id, $amount, $info['pg_ref_no']);
                            $reference_id = $result['refund_id'];
                            break;
                        case 13:
                            $result = $payment->setuRefund($pg_detail, $transaction_id, $refund_id, $amount, $info['pg_ref_no']);
                            $reference_id = $result['refund_id'];
                            break;
                    }
                    if ($reference_id != FALSE) {
                        $this->common->genericupdate('refund_request', 'refund_id', $reference_id, 'id', $refund_id);
                        $success = array('transaction_id' => $transaction_id, 'amount' => $amount, 'refund_id' => $refund_id);
                        if (substr($transaction_id, 0, 1) == 'T') {
                            $this->common->genericupdate('payment_transaction', 'payment_transaction_status', 5, 'pay_transaction_id', $transaction_id);
                            $customer_id = $this->common->getRowValue('customer_id', 'payment_transaction', 'pay_transaction_id', $transaction_id);
                            $toEmail_ = $this->common->getRowValue('email', 'customer', 'customer_id', $customer_id, 0);
                            $toMobile_ = $this->common->getRowValue('mobile', 'customer', 'customer_id', $customer_id, 0);
                        } else {
                            $this->common->genericupdate('xway_transaction', 'xway_transaction_status', 5, 'xway_transaction_id', $transaction_id);
                            $toEmail_ = $this->common->getRowValue('email', 'xway_transaction', 'xway_transaction_id', $transaction_id);
                            $toMobile_ = $this->common->getRowValue('phone', 'xway_transaction', 'xway_transaction_id', $transaction_id);
                        }

                        $detail = $this->common->getMerchantProfile($this->merchant_id);
                        $merchant_email = $detail['business_email'];
                        $company_name = $detail['company_name'];
                        $emailWrapper = new EmailWrapper();
                        $emailWrapper->merchant_email_ = $merchant_email;
                        $emailWrapper->cc = array($merchant_email);
                        $emailWrapper->from_name_ = $company_name;
                        $mailcontents = $emailWrapper->fetchMailBody("patron.refund");
                        $message = $mailcontents[0];
                        $message = str_replace('__COMPANY_NAME__', $company_name, $message);
                        $message = str_replace('__AMOUNT__', $amount, $message);
                        $message = str_replace('__TRANSACTION_ID__', $transaction_id, $message);
                        $subject = str_replace('__COMPANY_NAME__', $company_name, $mailcontents[1]);
                        $emailWrapper->sendMail($toEmail_, "", $subject, $message);

                        $SMSMessage = new SMSMessage();
                        $sms = $SMSMessage->fetch('p12');
                        if (strlen($company_name) > 15) {
                            $company_name = substr($company_name, 0, 13) . '...';
                        }
                        $sms = str_replace('<merchant name>', $company_name, $sms);
                        $sms = str_replace('<amount>', $amount, $sms);
                        $sms = str_replace('<merchant name>', $company_name, $sms);
                        $sms = str_replace('<amount>', $amount, $sms);
                        $json = '{"access_key_id":"' . $this->jsonArray['access_key_id'] . '","secret_access_key":"' . $this->jsonArray['secret_access_key'] . '","data":[{"mobile":"' . $toMobile_ . '","sms":"' . $sms . '"}]}';
                        $this->curlRequest('data=' . $json, 'https://api.swipez.in/api/v1/merchant/sms/send');
                    } else {
                        $this->common->genericupdate('refund_request', 'refund_status', '-1', 'id', $refund_id);
                        return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02038', $response_message);
                        return;
                    }
                }
            }
            return $this->printJsonResponse($req_time, $error_code, $success, $this->errorlist);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[' . $this->version . ']' . '[' . $this->merchant_id . ']' . '[A0006]Error while refund initiated Error: for user [' . $this->user_id . '] and for template id [' . $template_id . ']' . $e->getMessage());
            return $this->printJsonResponse(date("Y-m-d H:i:s"), 'ER02015');
        }
    }

    function createErrorlist($code, $key_name, $key_path)
    {
        $message = $this->ApiErrors->fetch($code);
        $this->errorlist[] = array('code' => $code, 'msg' => $message, 'keyname' => $key_name, 'keypath' => $key_path);
    }

    function curlRequest($post_string, $post_url, $filter = 1)
    {
        try {
            if ($filter == 1) {
                $post_string = str_replace('&', '%26', $post_string);
                $post_string = str_replace('@', '%40', $post_string);
            }
            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            if (curl_error($ch)) {
                SwipezLogger::error(__CLASS__, 'Curl Error: Error while calling invoice Curl Post Data: ' . $post_string . ' API Error: ' . curl_error($ch));
            }
            $data1 = curl_exec($ch);
            curl_close($ch);
            return $data1;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while curl request Post string: ' . $post_string . ' Post Url: ' . $post_url . ' Error: ' . $e->getMessage());
        }
    }

    function reconcile()
    {
        $req_time = date("Y-m-d H:i:s");
        $transaction_id = $this->jsonArray['transaction_id'];
        require_once UTIL . 'ReconcileWrapper.php';
        $reconcile = new ReconcileWrapper();
        $reconcile->notify = 1;
        $reconcile->transactionIds = "'" . $transaction_id . "'";
        $transaction_rows = $reconcile->fetchSwipezDataFromDB();
        $error_code = '';
        if (!empty($transaction_rows)) {
            $response = $reconcile->ReconcileIncompleteTransaction($transaction_rows, 'Swipez');
        } else {
            $xway_rows = $reconcile->fetchXwayDataFromDB();
            if (!empty($xway_rows)) {
                $response = $reconcile->ReconcileIncompleteTransaction($xway_rows, 'Xway');
            } else {
                $error_code = 'ER02021';
                $response = 'Transaction not found';
            }
        }
        return $this->printJsonResponse($req_time, $error_code, $response, $this->errorlist);
    }
}
