<?php

use Razorpay\Api\Api;
use App\Libraries\Easebuzz\Easebuzz;

class ReconcileWrapper
{

    public $db = NULL;
    public $encrypt = NULL;
    public $message = NULL;
    public $toDate = NULL;
    public $mode = NULL;
    public $notify = 1;
    public $fromDate = NULL;
    public $transactionIds = NULL;
    public $total_transactions = 0;
    public $total_reconciled_transactions = 0;
    public $mode_text = '';

    function __construct()
    {
        $this->db = new DBWrapper();
    }

    public function ReconcileIncompleteTransaction($rows, $type)
    {
        require_once MODEL . 'CommonModel.php';
        $model = new CommonModel();
        foreach ($rows as $row) {
            try {
                # Reconciling incomplete transaction id : 
                $result = array();
                SwipezLogger::info(__CLASS__, 'Reconciling incomplete transaction id: ' . $row['pay_transaction_id']);
                switch ($row['pg_type']) {
                    case 2:
                        $response = $this->getPaytmResponse($row['pay_transaction_id'], $row['pg_val1'], $row['pg_val2']);
                        break;
                    case 3:
                        $response = $this->getPayuResponse($row['pay_transaction_id'], $row['pg_val1'], $row['pg_val7']);
                        break;
                    case 7:
                        $response = $this->getCashfreeResponse($row['pay_transaction_id'], $row['pg_val1'], $row['pg_val2']);
                        break;
                    case 9:
                        $response = $this->getCashfreeResponse($row['pay_transaction_id'], $row['pg_val1'], $row['pg_val2']);
                        break;
                    case 4:
                        $response = $this->getAtomResponse($row['pay_transaction_id'], $row['pg_val1'], substr($row['created_date'], 0, 10), $row['amount']);
                        break;
                    case 8:
                        $key = base64_encode($row['pg_val4'] . ':' . $row['pg_val2']);
                        $response = $this->PaypalOrderStatus($row['pg_ref_no'], $key, $row['pg_val3']);
                        break;
                    case 10:
                        $response = $this->getRazorpayResponse($row['pg_ref_no'], $row['pg_val1'], $row['pg_val2'], $row['pg_val7']);
                        break;
                    case 11:
                        $response = $this->getStripeResponse($row['pg_ref_no'], $row['pg_val1'], $row['pg_val2'], $row['pg_val7']);
                        break;
                    case 12:
                        $response = $this->getPayoneerResponse($row, $row['pay_transaction_id']);
                        break;
                    case 13:
                        $response = $this->setuReconStatus($row['pay_transaction_id'], $row);
                        break;
                }
                if (empty($response)) {

                    SwipezLogger::error(__CLASS__, '[E32]Transaction id does not exist in payment gateway Transaction_id: ' . $row['pay_transaction_id']);
                } else {
                    switch ($row['pg_type']) {
                        case 2:
                            $result = $this->savePaytmResponse($response, $row['created_by']);
                            break;
                        case 7:
                            $response['orderId'] = $row['pay_transaction_id'];
                            $response['orderAmount'] = $row['amount'];
                            $result = $this->saveCashfreeResponse($response, $row['created_by']);
                            break;
                        case 9:
                            $response['orderId'] = $row['pay_transaction_id'];
                            $response['orderAmount'] = $row['amount'];
                            $result = $this->saveCashfreeResponse($response, $row['created_by']);
                            break;
                        case 13:
                            $response['orderId'] = $row['pay_transaction_id'];
                            $response['orderAmount'] = $row['amount'];
                            $result = $this->saveSetuResponse($response, $row['created_by']);
                            break;
                        case 3:
                            $response['payuMoneyId'] = $response['paymentId'];
                            if (isset($response['payuMoneyId'])) {
                                $result = $this->savePayuResponse($response, $row['created_by']);
                            }
                            break;
                        case 4:
                            $response['f_code'] = ($response['VERIFIED'] == 'SUCCESS') ? 'Ok' : 'F';
                            $result = $this->saveAtomResponse($response, $row['created_by']);
                            break;
                        case 10:
                            $result = $this->saveRazorpayResponse($response, $row['pay_transaction_id']);
                            break;
                        case 11:
                            $result = $this->saveStripeResponse($response, $row['pay_transaction_id']);
                            break;
                        case 12:
                            $result = $this->savePayoneerResponse($response, $row['pay_transaction_id']);
                            break;
                        case 8:
                            $response['orderId'] = $row['pay_transaction_id'];
                            $response['orderAmount'] = $row['amount'];
                            $response['txTime'] = $response['created_date'];
                            $response['referenceId'] = $row['pg_ref_no'];
                            if ($response['STATUS'] == 'TXN_SUCCESS') {
                                $response['status'] = 'success';
                            } else {
                                $response['status'] = 'failed';
                            }
                            $response['txMsg'] = '';
                            $result = $this->savePaypalResponse($response, $row['created_by']);
                            break;
                    }
                    if (!empty($result)) {
                        if ($response['STATUS'] == 'TXN_SUCCESS') {
                            $this->total_reconciled_transactions = $this->total_reconciled_transactions + 1;
                            SwipezLogger::info(__CLASS__, '[E33]Received response from PG Customer name:' . $result['patron_name'] . ' Email:' . $result['patron_email'] . ' Transaction id:' . $row['pay_transaction_id']);
                            $form_detail = $model->getSingleValue('form_builder_transaction', 'transaction_id', $row['pay_transaction_id']);
                            $model->genericupdate('form_builder_transaction', 'status', 1, 'transaction_id', $row['pay_transaction_id'], 'System');
                            if (!empty($form_detail)) {
                                $invoice_detail = $form_detail;
                            }

                            $xwaydet = $model->getSingleValue('xway_transaction', 'xway_transaction_id', $row['pay_transaction_id']);
                            if ($xwaydet['type'] == 3 && $xwaydet['plan_id'] > 0) {
                                $mer_setting = $model->getSingleValue('merchant_setting', 'merchant_id', $xwaydet['merchant_id']);
                                if ($mer_setting['plan_invoice_create'] == 1) {
                                    require_once CONTROLLER . 'InvoiceWrapper.php';
                                    $wrapper = new InvoiceWrapper($model);
                                    $plandetails = $wrapper->getPlanInvoiceDetails($xwaydet);
                                    $wrapper->xwayInvoice($plandetails);
                                }
                            }

                            if (!empty($invoice_detail)) {
                                if ($invoice_detail['invoice_enable'] == 1) {
                                    try {
                                        require_once CONTROLLER . 'InvoiceWrapper.php';
                                        $wrapper = new InvoiceWrapper($model);
                                        $wrapper->xwayInvoice($form_detail);
                                        $redirect_url = $this->getRedirectUrl($invoice_detail['json']);
                                        if ($redirect_url != '') {
                                            $array = $wrapper->setRedirectPost($row['pay_transaction_id'], 'success', $result['company_name']);
                                            foreach ($array as $key => $value) {
                                                $value = str_replace('&', '%26', $value);
                                                $value = str_replace('@', '%40', $value);
                                                $post_items[] = $key . '=' . $value;
                                            }
                                            //create the final string to be posted using implode()
                                            $post_string = implode('&', $post_items);
                                            $wrapper->curlRequest($post_string, $redirect_url, 'https', 0);
                                        }
                                    } catch (Exception $e) {
                                        Sentry\captureException($e);

                                        SwipezLogger::error(__CLASS__, '[E2145]Error while save form builder invoice Error: ' . $e->getMessage());
                                    }
                                }
                            }

                            if (substr($row['pay_transaction_id'], 0, 1) == 'F') {
                                require_once CONTROLLER . 'InvoiceWrapper.php';
                                $wrapper = new InvoiceWrapper($model);
                                $wrapper->getPackageInvoiceDetails($row['pay_transaction_id']);
                            }

                            $this->sendNotification($result, $response, $row, $type);
                            if ($this->mode == 2) {
                                return "Reconciled transaction :" . $row['pay_transaction_id'];
                            }
                        } else {
                            SwipezLogger::info(__CLASS__, 'Received failed transaction status from PG  Transaction_id: ' . $row['pay_transaction_id']);
                            if ($this->mode == 2) {
                                return "Failed transaction :" . $row['pay_transaction_id'];
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[EPG01]Error while Reconcile Transaction ID : ' . $row['pay_transaction_id'] . ' Error: ' . $e->getMessage());
            }
        }
    }

    public function setuGenerateToken($pg_details)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $pg_details["pg_val4"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "clientID" : "' . $pg_details["pg_val1"] . '",    
            "secret"   :  "' . $pg_details["pg_val2"] . '"
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        $token = $response["data"]["token"];
        return  $token;
    }

    public function setuReconStatus($trans_id, $row)
    {
        $token = $this->setuGenerateToken($row);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $row["pg_val5"] . '/' . $row['pg_ref_no'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-Setu-Product-Instance-ID: ' . $row["pg_val6"],
                'authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        if ($response["success"]) {
            if ($response['data']["status"] == 'PAYMENT_SUCCESSFUL') {
                $response['STATUS'] = 'TXN_SUCCESS';
            } else {
                $response['STATUS'] = 'failed';
                SwipezLogger::error(__CLASS__, '[E37]Response setu : ' . $trans_id . ' Response: ' . $response);
            }
            return $response;
        } else {

            SwipezLogger::error(__CLASS__, '[E37]Response setu : ' . $trans_id . ' Response: ' . $response);
        }
    }

    public function getPgDetailsbyID($pg_id, $merchant_id, $currency = 'INR')
    {
        $currency = (strlen($currency) == 3) ? $currency : 'INR';

        $sql = "SELECT fee_detail_id,pg.pg_id,pg.pg_type,pg.pg_val1,
        pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,
        pg.pg_val5,pg.pg_val6,pg.pg_val7,pg.pg_val8,
        pg.pg_val9,status_url,pg.type 
        from payment_gateway as pg 
        inner join merchant_fee_detail as fd on pg.pg_id=fd.pg_id 
        where fd.merchant_id=:merchant_id 
        and pg.pg_type=:pg_id 
        and fd.is_active=1 
        and fd.currency like '%" . $currency . "%' 
        order by fd.pg_fee_val desc 
        limit 1";
        $params = array(':merchant_id' => $merchant_id, ':pg_id' => $pg_id);
        $this->db->exec($sql, $params);
        return $this->db->single();
    }

    public function getSetuPlatformIDbyTransID($trans_id)
    {

        $sql = "SELECT platformBillID
        from pg_ret_bank11 
        where billerBillID=:billerBillID";
        $params = array(':billerBillID' => $trans_id);
        $this->db->exec($sql, $params);
        return $this->db->single();
    }

    public function PaypalOrderStatus($id, $secretKey, $mode)
    {
        try {
            if ($mode == 'Live') {
                $url = 'https://api.paypal.com/v2/checkout/orders/' . $id;
            } else {
                $url = 'https://api.sandbox.paypal.com/v2/checkout/orders/' . $id;
            }


            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Basic " . $secretKey,
                    "content-type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {

                SwipezLogger::error(__CLASS__, 'paypal response Curl Error: ' . $err);
            } else {
                SwipezLogger::debug(__CLASS__, 'paypal Gateway response: ' . $response);
                $array = json_decode($response, 1);
                if ($array['status'] == 'SUCCESS') {
                    $array['STATUS'] = 'TXN_SUCCESS';
                } else {
                    $array['STATUS'] = 'failed';

                    SwipezLogger::error(__CLASS__, '[E37]Response Paypal : ' . $id . ' Response: ' . $response);
                }
                return $array;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePAYUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Process input param given to the script
     *
     */
    public function processInputParams()
    {
        try {
            if (!isset($GLOBALS['argv'][1])) {
                $mode = 1;
            } else {
                $mode = $GLOBALS['argv'][1];
            }
            $this->mode = $mode;
            switch ($mode) {
                case 1:
                    $this->mode_text = '15 Min ';
                    $this->notify = 1;
                    break;
                case 2:

                    $ids = '';
                    $transaction_ids = $GLOBALS['argv'][2];
                    if (strlen($transaction_ids) > 9) {
                        $transaction_ids = explode(',', $transaction_ids);
                        foreach ($transaction_ids as $id) {
                            $ids .= "'" . $id . "',";
                        }
                        $this->transactionIds = substr($ids, 0, -1);
                    } else {
                        print "Missing Transaction Ids";
                        throw new Exception("Missing Transaction Ids");
                    }
                    $this->notify = $GLOBALS['argv'][3];
                    break;
                case 3:
                    $this->mode_text = 'Manual ';
                    $fromDate = $GLOBALS['argv'][2];
                    $result = $this->validateDate($fromDate);
                    if ($result != 1)
                        throw new Exception($result);

                    $fromTime = $GLOBALS['argv'][3];
                    $result = $this->validateTime($fromTime);
                    if ($result != 1)
                        throw new Exception($result);

                    $toDate = $GLOBALS['argv'][4];
                    $result = $this->validateDate($toDate);
                    if ($result != 1)
                        throw new Exception($result);

                    $toTime = $GLOBALS['argv'][5];
                    $result = $this->validateTime($toTime);
                    if ($result != 1)
                        throw new Exception($result);
                    $this->fromDate = $fromDate . ' ' . $fromTime;
                    $this->toDate = $toDate . ' ' . $toTime;
                    $this->notify = $GLOBALS['argv'][6];
                    break;
                case 4:
                    $this->mode_text = '3 Hr ';
                    $fromDate = date('Y-m-d H:i:s', strtotime('-3 hours', strtotime(date('Y-m-d H:i:s'))));
                    $toDate = date('Y-m-d H:i:s');
                    $this->fromDate = $fromDate;
                    $this->toDate = $toDate;
                    $this->notify = 1;
                    break;
                case 5:
                    $this->mode_text = '24 Hr ';
                    $fromDate = date('Y-m-d', strtotime('-2 day', strtotime(date('Y-m-d'))));
                    $fromTime = '00:00:00';
                    $toDate = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $toTime = '24:00:00';
                    $this->fromDate = $fromDate . ' ' . $fromTime;
                    $this->toDate = $toDate . ' ' . $toTime;
                    $this->notify = 1;
                    break;
                case 6:
                    $this->mode_text = '5 Min ';
                    $fromDate = date('Y-m-d H:i:s', strtotime('-5 minutes', strtotime(date('Y-m-d H:i:s'))));
                    $toDate = date('Y-m-d H:i:s');
                    $this->fromDate = $fromDate;
                    $this->toDate = $toDate;
                    $this->notify = 1;
                    break;
                default:
                    print "Invalid # of command line arguments. <Transactionids 1,2,3 OR 0><from date> <from time> <to date> <to time>";
                    throw new Exception("Invalid # of command line arguments");
                    break;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Invalid # of command line arguments');
        }
    }

    public function getPaytmResponse($transaction_id, $key, $mid)
    {
        try {
            require_once UTIL . 'encdec_paytm2.php';
            $paytmParams = array();
            $paytmParams["body"] = array(
                "mid" => $mid,
                "orderId" => $transaction_id,
            );
            $checksum = getChecksumFromString(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $key);
            $paytmParams["head"] = array(
                "signature" => $checksum
            );
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            $url = "https://securegw.paytm.in/merchant-status/api/v1/getPaymentStatus";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                print "Failed while get paytm api response Transaction_id: " . $transaction_id;

                SwipezLogger::error(__CLASS__, '[E34]Exception occured while get paytm api response Transaction_id: ' . $transaction_id);
            }
            SwipezLogger::debug(__CLASS__, 'Paytm Response:' . $result);
            $rows = json_decode($result, true);
            $row = $rows['body'];
            $row['STATUS'] = $rows['body']['resultInfo']['resultStatus'];
            $row['RESPMSG'] = $rows['body']['resultInfo']['resultMsg'];
            $row['resultCode'] = $rows['body']['resultInfo']['resultCode'];
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Failed while get paytm api response";

            SwipezLogger::error(__CLASS__, '[E35]Exception occured while get paytm api response Transaction_id: ' . $transaction_id . ' Error: ' . $ex->getMessage());
        }
    }

    public function getCashfreeResponse($transaction_id, $account_id, $secret_key)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.cashfree.com/api/v1/order/info/status",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "appId=$account_id&secretKey=$secret_key&orderId=$transaction_id",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            //$response = '{"orderStatus":"PAID","txStatus":"SUCCESS","txTime":"2018-11-05 21:16:34","txMsg":"Transaction Successful","referenceId":"5656602","paymentMode":"UPI","orderCurrency":"INR","status":"OK"}';
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                SwipezLogger::debug(__CLASS__, 'Cashfree Response:' . trim($response));
                $row = json_decode($response, true);
                if ($row['txStatus'] == 'SUCCESS') {
                    $row['STATUS'] = 'TXN_SUCCESS';
                } else if ($row['txStatus'] == 'FAILED') {
                    $row['STATUS'] = 'failed';
                } else if ($row['txStatus'] == 'PENDING') {
                    $row['STATUS'] = 'failed';
                } else {
                    $row = array();
                }
                return $row;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Failed while get cashfree api response";

            SwipezLogger::error(__CLASS__, '[E35]Exception occured while get cashfree api response Transaction_id: ' . $transaction_id . ' Error: ' . $ex->getMessage());
        }
    }

    public function getPayuResponse($transaction_id, $merchant_key, $secret_key)
    {
        try {
            $url = 'https://www.payumoney.com/payment/op/getPaymentResponse?merchantKey=' . $merchant_key . '&merchantTransactionIds=' . $transaction_id;
            $data = array();
            $options = array(
                'http' => array(
                    'header' => "Authorization:" . $secret_key,
                    'method' => 'POST',
                    'Authorization' => $secret_key,
                    'content' => http_build_query($data)
                ),
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === FALSE) {
                print "Failed while get payu api response Transaction_id: " . $transaction_id;

                SwipezLogger::error(__CLASS__, '[E36]Exception occured while get payu api response Transaction_id: ' . $transaction_id);
            }
            SwipezLogger::debug(__CLASS__, 'Payu Response:' . $result);
            $rows = json_decode($result, true);
            $row = $rows['result'][0]['postBackParam'];
            if ($row['status'] == 'success') {
                $row['STATUS'] = 'TXN_SUCCESS';
            } else {
                $row['STATUS'] = 'failed';

                SwipezLogger::error(__CLASS__, '[E37]Response from PG Transaction_id: ' . $transaction_id . ' Response: ' . $result);
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Failed while get payu api response";

            SwipezLogger::error(__CLASS__, '[E37]Exception occured while get payu api response Transaction_id: ' . $transaction_id . ' Error: ' . $ex->getMessage());
        }
    }

    public function getPayoneerResponse($pg_details, $transaction_id)
    {
        $api_url = $pg_details['pg_val5'] . '/v2/programs/' . $pg_details['pg_val2'] . '/payment-requests/' . $transaction_id . '/status';
        $secret = base64_encode($pg_details['pg_val6'] . ':' . $pg_details['pg_val7']);
        $header = array(
            "Authorization: Basic " . $secret,
            "Content-Type: application/json"
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER =>  $header,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = '{"audit_id":4769572780,"code":0,"description":"Success","payment_request_status":"Payment Paid","payment":{"currency":"USD","total_amount_charged":92.45,"fee_amount":2.69,"fee_paid_by":"Buyer","payment_method":"Credit card"}}';
        $response = json_decode($response, 1);
        return $response;
    }

    public function getAtomResponse($transaction_id, $MID, $date, $amount)
    {
        try {
            $url = "https://payment.atomtech.in/paynetz/vfts?merchantid=" . $MID . "&merchanttxnid=" . $transaction_id . "&amt=" . $amount . "&tdate=" . $date;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_PORT, 443);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            $data = curl_exec($ch);
            curl_close($ch);
            $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            SwipezLogger::debug(__CLASS__, 'Atom Response:' . $json);
            $array = json_decode($json, true);
            $row = $array['@attributes'];
            if ($row['VERIFIED'] == 'SUCCESS') {
                $row['STATUS'] = 'TXN_SUCCESS';
            } else {
                $row['STATUS'] = 'Failed';
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Failed while get atom api response";

            SwipezLogger::error(__CLASS__, '[E38]Exception occured while get atom api response Transaction_id: ' . $transaction_id . ' Error: ' . $ex->getMessage());
        }
    }

    public function getRazorpayResponse($order_id, $val1, $val2, $account_id)
    {
        try {
            $api = new Api($val1, $val2);
            if ($account_id != '') {
                $api->setHeader('x-razorpay-account', $account_id);
            }
            $payment = $api->order->fetch($order_id)->payments($order_id);
            SwipezLogger::info(__CLASS__, 'Razorpay payment response for ' . $order_id . ' ' . json_encode($payment));
            $success = 0;
            $success_row = array();
            foreach ($payment->items as $row) {
                if ($row->status == 'captured') {
                    $success = 1;
                    $success_row = $row;
                    break;
                } elseif ($row->status == 'authorized') {
                    $payment = $api->payment->fetch($row->id);
                    $row = $payment->capture(array('amount' => $row->amount, 'currency' => 'INR'));
                    $success = 1;
                    $success_row = $row;
                } elseif ($row->status == 'failed') {
                    if ($success == 0) {
                        $success_row = $row;
                    }
                }
            }
            return $success_row;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Failed while get atom api response";

            SwipezLogger::error(__CLASS__, '[E38]Exception occured while get atom api response Transaction_id: ' . $transaction_id . ' Error: ' . $ex->getMessage());
        }
    }

    public function getStripeResponse($order_id, $val1, $val2, $account_id)
    {
        // dd($order_id);
        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );
            $payment = $stripe->paymentIntents->retrieve(
                $order_id,
                []
            );
            // dd($payment);
            // $payment = $api->order->fetch($order_id)->payments($order_id);
            SwipezLogger::info(__CLASS__, 'Stripe payment response for ' . $order_id . ' ' . json_encode($payment));
            $success = 0;
            $success_row = array();
            if ($payment->status == 'succeeded') {
                // dd($payment->status);
                $success = 1;
                $success_row = $payment;
            } else {
                if ($success == 0) {
                    $success_row = $payment;
                }
            }
            return $success_row;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Failed while get atom api response";

            SwipezLogger::error(__CLASS__, '[E38]Exception occured while get atom api response Transaction_id: ' . $transaction_id . ' Error: ' . $ex->getMessage());
        }
    }

    /**
     * Fetch transaction list data from database
     * 
     * @return type
     */
    public function fetchSwipezDataFromDB()
    {
        try {
            if ($this->transactionIds != NULL) {
                $sql = "select pay_transaction_id,amount,payment_transaction_status,pg_ref_no,t.created_by,p.pg_type,p.pg_val1,p.pg_val2,p.pg_val4,p.pg_val5,p.pg_val6,p.pg_val7,t.created_date,t.amount from payment_transaction t inner join payment_gateway p on t.pg_id=p.pg_id where payment_transaction_status<>1 and pay_transaction_id in(" . $this->transactionIds . ');';
            } else {
                if ($this->fromDate != NULL) {
                } else {
                    $this->fromDate = date('Y-m-d H:i:00', strtotime("-30 minutes"));
                    $this->toDate = date('Y-m-d H:i:00', strtotime("-15 minutes"));
                }
                $from_date = "'" . $this->fromDate . "'";
                $to_date = "'" . $this->toDate . "'";
                $sql = "select pay_transaction_id,payment_transaction_status,pg_ref_no,t.created_by,p.pg_type,p.pg_val1,p.pg_val2,p.pg_val4,p.pg_val5,p.pg_val6,p.pg_val7,t.created_date,t.amount from payment_transaction t inner join payment_gateway p on t.pg_id=p.pg_id where payment_transaction_status<>1 and t.created_date> " . $from_date . " and t.created_date<" . $to_date . ";";
            }

            $params = array();
            $this->db->exec($sql, $params);
            $resultSet = $this->db->resultset();
            return $resultSet;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Fetching data failed from swipez db ";

            SwipezLogger::error(__CLASS__, '[E39]Fetching data failed from swipez db sql: ' . $sql . $ex->getMessage());
        }
    }

    public function fetchXwayDataFromDB()
    {
        try {
            if ($this->transactionIds != NULL) {
                $sql = "select xway_transaction_id as pay_transaction_id,amount,xway_transaction_status as payment_transaction_status,pg_ref_no1 as pg_ref_no,merchant_id as created_by,p.pg_type,p.pg_val1,p.pg_val2,p.pg_val4,p.pg_val5,p.pg_val6,p.pg_val7,t.created_date,t.amount from xway_transaction t inner join payment_gateway p on t.pg_id=p.pg_id where xway_transaction_status<>1 and xway_transaction_id in(" . $this->transactionIds . ');';
            } else {
                if ($this->fromDate != NULL) {
                } else {
                    $this->fromDate = date('Y-m-d H:i:00', strtotime("-30 minutes"));
                    $this->toDate = date('Y-m-d H:i:00', strtotime("-15 minutes"));
                }
                $from_date = "'" . $this->fromDate . "'";
                $to_date = "'" . $this->toDate . "'";
                $sql = "select xway_transaction_id as pay_transaction_id,xway_transaction_status as payment_transaction_status,pg_ref_no1 as pg_ref_no,merchant_id as created_by,p.pg_type,p.pg_val1,p.pg_val2,p.pg_val4,p.pg_val5,p.pg_val6,p.pg_val7,t.created_date,t.amount from xway_transaction t inner join payment_gateway p on t.pg_id=p.pg_id where xway_transaction_status<>1 and t.created_date> " . $from_date . " and t.created_date<" . $to_date . ";";
            }
            $params = array();
            $this->db->exec($sql, $params);
            $resultSet = $this->db->resultset();
            return $resultSet;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Fetching data failed from xway db ";

            SwipezLogger::error(__CLASS__, '[E40]Fetching data failed from xway db sql: ' . $sql . $ex->getMessage());
        }
    }

    public function fetchPackageDataFromDB()
    {
        try {
            if ($this->transactionIds != NULL) {
                $sql = "select package_transaction_id as pay_transaction_id,amount,payment_transaction_status as payment_transaction_status,pg_ref_no,merchant_id as created_by,p.pg_type,p.pg_val1,p.pg_val2,p.pg_val7,t.created_date,t.amount from package_transaction t inner join payment_gateway p on t.pg_id=p.pg_id where payment_transaction_status<>1 and package_transaction_id in(" . $this->transactionIds . ');';
            } else {
                if ($this->fromDate != NULL) {
                    $from_date = "'" . $this->fromDate . "'";
                    $to_date = "'" . $this->toDate . "'";
                } else {
                    $from_date = "'" . date('Y-m-d H:i:00', strtotime("-30 minutes")) . "'";
                    $to_date = "'" . date('Y-m-d H:i:00', strtotime("-15 minutes")) . "'";
                    $this->fromDate = $from_date;
                    $this->toDate = $to_date;
                }
                $sql = "select package_transaction_id as pay_transaction_id,payment_transaction_status as payment_transaction_status,pg_ref_no,merchant_id as created_by,p.pg_type,p.pg_val1,p.pg_val2,p.pg_val7,t.created_date,t.amount from package_transaction t inner join payment_gateway p on t.pg_id=p.pg_id where payment_transaction_status<>1 and t.created_date> " . $from_date . " and t.created_date<" . $to_date . ";";
            }
            $params = array();
            $this->db->exec($sql, $params);
            $resultSet = $this->db->resultset();
            return $resultSet;
        } catch (Exception $e) {
            Sentry\captureException($e);
            print "Fetching data failed from package transaction db ";

            SwipezLogger::error(__CLASS__, '[E40]Fetching data failed from package transaction db sql: ' . $sql . $ex->getMessage());
        }
    }

    function existRetBank($column, $table, $value)
    {
        $sql = "select " . $column . " from " . $table . " where " . $column . "=:id";
        $params = array(':id' => $value);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        if (empty($row)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function saveRazorpayResponse($data, $transaction_id)
    {
        try {
            $datetime = date('Y-m-d H:i:s', $data->created_at);
            $amount = $data->amount / 100;
            $exist = $this->existRetBank('payment_id', 'pg_ret_bank9', $data->id);
            if ($exist == false) {
                $sql = "INSERT INTO `pg_ret_bank9`(`payment_id`,`entity`,`currency`,`status`,`order_id`,`amount`,`card_id`,`bank`,`wallet`,`vpa`,`email`,`contact`,`fee`,`tax`,`method`,`error_description`,`txTime`,`error_code`,`created_date`)
VALUES(:payment_id,:entity,:currency,:status,:order_id,:amount,:card_id,:bank,:wallet,:vpa,:email,:contact,:fee,:tax,:method,:error_description,:txTime,:error_code,:created_date);";
                $params = array(
                    ':payment_id' => $data->id, ':entity' => $data->entity, ':currency' => $data->currency, ':status' => $data->status,
                    ':order_id' => $data->order_id, ':amount' => $amount, ':card_id' => $data->card_id, ':bank' => $data->bank, ':wallet' => $data->wallet, ':vpa' => $data->vpa, ':email' => $data->email,
                    ':contact' => $data->contact, ':fee' => $data->fee, ':tax' => $data->tax, ':method' => $data->method, ':error_description' => $data->error_description, ':error_code' => $data->error_code, ':txTime' => $datetime, ':created_date' => $datetime
                );
            } else {
                $sql = "update pg_ret_bank9 set `status`=:status where payment_id=:id";
                $params = array(':status' => $data->status, ':id' => $data->id);
            }
            $this->db->exec($sql, $params);

            if ($data->status == 'captured' || $data->status == 'authorized') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }

            if (substr($transaction_id, 0, 1) == 'T' || substr($transaction_id, 0, 1) == 'F') {
                $response = $this->savePaymentResponse($response, $datetime, $transaction_id, $data->order_id, $data->id, $amount, 9998, '', $response['status'], '', $data->method);
            } else {
                $response = $this->saveXwayResponse($response, $datetime, $transaction_id, $data->order_id, $data->id, $amount, 9998, '', $response['status'], '', $data->method);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E40+7]Error while Save razorpay retbank details' . $ex->getMessage());
        }
    }

    public function saveStripeResponse($data, $transaction_id)
    {
        try {
            // $datetime = date('Y-m-d H:i:s', $data->created_at);
            $datetime = date('Y-m-d H:i:s');
            $amount = $data->amount / 100;
            $exist = $this->existRetBank('payment_intent_id', 'pg_ret_bank10', $data->id);
            if ($exist == false) {
                $sql = "insert INTO `pg_ret_bank10`(`payment_intent_id`,`stripe_customer_id`,`amount`,`destination`,`currency`,`created_date`,`payment_method`, `stripe_charge_id`, `stripe_transaction_id`, `receipt_url`, `status`)values(:PaymentIntentId,:CustomerId,:Amount,:Destination,:Currency,CURRENT_TIMESTAMP(),:PaymentMethod,:ChargeId,:TransactionId,:ReceiptURL,:Status)";
                $params = array(':PaymentIntentId' => $data->id, ':CustomerId' => $data->customer, ':Amount' => $data->amount / 100, ':Destination' => $data->transfer_data->destination, ':Currency' => $data->currency, ':PaymentMethod' => $data->charges->data[0]->payment_method_details->type, ':ChargeId' => $data->charges->data[0]->id, ':TransactionId' => $data->charges->data[0]->transfer, ':ReceiptURL' => $data->charges->data[0]->receipt_url, ':Status' => $data->status);
            } else {
                $sql = "update pg_ret_bank10 set `status` =:status where payment_intent_id=:id";
                $params = array(':status' => $data->status, ':id' => $data->id);
            }
            $this->db->exec($sql, $params);
            // dd('done');

            if ($data->status == 'captured' || $data->status == 'succeeded') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }
            if (substr($transaction_id, 0, 1) == 'T' || substr($transaction_id, 0, 1) == 'F') {
                $response = $this->savePaymentResponse($response, $datetime, $transaction_id, $data->id, $data->id, $amount, 9998, '', $response['status'], '', $data->charges->data[0]->payment_method_details->type);
            } else {
                $response = $this->saveXwayResponse($response, $datetime, $transaction_id, $data->id, $data->id, $amount, 9998, '', $response['status'], '', $data->charges->data[0]->payment_method_details->type);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E40+7]Error while Save stripe retbank details' . $e->getMessage());
        }
    }

    public function savePayoneerResponse($data, $transaction_id)
    {
        try {
            // $datetime = date('Y-m-d H:i:s', $data->created_at);
            $datetime = date('Y-m-d H:i:s');

            $status = $data['payment_request_status'];
            // dd('done');
            $amount = $data['payment']['total_amount_charged'];
            if ($status == 'Payment Under Review' || $status == 'Payment Approved' || $status == 'Payment Paid') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }
            if (substr($transaction_id, 0, 1) == 'T' || substr($transaction_id, 0, 1) == 'F') {
                $response = $this->savePaymentResponse($response, $datetime, $transaction_id, $transaction_id, $transaction_id, $amount, 9998, '', $response['status'], '', $data['payment']['payment_method']);
            } else {
                $response = $this->saveXwayResponse($response, $datetime, $transaction_id, $transaction_id, $transaction_id, $amount, 9998, '', $response['status'], '', $data['payment']['payment_method']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E40+7]Error while Save stripe retbank details' . $e->getMessage());
        }
    }

    public function saveAtomResponse($response, $user_id)
    {
        try {
            $exist = $this->existRetBank('mmp_txn', 'pg_ret_bank5', $response['atomtxnId']);
            if ($exist == false) {
                $sql = "INSERT INTO `pg_ret_bank5`(`mmp_txn`,`mer_txn`,`amt`,`prod`,`date`,`bank_txn`,`f_code`,`clientcode`,`bank_name`,`auth_code`,`ipg_txn_id`,`merchant_id`,`desc`,`udf9`,`discriminator`,`surcharge`,`CardNumber`,`udf1`,`udf2`,`udf3`,`udf4`,`udf5`,`udf6`,`created_date`,`created_by`)
                VALUES(:mmp_txn,:mer_txn,:amt,:prod,now(),:bank_txn,:f_code,:clientcode,:bank_name,:auth_code,:ipg_txn_id,:merchant_id,:desc,:udf9,:discriminator,:surcharge,:CardNumber,:udf1,:udf2,:udf3,:udf4,:udf5,:udf6,now(),:user_id);";
                $params = array(
                    ':mmp_txn' => $response['atomtxnId'], ':mer_txn' => $response['MerchantTxnID'], ':amt' => $response['AMT'],
                    ':prod' => '', ':bank_txn' => $response['BID'], ':f_code' => $response['f_code'], ':clientcode' => '007', ':bank_name' => $response['bankname'],
                    ':auth_code' => '', ':ipg_txn_id' => '', ':merchant_id' => $response['MerchantID'], ':desc' => '', ':udf9' => '',
                    ':discriminator' => $response['discriminator'], ':surcharge' => $response['surcharge'], ':CardNumber' => '', ':udf1' => '', ':udf2' => '',
                    ':udf3' => '', ':udf4' => '', ':udf5' => '', ':udf6' => '', ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank5 set `f_code`=:f_code,`desc`='Reconcile Status changed' where mmp_txn=:mmp_txn";
                $params = array(':mmp_txn' => $response['atomtxnId'], ':f_code' => $response['f_code']);
            }
            $this->db->exec($sql, $params);
            $response['payment_mode'] = $response['discriminator'];

            if ($response['f_code'] == 'Ok') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }
            if (substr($response['MerchantTxnID'], 0, 1) == 'T' || substr($response['MerchantTxnID'], 0, 1) == 'F') {
                $response = $this->savePaymentResponse($response, $response['TxnDate'], $response['MerchantTxnID'], $response['atomtxnId'], $response['BID'], $response['AMT'], 9996, '', $response['status'], $user_id, $response['discriminator']);
            } else {
                $response = $this->saveXwayResponse($response, $response['TxnDate'], $response['MerchantTxnID'], $response['atomtxnId'], $response['BID'], $response['AMT'], 9996, '', $response['status'], $user_id, $response['discriminator']);
            }

            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E41]Error while saving ATOM request response from ATOM Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function savePayuResponse($response, $user_id)
    {
        try {
            $exist = $this->existRetBank('payuMoneyId', 'pg_ret_bank4', $response['payuMoneyId']);
            if ($exist == false) {
                $sql = "INSERT INTO `pg_ret_bank4` (`payuMoneyId`,`mihpayid`,`mode`,`status`,`unmappedstatus`,`key`,`txnid`,`amount`,`discount`,`net_amount_debit`,`addedon`,`productinfo`,`firstname`,
`lastname`,`address1`,`address2`,`city`,`state`,`country`,`zipcode`,`email`,`phone`,`udf1`,`udf2`,`udf3`,`udf4`,`udf5`,`udf6`,`udf7`,`udf8`,`udf9`,`udf10`,`hash`,`field1`,
`field2`,`field5`,`field6`,`field7`,`field8`,`field9`,`PG_TYPE`,`encryptedPaymentId`,`bank_ref_num`,`bankcode`,`error`,`error_Message`,`cardToken`,`name_on_card`,`cardnum`,
`cardhash`,`card_merchant_param`,`amount_split`,`created_date`,`created_by`)VALUES(:payuMoneyId,:mihpayid,:mode,:status,:unmappedstatus,:key,:txnid,:amount,:discount
,:net_amount_debit,:addedon,:productinfo,:firstname,:lastname,:address1,:address2,:city,:state,:country,:zipcode,:email,:phone,:udf1,:udf2,:udf3,:udf4,:udf5,:udf6,:udf7,:udf8,:udf9
,:udf10,:hash,:field1,:field2,:field5,:field6,:field7,:field8,:field9,:PG_TYPE,:encryptedPaymentId,:bank_ref_num,:bankcode,:error,:error_Message,:cardToken,:name_on_card,:cardnum,:cardhash,:card_merchant_param,:amount_split,CURRENT_TIMESTAMP(),:user_id)";

                $params = array(
                    ':payuMoneyId' => $response['payuMoneyId'], ':mihpayid' => $response['mihpayid'], ':mode' => $response['mode'], ':status' => $response['status'], ':unmappedstatus' => $response['unmappedstatus'], ':key' => $response['key'], ':txnid' => $response['txnid'],
                    ':amount' => $response['amount'], ':discount' => $response['discount'], ':net_amount_debit' => $response['net_amount_debit'], ':addedon' => $response['addedon'], ':productinfo' => $response['productinfo'], ':firstname' => $response['firstname'],
                    ':lastname' => $response['lastname'], ':address1' => $response['address1'], ':address2' => $response['address2'], ':city' => $response['city'], ':state' => $response['state'],
                    ':country' => $response['country'], ':zipcode' => $response['zipcode'], ':email' => $response['email'], ':phone' => $response['phone'], ':udf1' => $response['udf1'],
                    ':udf2' => $response['udf2'], ':udf3' => $response['udf3'], ':udf4' => $response['udf4'], ':udf5' => $response['udf5'], ':udf6' => $response['udf6'], ':udf7' => $response['udf7'],
                    ':udf8' => $response['udf8'], ':udf9' => $response['udf9'], ':udf10' => $response['udf10'], ':hash' => $response['hash'], ':field1' => $response['field1'], ':field2' => $response['field2'], ':field5' => $response['field5'], ':field6' => $response['field6'], ':field7' => $response['field7'], ':field8' => $response['field8'], ':field9' => $response['field9'],
                    ':PG_TYPE' => $response['pg_TYPE'], ':encryptedPaymentId' => $response['encryptedPaymentId'], ':bank_ref_num' => $response['bank_ref_num'], ':bankcode' => $response['bankcode'],
                    ':error' => $response['error'], ':error_Message' => $response['error_Message'], ':cardToken' => $response['cardToken'], ':name_on_card' => $response['name_on_card'],
                    ':cardnum' => $response['cardnum'], ':cardhash' => $response['cardhash'], ':card_merchant_param' => $response['card_merchant_param'], ':amount_split' => $response['amount_split'], ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank4 set `status`=:status,field9=:field9,error_Message=:error_Message where payuMoneyId=:payuMoneyId";
                $params = array(':payuMoneyId' => $response['payuMoneyId'], ':status' => $response['status'], ':field9' => $response['field9'], ':error_Message' => $response['error_Message']);
            }

            $this->db->exec($sql, $params);
            if ($response['status'] == 'success') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }
            if (substr($response['txnid'], 0, 1) == 'T' || substr($response['txnid'], 0, 1) == 'F') {
                $response = $this->savePaymentResponse($response, $response['addedon'], $response['txnid'], $response['payuMoneyId'], $response['mihpayid'], $response['amount'], 9992, $response['error_Message'], $response['status'], $user_id, $response['mode']);
            } else {
                $response = $this->saveXwayResponse($response, $response['addedon'], $response['txnid'], $response['payuMoneyId'], $response['mihpayid'], $response['amount'], 9992, $response['error_Message'], $response['status'], $user_id, $response['mode']);
            }
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E42]Error while saving PAYU request response from EBS Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function savePaytmResponse($response, $user_id)
    {
        try {
            $exist = $this->existRetBank('MID', 'pg_ret_bank3', $response['mid']);
            if ($exist == FALSE) {
                $sql = "insert INTO `pg_ret_bank3`(`MID`,`ORDERID`,`TXNAMOUNT`,`CURRENCY`,`TXNID`,`BANKTXNID`,`STATUS`,`RESPCODE`,`RESPMSG`,`TXNDATE`,`GATEWAYNAME`,`BANKNAME`,`PAYMENTMODE`,`CHECKSUMHASH`,`payment_method`,created_by,created_date)VALUES
                (:MID,:ORDERID,:TXNAMOUNT,:CURRENCY,:TXNID, :BANKTXNID,:STATUS,:RESPCODE,:RESPMSG,:TXNDATE,:GATEWAYNAME,:BANKNAME, :PAYMENTMODE,:CHECKSUMHASH,:payment_method,:user_id,CURRENT_TIMESTAMP())";
                $params = array(
                    ':MID' => $response['mid'], ':ORDERID' => $response['orderId'], ':TXNAMOUNT' => $response['txnAmount'], ':CURRENCY' => '',
                    ':TXNID' => $response['txnId'], ':BANKTXNID' => $response['bankTxnId'], ':STATUS' => $response['STATUS'], ':RESPCODE' => $response['resultCode'], ':RESPMSG' => $response['RESPMSG'], ':TXNDATE' => $response['txnDate'], ':GATEWAYNAME' => $response['gatewayName'], ':BANKNAME' => $response['bankName'], ':PAYMENTMODE' => $response['paymentMode'], ':CHECKSUMHASH' => '', ':payment_method' => 9999, ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank3 set BANKTXNID=:BANKTXNID,STATUS=:STATUS,RESPCODE=:RESPCODE,RESPMSG=:RESPMSG where MID=:MID";
                $params = array(':MID' => $response['mid'], ':BANKTXNID' => $response['bankTxnId'], ':STATUS' => $response['STATUS'], ':RESPCODE' => $response['resultCode'], ':RESPMSG' => $response['RESPMSG']);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E43]Error while saving Paytm response Error: for json[' . json_encode($params) . ']' . $e->getMessage());
        }

        if ($response['STATUS'] == 'TXN_SUCCESS') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        if (substr($response['orderId'], 0, 1) == 'T' || substr($response['orderId'], 0, 1) == 'F') {
            $response = $this->savePaymentResponse($response, $response['txnDate'], $response['orderId'], $response['txnId'], $response['bankTxnId'], $response['txnAmount'], 9999, $response['RESPMSG'], $response['status'], $user_id, 'Paytm');
        } else {
            $response = $this->saveXwayResponse($response, $response['txnDate'], $response['orderId'], $response['txnId'], $response['bankTxnId'], $response['txnAmount'], 9999, $response['RESPMSG'], $response['status'], $user_id, 'Paytm');
        }
        return $response;
    }

    public function savePaypalResponse($response, $user_id)
    {
        try {
            $response['paymentMode'] = 'Paypal';
            $exist = $this->existRetBank('orderId', 'pg_ret_bank8', $response['orderId']);
            if ($exist == FALSE) {
                $sql = "INSERT INTO `pg_ret_bank8`(`orderId`,`orderAmount`,`referenceId`,`txStatus`,`paymentMode`,`txTime`,`created_date`,`created_by`)
VALUES(:orderId,:orderAmount,:referenceId,:txStatus,:paymentMode,:txMsg,:txTime,CURRENT_TIMESTAMP(),:user_id);";
                $params = array(
                    ':orderId' => $response['orderId'], ':orderAmount' => $response['orderAmount'], ':referenceId' => $response['referenceId'], ':txStatus' => $response['txStatus'], ':paymentMode' => $response['paymentMode'], ':txTime' => $response['txTime'], ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank8 set txStatus=:txStatus,paymentMode=:paymentMode where orderId=:orderId";
                $params = array(':txStatus' => $response['txStatus'], ':paymentMode' => $response['paymentMode'], ':orderId' => $response['orderId']);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E43+65]Error while saving paypal response Error: for json[' . json_encode($params) . ']' . $e->getMessage());
        }

        if ($response['txStatus'] == 'SUCCESS') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        if (substr($response['orderId'], 0, 1) == 'T' || substr($response['orderId'], 0, 1) == 'F') {
            $response = $this->savePaymentResponse($response, $response['txTime'], $response['orderId'], $response['referenceId'], $response['referenceId'], $response['orderAmount'], 9991, $response['txMsg'], $response['status'], $user_id);
        } else {
            $response = $this->saveXwayResponse($response, $response['txTime'], $response['orderId'], $response['referenceId'], $response['referenceId'], $response['orderAmount'], 9991, $response['txMsg'], $response['status'], $user_id);
        }
        return $response;
    }

    public function saveCashfreeResponse($response, $user_id)
    {
        try {
            $exist = $this->existRetBank('orderId', 'pg_ret_bank7', $response['orderId']);
            if ($exist == FALSE) {
                $sql = "INSERT INTO `pg_ret_bank7`(`orderId`,`orderAmount`,`referenceId`,`txStatus`,`paymentMode`,`txMsg`,`txTime`,`signature`,`email`,`created_date`,`created_by`)
VALUES(:orderId,:orderAmount,:referenceId,:txStatus,:paymentMode,:txMsg,:txTime,:signature,:email,CURRENT_TIMESTAMP(),:user_id);";
                $params = array(
                    ':orderId' => $response['orderId'], ':orderAmount' => $response['orderAmount'], ':referenceId' => $response['referenceId'], ':txStatus' => $response['txStatus'], ':paymentMode' => $response['paymentMode'], ':txMsg' => $response['txMsg'], ':txTime' => $response['txTime'], ':signature' => '', ':email' => '', ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank7 set txStatus=:txStatus,paymentMode=:paymentMode,txMsg=:txMsg where orderId=:orderId";
                $params = array(':txStatus' => $response['txStatus'], ':paymentMode' => $response['paymentMode'], ':txMsg' => $response['txMsg'], ':orderId' => $response['orderId']);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E43+65]Error while saving Cashfree response Error: for json[' . json_encode($params) . ']' . $e->getMessage());
        }

        if ($response['txStatus'] == 'SUCCESS') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        if (substr($response['orderId'], 0, 1) == 'T' || substr($response['orderId'], 0, 1) == 'F') {
            $response = $this->savePaymentResponse($response, $response['txTime'], $response['orderId'], $response['referenceId'], $response['referenceId'], $response['orderAmount'], 9998, $response['txMsg'], $response['status'], $user_id, $response['paymentMode']);
        } else {
            $response = $this->saveXwayResponse($response, $response['txTime'], $response['orderId'], $response['referenceId'], $response['referenceId'], $response['orderAmount'], 9998, $response['txMsg'], $response['status'], $user_id, $response['paymentMode']);
        }
        return $response;
    }

    public function saveSetuResponse($response, $user_id)
    {
        try {
            $exist = $this->existRetBank('billerBillID', 'pg_ret_bank11', $response['orderId']);
            if ($exist == FALSE) {
                $sql = "INSERT INTO `pg_ret_bank11`(`orderId`,`orderAmount`,`referenceId`,`txStatus`,`paymentMode`,`txMsg`,`txTime`,`signature`,`email`,`created_date`,`created_by`)
VALUES(:orderId,:orderAmount,:referenceId,:txStatus,:paymentMode,:txMsg,:txTime,:signature,:email,CURRENT_TIMESTAMP(),:user_id);";
                $params = array(
                    ':orderId' => $response['orderId'], ':orderAmount' => $response['orderAmount'], ':referenceId' => $response['referenceId'],
                    ':txStatus' => $response['txStatus'], ':paymentMode' => $response['paymentMode'],
                    ':txMsg' => $response['txMsg'], ':txTime' => $response['txTime'], ':signature' => '', ':email' => '', ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank11 set status=:txStatus,paymentMode=:paymentMode,txMsg=:txMsg where orderId=:orderId";
                $params = array(
                    ':txStatus' => $response['STATUS'], ':paymentMode' => 'UPI', ':txMsg' => $response['data']['transactionNote'],
                    ':orderId' => $response['orderId']
                );
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E43+65]Error while saving Cashfree response Error: for json[' . json_encode($params) . ']' . $e->getMessage());
        }

        if ($response['data']['status'] == 'PAYMENT_SUCCESSFUL') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        if (substr($response['orderId'], 0, 1) == 'T' || substr($response['orderId'], 0, 1) == 'F') {
            $response = $this->savePaymentResponse(
                $response,
                $response['data']['createdAt'],
                $response['orderId'],
                $response['data']['platformBillID'],
                $response['data']['platformBillID'],
                $response['orderAmount'],
                9998,
                $response['data']['transactionNote'],
                $response['status'],
                $user_id,
                'UPI'
            );
        } else {
            $response = $this->saveXwayResponse(
                $response,
                $response['data']['createdAt'],
                $response['orderId'],
                $response['data']['platformBillID'],
                $response['data']['platformBillID'],
                $response['orderAmount'],
                9998,
                $response['data']['transactionNote'],
                $response['status'],
                $user_id,
                'UPI'
            );
        }
        return $response;
    }

    public function savePaymentResponse($response, $date, $transaction_id, $payment_id, $pg_transaction_id, $amount, $payment_method, $message, $status, $user_id, $discriminator = '')
    {
        try {
            $user_id = ($user_id == null) ? 'Reconciled' : $user_id;
            $type = (substr($transaction_id, 0, 1) == 'F') ? 'package' : 'payment_request';
            $sql = "call insert_payment_response(:type,:transaction_id,:payment_id,:pg_transaction_id,:amount,:payment_method,:payment_mode,:message,:status,:user_id)";
            $params = array(
                ':type' => $type, ':transaction_id' => $transaction_id, ':payment_id' => $payment_id,
                ':pg_transaction_id' => $pg_transaction_id, ':amount' => $amount, ':payment_method' => $payment_method, ':payment_mode' => $discriminator, ':message' => $message, ':status' => $status, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($status == 'success') {
                if ($row['message'] == 'success') {
                    $response['merchant_name'] = $row['company_name'];
                    $response['Amount'] = $amount;
                    $response['merchant_user_id'] = $row['merchant_user_id'];
                    $response['sms_gateway'] = $row['sms_gateway'];
                    $response['suppliers'] = $row['suppliers'];
                    $response['quantity'] = $row['quantity'];
                    $response['payment_request_id'] = $row['payment_request_id'];
                    $response['transaction_id'] = $transaction_id;
                    $response['mobile_no'] = $row['merchant_mobile_no'];
                    $response['merchant_email'] = $row['merchant_email_id'];
                    $response['payment_mode'] = $row['payment_mode'] . ' ' . $discriminator;
                    $response['image'] = $row['image'];
                    $response['merchant_logo'] = $row['merchant_logo'];
                    $response['BillingName'] = $row['patron_name'];
                    $response['BillingEmail'] = $row['patron_email'];
                    $response['patron_name'] = $row['patron_name'];
                    $response['patron_email'] = $row['patron_email'];
                    $response['TransactionID'] = $pg_transaction_id;
                    $response['MerchantRefNo'] = $transaction_id;
                    $response['DateCreated'] = $date;
                    $response['discount'] = $row['discount'];
                    $response['narrative'] = $row['narrative'];
                    $response['customer_code'] = $row['customer_code'];
                    $response['invoice_number'] = $row['invoice_number'];
                    return $response;
                } else {

                    SwipezLogger::error(__CLASS__, '[E45]Error while updating payment response status Transaction_id: ' . $transaction_id . ' error: ' . json_encode($row));
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E46]Error while saving payment request response from PG Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function saveXwayResponse($response, $date, $transaction_id, $payment_id, $pg_transaction_id, $amount, $payment_method, $message, $status, $user_id, $discriminator = '')
    {
        try {
            $sql = "call save_xway_payment_response(:transaction_id,:payment_id,:pg_transaction_id,:amount,:payment_method,:message,:status,:payment_mode)";
            $params = array(':transaction_id' => $transaction_id, ':payment_id' => $payment_id, ':pg_transaction_id' => $pg_transaction_id, ':amount' => $amount, ':payment_method' => $payment_method, ':message' => $message, ':status' => $status, ':payment_mode' => $discriminator);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $row['merchant_name'] = $row['company_name'];
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E47]Error while saving xway response Error: for param [' . json_encode($params) . ']' . $e->getMessage());
            return $response;
        }
    }

    public function validateDate($date)
    {
        if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date)) {
            return 1;
        } else {
            print "Error: Invalid date format $date should be YYYY-MM-DD";
            return "Invalid date format " . $date;
        }
    }

    public function validateTime($time)
    {
        if (preg_match("/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $time)) {
            return 1;
        } else {
            print "Error: Invalid time format $time should be hh-mm-ss";
            return "Invalid time format " . $time;
        }
    }

    function sendNotification($result, $response, $row, $type)
    {
        $sms = new SMSMessage();
        # Successfully reconciled transaction record from Pay u
        SwipezLogger::info(__CLASS__, 'Successfully reconciled transaction record from PG transaction id: ' . $row['pay_transaction_id']);
        if ($this->notify == 1) {
            $sg_details = NULL;
            if ($result['sms_gateway'] > 1) {
                $sg_details = $this->getSMSgateway($result['sms_gateway']);
            }
            #Send sms to merchant 
            $message = $sms->fetch('m7');
            $customer_code = ($result['customer_code'] != '') ? ' (' . $result['customer_code'] . ')' : '';
            $message = str_replace('<Patron Name>', $result['BillingName'] . $customer_code, $message);
            $message = str_replace('xxxx', $result['Amount'], $message);
            $this->sendSMS($result['merchant_user_id'], $message, $result['mobile_no'], $sg_details);
            #Send sms to Customer 
            $message = $sms->fetch('p3');
            $message = str_replace('<Merchant Name>', $result['merchant_name'], $message);
            $message = str_replace('xxxx', $result['Amount'], $message);
            $message = str_replace('<Transaction id>', $result['transaction_id'], $message);
            $this->sendSMS(NULL, $message, $result['billing_mobile'], $sg_details);
            #Send email receipt 
            $app_url = $this->getAppUrl($result['merchant_domain']);
            require_once CONTROLLER . 'Notification.php';
            $notification = new Notification($app_url, 'cron');
            $notification->sendMailReceipt($row['pay_transaction_id']);
        }
    }

    function sendSMS($user_id = null, $message, $mobileNo, $gateway = NULL)
    {
        try {
            if ($mobileNo > 0) {
                $SMSHelper = new SMSSender();
                if (!empty($gateway)) {
                    $SMSHelper->url = $gateway['req_url'];
                    $SMSHelper->val1 = $gateway['sg_val1'];
                    $SMSHelper->val2 = $gateway['sg_val2'];
                    $SMSHelper->val3 = $gateway['sg_val3'];
                }
                SwipezLogger::debug(__CLASS__, 'Sending SMS initiateMobile number is : ' . $mobileNo);
                $responseArr = $SMSHelper->send($message, $mobileNo);
                SwipezLogger::info(__CLASS__, 'Sending SMS Response : ' . $responseArr);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[BE103]Error while send sms Error: ' . $e->getMessage());
        }
    }

    public function sendMailPaymentReceipt($customer_code, $invoice_number, $patron_name, $merchant_email, $patron_email, $payment_towards, $receipt_no, $transaction_no, $payment_date, $payment_amount, $payment_mode, $discount, $logo, $merchant_user_id, $narrative = '')
    {
        try {
            $emailWrapper = new EmailWrapper();
            $mailcontents = @file_get_contents('../util/Email/payment_receipt.php');
            $message = $mailcontents;
            $message = str_replace('__CUSTOMER_CODE__', $customer_code, $message);
            $message = str_replace('__PATRON_NAME__', $patron_name, $message);
            $message = str_replace('__PATRON_EMAIL__', $patron_email, $message);
            $message = str_replace('__PAYMENT_TOWARDS__', $payment_towards, $message);
            $message = str_replace('__RECEIPT_NO__', $receipt_no, $message);
            $message = str_replace('__TRANSACTION_NO__', $transaction_no, $message);
            $message = str_replace('__PAYMENT_DATE__', $payment_date, $message);
            $message = str_replace('__PAYMENT_AMOUNT__', $payment_amount, $message);
            $message = str_replace('__PAYMENT_MODE__', $payment_mode, $message);
            if ($logo != '') {
                $message = str_replace('https://www.swipez.in/images/logo.png', $logo, $message);
            }

            if ($invoice_number != '') {
                $invoice_number = '<table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;"><tr><td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Invoice number</td><td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">' . $invoice_number . '&nbsp;</td></tr></table>';
            }

            $message = str_replace('__INVOICE_NUMBER__', $invoice_number, $message);

            if ($narrative != '') {
                $narrative = '<table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;"><tr><td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Description</td><td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">' . $narrative . '&nbsp;</td></tr></table>';
            }

            if ($discount > 0) {
                $discount_t = '<table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#5b4d4b;font-size: 13px;line-height: 15px;line-height: 25px;"><tr><td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Coupon Discount</td><td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">' . $discount . '/- &nbsp;</td></tr></table>';
                $message = str_replace('__DISCOUNT__', $discount_t . $narrative, $message);
            } else {
                $message = str_replace('__DISCOUNT__', $narrative, $message);
            }

            #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)

            if ($payment_towards != NULL) {
                $emailWrapper->from_name_ = $payment_towards;
            }
            $sub_title = 'Payment receipt from ';
            $subject = $sub_title . $payment_towards;
            $emailWrapper->sendMail($patron_email, "", $subject, $message);
            $subject = $sub_title . $patron_name;
            $message = str_replace('Your', 'Customer', $message);
            $message = str_replace('your', 'customer', $message);
            $emailWrapper->from_name_ = 'Swipez';
            $is_email = 1;
            if (!empty($result)) {
                if ($result['send_email'] == 0) {
                    $is_email = 0;
                }
            }
            if ($is_email == 1) {
                $emailWrapper->sendMail($merchant_email, "", $subject, $message);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E48]Error while sending mail Error: email link to : ' . $toEmail_ . $e->getMessage());
        }
    }

    public function getAppUrl($id)
    {
        try {
            if ($id > 0) {
            } else {
                $id = 1;
            }
            $sql = "select config_value from config where config_type='merchant_domain' and config_key=:id";
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['config_value'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E49]Error while get merchant domain Error: ' . $e->getMessage());
        }
    }

    public function getSMSgateway($id)
    {
        try {
            $sql = "select * from sms_gateway where sg_id=:id";
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E49]Error while SMS gateway result Error: ' . $e->getMessage());
        }
    }

    function getRedirectUrl($json)
    {
        $json_array = json_decode($json, 1);
        $redirect_url = '';
        foreach ($json_array as $row) {
            if ($row['type'] == 'web_redirect') {
                if ($row['value'] != '') {
                    $redirect_url = $row['value'];
                }
            }
        }
        return $redirect_url;
    }

    function easebuzzTransaction($key, $salt, $env, $transaction_id, $amount, $email, $phone)
    {
        try {
            $easebuzzObj = new Easebuzz($key, $salt, $env);
            $postData = array(
                "txnid" => $transaction_id,
                "amount" => $amount,
                "email" => $email,
                "phone" => $phone
            );

            $result = $easebuzzObj->transactionAPI($postData);
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            return null;
        }
    }
}
