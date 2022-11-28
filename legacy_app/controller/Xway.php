<?php

/**
 * Xway controller class to handle  xway merchant request and EBS response
 */

use Razorpay\Api\Api;
use App\Jobs\InvoiceCreationViaXway;
use Log;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\PaymentWrapperController;
use App\Libraries\Easebuzz\Easebuzz;

class Xway extends Controller
{

    private $currency = array('INR', 'USD');

    function __construct()
    {
        parent::__construct();
        SwipezLogger::$path = "../legacy_app/lib/config/log4php_xway_config.xml";
    }

    /**
     * Handle EBS gateways response for xway merchant
     */
    function payuresponse()
    {
        try {
            $pg_type = $this->session->get('transaction_type');
            $pg_transaction_id = $_POST["txnid"];
            SwipezLogger::info(__CLASS__, 'Payu payment response ' . json_encode($_POST));
            $this->view->render('secure/loader');
            if (isset($pg_transaction_id)) {
                $amount = $this->model->validateXwayTransactionResponse($pg_transaction_id, $_POST['amount']);
                $result = $this->model->handlePayuResponse($_POST, $this->session->get('userid'), $pg_type, $pg_transaction_id, $amount);
                $this->handleResponse($result);
            } else {
                SwipezLogger::error(__CLASS__, '[E094]Error while payment gateway response  user id: ' . $this->session->get('userid'));
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function razorpayresponse()
    {
        try {
            $transaction_id = $_POST['transaction_id'];
            SwipezLogger::info(__CLASS__, 'Razorpay payment response ' . json_encode($_POST));
            $this->view->render('secure/loader');
            if (strlen($transaction_id) == 10) {
                $transaction = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id);
                if ($transaction['xway_transaction_status'] == 0 || $transaction['xway_transaction_status'] == 4) {
                    $pg_detail = $this->common->getSingleValue('payment_gateway', 'pg_id', $transaction['pg_id']);
                    $api = new Api($pg_detail['pg_val1'], $pg_detail['pg_val2']);
                    if ($pg_detail['pg_val7'] != '') {
                        $api->setHeader('x-razorpay-account', $pg_detail['pg_val7']);
                    }
                    $payment = $api->payment->fetch($_POST['razorpay_payment_id']);
                    if ($payment->status == 'authorized') {
                        $api->payment->capture(array('amount' => $payment->amount, 'currency' => $payment->currency));
                    }
                    $result = $this->model->saveRazorpayResponse($payment, $transaction_id, $_POST['name']);
                    $result['type'] = 'request';
                    if ($_POST['type'] != 'webhook') {
                        $this->handleResponse($result);
                    }
                }
            } else {
                SwipezLogger::error(__CLASS__, 'Xway razorpay response order id:' . $transaction_id);
                $this->setPaymentFailedError($transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function striperesponse($transaction_id)
    {
        if (strlen($transaction_id) == 10) {
            if (substr($transaction_id, 0, 1) == 'F') {
                $transaction = $this->common->getSingleValue('package_transaction', 'package_transaction_id', $transaction_id);
            } else if (substr($transaction_id, 0, 1) == 'T') {
                $transaction = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $transaction_id);
                $intent = $transaction['pg_ref_no'];
            } else {
                $transaction = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id);
                $transaction['payment_transaction_status'] = $transaction['xway_transaction_status'];
                $intent = $transaction['pg_ref_no1'];
            }
            try {
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );
                $payment_intent = $stripe->paymentIntents->retrieve(
                    $intent,
                    []
                );
                $transaction_id = $payment_intent->metadata->transaction_id;
                if (strlen($transaction_id) == 10) {
                    $transaction = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id);
                    if ($transaction['xway_transaction_status'] == 0 || $transaction['xway_transaction_status'] == 4) {
                        $result = $this->model->saveStripeResponse($payment_intent, $transaction_id, 'kirti deshwal');
                        $result['type'] = 'request';
                        $result['amount'] = $result['amount'] / 100;
                        if ($_POST['type'] != 'webhook') {
                            $this->handleResponse($result);
                        }
                    }
                } else {
                    SwipezLogger::error(__CLASS__, 'Xway Stripe response order id:' . $transaction_id);
                    $this->setPaymentFailedError($transaction_id);
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
                $this->setGenericError();
            }
        } else {
            $this->setPaymentFailedError($transaction_id);
        }
    }

    function razorpaywebhook()
    {
        try {
            $json = file_get_contents('php://input');
            $array = json_decode($json, 1);
            $data = $array['payload']['payment']['entity'];
            $status = $data['status'];
            SwipezLogger::debug(__CLASS__, 'Razorpay Webhook initiated' . $json);
            if ($status == 'authorized' || $status == 'captured') {
                $_POST['razorpay_payment_id'] = $data['id'];
                $_POST['transaction_id'] = $data['notes']['merchant_order_id'];
                $xwaydetails = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $_POST['transaction_id']);
                $this->merchantWebhookApi($json, $xwaydetails['pg_id']);
                if ($xwaydetails['xway_transaction_status'] == 0 && $xwaydetails['return_url'] == '') {
                    $_POST['name'] = $xwaydetails['name'];
                    $_POST['type'] = 'webhook';
                    $this->session->set('transaction_type', 'xway');
                    $this->session->set('transaction_id', $_POST['transaction_id']);
                    $this->razorpayresponse();
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096+54]Error while razorpay webhook Error: ' . $e->getMessage());
        }
    }

    function merchantWebhookApi($json, $pg_id)
    {
        try {
            $webhook_url = $this->common->getRowValue('pg_val9', 'payment_gateway', 'pg_id', $pg_id);
            if ($webhook_url != '') {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $webhook_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $json,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json"
                    )
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while glossaread Webhook Api Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function atomresponse()
    {
        try {
            $pg_type = $this->session->get('transaction_type');
            $pg_transaction_id = $_POST['mer_txn'];
            SwipezLogger::info(__CLASS__, 'Atom payment response ' . json_encode($_POST));
            $this->view->render('secure/loader');
            $row = $this->model->getPaymentGatewayDetails($pg_transaction_id);
            if ($row['pg_val9'] != '' && isset($_POST["signature"])) {
                $this->validateAtomResponse($_POST, $row['pg_val9']);
            }
            $result = $this->model->handleAtomResponse($_POST, $this->session->get('userid'), $pg_type, $pg_transaction_id);
            $this->handleResponse($result);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function validateAtomResponse($responseParams, $respHashKey)
    {
        $str = $responseParams["mmp_txn"] . $responseParams["mer_txn"] . $responseParams["f_code"] . $responseParams["prod"] . $responseParams["discriminator"] . $responseParams["amt"] . $responseParams["bank_txn"];
        $signature = hash_hmac("sha512", $str, $respHashKey, false);
        if ($signature == $responseParams["signature"]) {
            return true;
        } else {
            SwipezLogger::error(__CLASS__, '[E500]Error: ATOM checksum mismatch : ' . json_encode($responseParams));
            $this->setGenericError();
        }
    }

    function paytmresponse()
    {
        try {
            $this->view->render('secure/loader');
            $transaction_id = $_POST['ORDERID'];
            if (strlen($_POST['ORDERID']) == 10) {
                $pg_transaction_id = $_POST['ORDERID'];
                $this->session->remove('paytm_cust_id');
                $this->session->remove('transaction_id');
                $this->set_PaytmDetail(null, $pg_transaction_id);
                require_once(UTIL . "encdec_paytm2.php");
                $paytmChecksum = "";
                $paramList = array();
                $isValidChecksum = FALSE;
                $paramList = $_POST;
                $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
                //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
                $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.
                if ($isValidChecksum === TRUE || $isValidChecksum === "TRUE") {
                    $pg_type = $this->session->get('transaction_type');
                    $patron_name = $this->session->get('patron_name');
                    $email = $this->session->get('email');
                    if ($email != true) {
                        $email = '';
                    }
                    if ($patron_name != true) {
                        $patron_name = '';
                    }
                    $result = $this->model->handlePaytmGatewayResponse($patron_name, $email, $_POST['MID'], $_POST['ORDERID'], $_POST['TXNAMOUNT'], $_POST['CURRENCY'], $_POST['TXNID'], $_POST['BANKTXNID'], $_POST['STATUS'], $_POST['RESPCODE'], $_POST['RESPMSG'], $_POST['TXNDATE'], $_POST['GATEWAYNAME'], $_POST['BANKNAME'], $_POST['PAYMENTMODE'], $_POST['CHECKSUMHASH'], $this->session->get('userid'), $pg_type, $pg_transaction_id);
                    $this->handleResponse($result, $pg_type);
                } else {
                    SwipezLogger::error(__CLASS__, '[E500]Error while Paytm gateway response Error: checksum mismatch : ' . $paytmChecksum);
                    $this->setPaymentFailedError($transaction_id);
                }
            } else {
                SwipezLogger::error(__CLASS__, 'Order id invalid ' . json_encode($_POST));
                $this->setPaymentFailedError($transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510]Error while paytm response Error: ' . $e->getMessage());
            $this->setPaymentFailedError($transaction_id);
        }
    }

    /**
     * handle respose from paytm gateways
     */
    function cashfreeresponse()
    {
        try {
            $this->view->render('secure/loader');
            SwipezLogger::info(__CLASS__, 'Cashfree payment response ' . json_encode($_POST));
            $pg_transaction_id = $_POST["orderId"];
            if (strlen($pg_transaction_id) == 10) {
                $patron_name = $this->session->get('patron_name');
                $this->session->remove('transaction_type');
                $this->session->remove('transaction_id');
                $wrap = new PaymentWrapperController();
                $isValidChecksum = $wrap->validateCashfree($pg_transaction_id, $_POST);
                if ($isValidChecksum === TRUE) {
                    //SwipezLogger::debug(__CLASS__, 'Cashfree checksum match ' . json_encode($_POST));
                } else {
                    SwipezLogger::error(__CLASS__, '[E514]Error while Cashfree gateway response Error: checksum mismatch ' . json_encode($_POST));
                }
                $email = $this->session->get('email');
                $result = $this->model->handleCashfreeGatewayResponse($patron_name, $email, $_POST, $this->session->get('userid'), $pg_type, $pg_transaction_id);
                $this->handleResponse($result);
            } else {
                $this->setPaymentFailedError($pg_transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+5]Error while Cashfree response Error: ' . $e->getMessage());
        }
    }

    function seturesponse()
    {
        try {
            $this->view->render('secure/loader');
            SwipezLogger::info(__CLASS__, 'Cashfree payment response ' . json_encode($_POST));
            $order_hash = $_POST["orderId"];
            $orderAmount = $_POST["orderAmount"];
            $pg_transaction_id = $this->encrypt->decode($order_hash);
            if (strlen($pg_transaction_id) == 10) {
                if (Redis::exists($order_hash)) {
                    $mn = Redis::get($order_hash);
                    $mn = json_decode($mn, 1);
                    $redis_payment_data = $mn["setu_response"];
                } else {
                    Log::error('payment done, but redis key doesnt exists');
                }

                $post_data = [];
                $post_data["orderId"] = $pg_transaction_id;
                $post_data["billerBillID"] = $redis_payment_data["billerBillID"];
                $post_data["amountPaid"] = $orderAmount;
                $post_data["currencyCode"] = $redis_payment_data["amountPaid"]["currencyCode"];
                $post_data["payerVpa"] =  $redis_payment_data["payerVpa"];
                $post_data["platformBillID"] = $redis_payment_data["platformBillID"];
                $post_data["receiptId"] = $redis_payment_data["receiptId"];
                $post_data["transactionNote"] = $redis_payment_data["transactionNote"];
                $post_data["transactionId"] = $redis_payment_data["transactionId"];
                $post_data["status"] = 'SUCCESS';
                $post_data["paymentMode"] = 'UPI';
                $post_data["timeStamp"] = time();
                $post_data["type"] = '';
                $post_data["trans_unique_id"] = '';

                $patron_name = $this->session->get('patron_name');
                $this->session->remove('transaction_type');
                $this->session->remove('transaction_id');
                $pg_type = NULL;
                $email = $this->session->get('email');
                $result = $this->model->handleSetuGatewayResponse($patron_name, $email, $post_data, $this->session->get('userid'), $pg_type, $pg_transaction_id);

                $this->handleResponse($result);
            } else {
                $this->setPaymentFailedError($pg_transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+5]Error while Cashfree response Error: ' . $e->getMessage());
        }
    }

    function paypalresponse($ref_id, $pg_link)
    {
        try {
            $explode = explode('/', $ref_id);
            $ref_id = $explode[0];
            $pg_link = $explode[1];
            $pg_type = $this->session->get('transaction_type');
            $paymentWrapper = new PaymentWrapperController();
            $pg_id = $this->encrypt->decode($pg_link);
            $pg_det = $this->common->getSingleValue('payment_gateway', 'pg_id', $pg_id);
            $key = base64_encode($pg_det['pg_val4'] . ':' . $pg_det['pg_val2']);
            $response = $paymentWrapper->PaypalOrderStatus($ref_id, $key, $pg_det['pg_val3']);
            SwipezLogger::info(__CLASS__, 'Paypal Response ' . json_encode($response));
            $res['ref_id'] = $ref_id;
            $res['status'] = $response['status'];
            $res['transaction_id'] = $response['purchase_units'][0]['reference_id'];
            $res['name'] = $response['payer']['name']['given_name'] . ' ' . $response['payer']['name']['surname'];
            $res['amount'] = $response['purchase_units'][0]['amount']['value'];
            $res['email'] = $response['payer']['email_address'];
            $result = $this->model->handlePaypalResponse($res);
            $this->handleResponse($result, $pg_type);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+56]Error while paypal response Error: ' . $e->getMessage());
        }
    }

    function techprocessresponse()
    {
        try {
            $post_array = explode('|', $_POST['msg']);
            $pg_transaction_id = $this->session->get('transaction_id');
            $pg_transaction_type = $this->session->get('transaction_type');
            $this->set_PaytmDetail($pg_transaction_type, $pg_transaction_id);
            $pg_hash = $post_array[15]; //Sent by pg
            $post_array[15] = PAYTM_MERCHANT_MID;
            $hash = md5(implode('|', $post_array));
            $post_array[15] = $pg_hash;
            if ($hash == $pg_hash) {
                $pg_type = $this->session->get('transaction_type');
                $patron_name = $this->session->get('patron_name');
                $email = $this->session->get('email');
                $this->session->remove('transaction_type');
                $this->session->remove('transaction_id');
                $result = $this->model->handleTechProcessResponse($patron_name, $email, $post_array, $this->session->get('userid'), $pg_type, $pg_transaction_id);
                $this->handleResponse($result, $pg_type);
            } else {
                SwipezLogger::error(__CLASS__, '[E500]Error while Paytm gateway response Error: checksum mismatch : ' . $hash);
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510]Error while paytm response Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function set_PaytmDetail($type, $transaction_id)
    {
        try {
            $row = $this->model->getPaymentGatewayDetails($transaction_id);
            define('PAYTM_ENVIRONMENT', $row['pg_val3']); // PROD
            define('PAYTM_MERCHANT_KEY', $row['pg_val1']); //Change this constant's value with Merchant key downloaded from portal
            define('PAYTM_MERCHANT_MID', $row['pg_val2']); //Change this constant's value with MID (Merchant ID) received from Paytm
            define('PAYTM_MERCHANT_WEBSITE', $row['pg_val4']); //Change this constant's value with Website name received from Paytm
            define('PAYTM_TXN_URL', $row['req_url']);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510-88]Error while set_PaytmDetail Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Handle xway merchant request and redirect information to EBS gateways
     */
    function secure($link = null, $fee_id = null)
    {
        try {
            if ($link != null) {
                $explode = explode('/', $link);
                $link = $explode[0];
                $fee_id = $explode[1];
                $data = file_get_contents('php://input');
                $dataaarray = json_decode($data, 1);
                foreach ($dataaarray as $row) {
                    $_POST[$row['name']] = $row['value'];
                }
                $_POST['payment_mode'] = $fee_id;
            }
            if (empty($_POST)) {
                SwipezLogger::error(__CLASS__, '[x001]Error:Empty post');
                $hasErrors['Payment failed'][0] = 'Invalid link';
                $hasErrors['Payment failed'][1] = 'You have used Back/Forward/Refresh button of your Browser. ';
            } else {
                $is_valid_request = 1;
                $_POST['merchant_id'] = $_POST['account_id'];
                $merchant_id = substr($_POST['account_id'], 0, 10);
                $franchise_id = substr($_POST['account_id'], 10);
                $_POST['account_id'] = $merchant_id;
                $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
                $vendor_id = 0;
                if ($_POST['vendor_id'] > 0) {
                    $vmerchant_id = $this->common->getRowValue('merchant_id', 'vendor', 'vendor_id', $_POST['vendor_id'], 1);
                    if ($merchant_id == $vmerchant_id) {
                        $vendor_id = $_POST['vendor_id'];
                    }
                }
                $_POST['franchise_id'] = $franchise_id;
                SwipezLogger::info(__CLASS__, '[x002]x-way request :' . json_encode($_POST));

                #validate template_id in create_invoice_api request is correct and belongs to merchant id
                if (isset($_POST['invoice_api_request']) && !empty($_POST['invoice_api_request'])) {
                    $create_invoice_api = 1;
                    $invoice_api_request = json_decode($_POST['invoice_api_request'], true);
                    $template_id = $this->encrypt->decode($invoice_api_request['template_id']);
                    if ($template_id != '') {
                        $find_merchant_id = $this->common->getRowValue('merchant_id', 'invoice_template', 'template_id', $template_id, 1);
                        if ($merchant_id != $find_merchant_id) {
                            $is_valid_request = 0;
                            $hasErrors['Payment failed'][0] = 'Invalid template';
                            $hasErrors['Payment failed'][1] = 'Invalid template for the Merchant';
                        }
                    }
                } else {
                    $create_invoice_api = 0;
                }
                if ($is_valid_request) {
                    #validate Secure hash
                    $hasErrors = $this->validateSecureHash($_POST);
                    if (!$hasErrors) {
                        $_POST['email'] = trim($_POST['email']);
                        $_POST['phone'] = trim($_POST['phone']);
                        $_POST['postal_code'] = trim($_POST['postal_code']);
                        require CONTROLLER . 'Securevalidator.php';
                        $validator = new Securevalidator(Null);
                        $validator->validatexway($merchant_id);
                        $hasErrors = $validator->fetchErrors();
                    } else {
                        SwipezLogger::error(__CLASS__, '[x003]Error: Invalid secure hash');
                    }

                    if (!$hasErrors) {
                        $merchant_id = $_POST['account_id'];
                        $surcharge_enable = 0;
                        if (isset($_POST['payment_mode'])) {
                            $fee_id = $this->encrypt->decode($_POST['payment_mode']);

                            $details = $this->model->getXwayDeatails($fee_id);
                            if ($details['surcharge_enable'] == 1 && $details['pg_surcharge_enabled'] == 0) {
                                $surcharge_enable = 1;
                            }
                            $_POST['pg_id'] = $details['pg_id'];
                            $pg_details = $this->model->getPGDeatails($fee_id, 'payment_gateway');
                        } else {
                            $pg_details = $this->model->getPGDeatails($merchant_id, null, $franchise_id);
                            $fee_id = $pg_details['xway_merchant_detail_id'];
                            if ($pg_details['surcharge_enable'] == 1 && $pg_details['pg_surcharge_enabled'] == 0) {
                                $surcharge_enable = 1;
                            }
                        }

                        $valid_return = $this->validateUrl($_POST['return_url'], $pg_details['return_url']);
                        if ($valid_return == 0) {
                            $hasErrors['Payment failed'][0] = 'Invalid link';
                            $result['error'] = "Invalid Return Url: " . $_POST['return_url'] . " ,";
                        }
                        $valid_ref = $this->validateUrl($_SERVER['HTTP_REFERER'], $pg_details['referrer_url']);
                        if ($valid_ref == 0) {
                            $hasErrors['Payment failed'][0] = 'Invalid link';
                            $result['error'] = $result['error'] . 'Invalid Referrer Url :' . $_SERVER['HTTP_REFERER'];
                        }

                        if (isset($_POST['currency'])) {
                            if (in_array($_POST['currency'], $this->currency)) {
                                $currency = $_POST['currency'];
                            } else {
                                $hasErrors['Payment failed'][0] = 'Invalid currency';
                                $hasErrors['Payment failed'][1] = 'Your currency is invalid.';
                            }
                        } else {
                            $currency = 'INR';
                        }

                        if (!$hasErrors) {
                            $result = $this->model->validatexway($_POST['mode'], $merchant_id, $_POST['amount'], $_POST['reference_no']);
                        }
                        if ($result['message'] == 'success') {

                            if ($pg_details['ga_tag'] != '') {
                                $this->view->global_tag = $pg_details['ga_tag'];
                            }
                            if ($pg_details['pg_count'] > 1) {
                                $this->multigateway($merchant_id);
                            } else {
                                if ($surcharge_enable == 1) {
                                    $surcharge = $this->model->getXwaySurcharge($_POST['amount'], $fee_id);
                                } else {
                                    $surcharge = 0;
                                }
                                $result['absolute_cost'] = $result['absolute_cost'] + $surcharge;
                                $details = $this->model->savexwaytransaction($pg_details['pg_id'], round($result['absolute_cost'], 2), $merchant_id, $_SERVER['HTTP_REFERER'], $_POST['return_url'], $_POST['reference_no'], $_POST['amount'], $surcharge, $_POST['description'], $_POST['name'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['account_id'], $_POST['postal_code'], $_POST['phone'], $_POST['email'], $_POST['udf1'], $_POST['udf2'], $_POST['udf3'], $_POST['udf4'], $_POST['udf5'], $_POST['mdd'], $_POST['secure_hash'], '', $_POST['franchise_id'], $vendor_id, $currency, 0, 1, 0, 0, $create_invoice_api);
                                $details['currency'] = $currency;
                                $details['@patron_mobile_no'] = $_POST['phone'];
                                $details['@patron_email'] = $_POST['email'];
                                $details['vendor_id'] = $pg_details['vendor_id'];
                                $transaction_id = $details['xtransaction_id'];
                                if (isset($_POST['webhook_id'])) {
                                    if ($_POST['webhook_id'] > 0) {
                                        $this->common->genericupdate('xway_transaction', 'webhook_id', $_POST['webhook_id'], 'xway_transaction_id', $details['xtransaction_id']);
                                    }
                                }

                                if ($create_invoice_api == 1) {
                                    //save auto_api_invoice_request
                                    $auto_invoice_request_tbl_id = $this->model->saveAutoInvoiceApiRequest($transaction_id, $_POST['invoice_api_request'], $merchant_id);
                                }

                                $paymentWrapper = new PaymentWrapperController();
                                $this->session->set('transaction_type', 'xway');
                                $this->session->set('transaction_id', $transaction_id);
                                $pg_details['repath'] = '';
                                $pg_details['fee_id'] = $fee_id;
                                $paymentWrapper->paymentProcced($transaction_id, $details, $pg_details, $_POST['name'], '', $_POST['email'], $_POST['phone'], NULL, NULL, NULL, NULL, 'guest', $this->common, $this->view, $this->encrypt);

                                if ($error != '') {
                                    SwipezLogger::error(__CLASS__, '[x005]Error while invoke PG PG_type ' . $pg_details['pg_type'] . ' Error: ' . $error);
                                }
                            }
                        } else {
                            $hasErrors['Payment failed'][0] = 'Payment failed';
                            $hasErrors['Payment failed'][1] = $result['error'];
                        }
                    }
                }
            }
            if (!empty($hasErrors)) {
                SwipezLogger::error(__CLASS__, '[x004]Error: validation ' . json_encode($hasErrors));
                $this->view->setError($hasErrors);
                $this->view->render('error/xway');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x007]Error while x-way request Error: ' . $e->getMessage());
            $hasErrors['Payment failed'][0] = 'Payment failed';
            $hasErrors['Payment failed'][1] = $e->getMessage();
            $this->view->setError($hasErrors);
            $this->view->render('error/xway');
        }
    }

    function validateSecureHash($post)
    {
        try {
            $details = $this->model->getMerchantXwayDeatails($_POST['account_id'], $_POST['franchise_id']);
            $hash = $details['xway_security_key'] . "|" . $post['merchant_id'] . "|" . $post['amount'] . "|" . $post['reference_no'] . "|" . $post['return_url'];
            $system_hash = md5($hash);
            if ($post['secure_hash'] != $system_hash) {
                $hasErrors['Payment failed'][0] = 'Secure hash';
                $hasErrors['Payment failed'][1] = 'Invalid secure hash';
                return $hasErrors;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x008]Error while validateSecureHash ' . $e->getMessage());
        }
    }

    function validateUrl($url, $req_url)
    {
        try {
            $env = getenv('ENV');
            if ($env != 'PROD') {
                return 1;
            }
            $success = 0;
            if (substr($url, 0, 4) != 'http') {
                $url = "http://" . $url;
            }
            $request_url = parse_url($url);
            $request_url = $request_url['host'];
            $url_array = explode(',', $req_url);
            foreach ($url_array as $data_url) {
                if (substr($data_url, 0, 1) == '*') {
                    $dot_position = strpos($request_url, '.');
                    $host = substr($request_url, $dot_position);
                    $validate_request_url = '*' . $host;
                } else {
                    $validate_request_url = $request_url;
                }

                if ($validate_request_url == $data_url) {
                    $success = 1;
                }
            }
            if ($success != 1) {
                SwipezLogger::error(__CLASS__, 'Error while validateURL :' . $req_url . ' Expected url: ' . $url);
            }
            return $success;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x008+1]Error while validateURL ' . $e->getMessage());
        }
    }

    function handleResponse($result)
    {
        try {
            if (substr($result['return_url'], 0, 4) != 'http') {
                $return_url = "http://" . $result['return_url'];
            } else {
                $return_url = $result['return_url'];
            }
            $this->view->post_url = $return_url;
            unset($result['return_url']);
            $details = $this->model->getMerchantXwayDeatails($result['checksum'], $result['franchise_id']);
            if (strpos($return_url, 'swipez.in/m/') !== false) {
            } else {
                if ($result['status'] == 'success') {
                    //check if invoice_creation_via_xway is requeired  else call notification
                    if ($result['create_invoice_api'] == 1) {
                        // update api_request_json with transaction & settlement details and pass auto_invoice_request_table id to create invoice queue
                        $this->setAutoCreateInvoiceJsonRequest($result);
                    } else {
                        $this->notification($result, $details);
                    }
                }
            }
            $result['reference_no'] = ($result['reference_no'] == '') ? $result['transaction_id'] : $result['reference_no'];
            $transaction_id = $result['reference_no'];
            $this->session->set('xway_response', 1);
            $hash = $details['xway_security_key'] . "|" . $result['amount'] . "|" . $result['reference_no'] . "|" . $result['billing_email'];
            $checksum = md5($hash);
            $result['checksum'] = $checksum;
            SwipezLogger::info(__CLASS__, 'Xway response Redirected successfully : Reference_no: ' . $transaction_id);
            $webhook_id = $this->common->getRowValue('webhook_id', 'xway_transaction', 'xway_transaction_id', $transaction_id);
            if ($details['ga_tag'] != '') {
                $this->view->global_tag = $details['ga_tag'];
            }
            if ($webhook_id > 0) {
                $this->handleWebhook($webhook_id, $result);
            }
            $this->view->post = $result;
            $this->view->render('secure/redirect');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x009]Error while sending response ' . $e->getMessage());
        }
    }

    function setAutoCreateInvoiceJsonRequest($result)
    {
        try {
            $get_auto_request_details = $this->common->getSingleValue('auto_invoice_api_request', 'transaction_id', $result['transaction_id']);
            if (!empty($get_auto_request_details)) {
                $api_request_json = json_decode($get_auto_request_details['api_request_json'], true);

                if (!empty($api_request_json)) {
                    //find merchant keys
                    $get_merchant_keys = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $get_auto_request_details['merchant_id']);
                    if (!empty($get_merchant_keys)) {
                        $api_request_json['access_key_id'] = $get_merchant_keys['access_key_id'];
                        $api_request_json['secret_access_key'] = $get_merchant_keys['secret_access_key'];
                        $customer = $api_request_json['invoice'][0]['new_customer'];
                        $customer['customer_name'] = $result['billing_name'];
                        $customer['email'] = $result['billing_email'];
                        $customer['mobile'] = $result['billing_mobile'];
                        $customer['address'] = $result['billing_address'];
                        $customer['city'] = $result['billing_city'];
                        $customer['state'] = $result['billing_state'];
                        $customer['zipcode'] = $result['billing_postal_code'];
                        $api_request_json['invoice'][0]['new_customer'] = $customer;

                        //add settlement block here
                        $settlement['access_key_id'] = $api_request_json['access_key_id'];
                        $settlement['secret_access_key'] = $api_request_json['secret_access_key'];
                        $settlement['invoice_id'] = '';
                        $settlement['paid_date'] = date('Y-m-d');
                        $settlement['amount'] = $result['amount'];
                        $settlement['mode'] = 'Swipez';
                        $settlement['bank_name'] = '';
                        $settlement['bank_ref_no'] = $result['transaction_id'];
                        $settlement['cheque_no'] = '';
                        $settlement['cash_paid_to'] = '';
                        $settlement['notify'] = '1';
                        $settlement['attach_invoice_pdf'] = '1';
                        $api_request_json['invoice'][0]['settlement'] = $settlement;

                        //update json in auto invoice api request
                        $this->common->genericupdate('auto_invoice_api_request', 'api_request_json', json_encode($api_request_json, true), 'id', $get_auto_request_details['id']);
                    }
                }
                InvoiceCreationViaXway::dispatch($get_auto_request_details['id'])->onQueue(env('SQS_INVOICE_CREATION_VIA_XWAY'));
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x009]Error while creating data for auto create invoice request via xway' . $e->getMessage());
        }
    }

    function handleWebhook($webhook_id, $result)
    {
        try {
            unset($result['checksum']);
            unset($result['merchant_domain']);
            unset($result['franchise_id']);
            unset($result['request_amount']);
            unset($result['create_invoice_api']);
            unset($result['type']);
            $detail = $this->common->getSingleValue('merchant_webhook', 'webhook_id', $webhook_id);
            $url = $detail['success_url'];
            $curl = curl_init();
            $header = array();
            if ($this->session->has('API_TOKEN')) {
                $header = array(
                    "Content-Type: application/json",
                    "Authorization:Bearer " . $this->session->get('API_TOKEN')
                );
            }
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($result),
                CURLOPT_HTTPHEADER => $header,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x00101] Webhook error' . $e->getMessage());
        }
    }

    function notification($result, $details)
    {
        $emailWrapper = new EmailWrapper();
        $this->smarty->assign("response", $result);
        $logo = $this->model->getMerchantLOGO($details['merchant_id']);
        if ($logo != '') {
            $logo = 'https://www.swipez.in/uploads/images/landing/' . $logo;
        }
        $this->smarty->assign("logo", $logo);
        if ($details['notify_merchant'] == 1) {
            $subject_ = 'Payment receipt from ' . $result['billing_name'];
            $this->smarty->assign("from", 'Customer');
            $mailer_receipt = $this->smarty->fetch(VIEW . 'mailer/xway_receipt.tpl');
            $emailWrapper->sendMail($result['merchant_email'], "", $subject_, $mailer_receipt);
        }
        if ($details['notify_patron'] == 1) {
            $this->smarty->assign("from", 'Your');
            $subject_ = 'Payment receipt from ' . $result['company_name'];
            $mailer_receipt = $this->smarty->fetch(VIEW . 'mailer/xway_receipt.tpl');
            $emailWrapper->sendMail($result['billing_email'], "", $subject_, $mailer_receipt);
        }
    }

    function multigateway($merchant_id)
    {
        try {
            $pg_details = $this->model->getMerchantPG($merchant_id);

            $this->smarty->assign("post_url", '/payment-gateway');
            $this->smarty->assign("request_post_url", '/xway/secure');
            $this->smarty->assign("is_new_pg", true);

            $details = $this->common->getSingleValue('merchant', 'merchant_id', $merchant_id);
            $invoice = new PaymentWrapperController();
            $radio = $invoice->getPGRadio($pg_details, $this->encrypt);
            $this->smarty->assign("paypal_id", $radio['paypal_id']);
            $this->smarty->assign("radio", $radio['radio']);
            $this->view->js = array('invoice');

            $this->smarty->assign("encrypt_grandtotal", $this->encrypt->encode($_POST['amount']));
            $this->smarty->assign("company_name", $details['company_name']);
            $this->smarty->assign("url", $details['display_url']);
            $this->smarty->assign("post", $_POST);
            // $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'secure/confirm.tpl');
            //$this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x00101]Error while multigateway response ' . $e->getMessage());
        }
    }

    function getmerchantkeys()
    {
        try {
            $response = array();
            $data = file_get_contents('php://input');
            $post_data = json_decode($data, true);
            if (!empty($post_data)) {
                $details = $this->common->getSingleValue('merchant_security_key', 'merchant_id', $post_data['merchant_id']);
                if (!empty($details)) {
                    $response['access_key_id'] = $details['access_key_id'];
                    $response['secret_access_key'] = $details['secret_access_key'];
                    echo json_encode($response, true);
                }
            }
            return false;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[x00101]Error while getting merchant credientials woocommerce' . $e->getMessage());
        }
    }

    function easebuzzresponse()
    {
        try {
            $this->view->render('secure/loader');
            SwipezLogger::info(__CLASS__, 'Easebuzz payment response ' . json_encode($_POST));
            $pg_transaction_id = $_POST["txnid"];
            if (strlen($pg_transaction_id) == 10) {
                $transaction_detail = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $pg_transaction_id);
                $transaction_status = $transaction_detail['xway_transaction_status'];
                if ($transaction_status == 1) {
                    //need to ask
                    //header('Location:/patron/transaction/receipt/' . $this->encrypt->encode($pg_transaction_id));
                    exit();
                } else {
                    $patron_name = $this->session->get('patron_name');
                    $email = $this->session->get('email');
                    $this->session->remove('transaction_type');
                    $this->session->remove('transaction_id');
                    $pg_type = NULL;
                    $wrap = new PaymentWrapperController();

                    $pg_details = $this->common->getSingleValue('payment_gateway', 'pg_id', $transaction_detail['pg_id']);
                    $easebuzzObj = new Easebuzz($pg_details['pg_val1'], $pg_details['pg_val2'], $pg_details['pg_val3']);
                    $result = $easebuzzObj->easebuzzResponse($_POST);
                    $response = json_decode($result, true);
                    if ($response['status'] == 1) {
                        $isValidChecksum = TRUE;
                    } else {
                        $isValidChecksum = FALSE;
                    }

                    if ($isValidChecksum === TRUE) {
                    } else {
                        SwipezLogger::error(__CLASS__, '[E514]Error while Easebuzz response Error: checksum mismatch ' . json_encode($_POST));
                        Sentry\captureMessage('Easebuzz response Error: checksum mismatch : ' . json_encode($_POST));
                        $this->setPaymentFailedError($pg_transaction_id);
                    }
                    $result = $this->model->handleEasebuzzGatewayResponse($patron_name, $email, $_POST, $this->session->get('userid'), $pg_type, $pg_transaction_id);
                    $this->handleResponse($result, $pg_type);
                }
            } else {
                $this->setPaymentFailedError($pg_transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+5]Error while Easebuzz response Error: ' . $e->getMessage());
        }
    }
}
