<?php

/**
 * Secure controller class to handle  payment gateways response 
 */

use Razorpay\Api\Api;
use App\Jobs\PatronNotification;
use App\Jobs\SupportTeamNotification;
use App\Http\Controllers\PaymentWrapperController;
use App\Libraries\Easebuzz\Easebuzz;

class Secure extends Controller
{

    public $request_type = 1;
    function __construct()
    {
        parent::__construct();
    }

    function invoke($type = null)
    {
        if ($type == null) {
            $this->view->render('secure/invoke');
        } else {
            $this->view->post_url = $_POST['post_url'];
            unset($_POST['post_url']);
            if ($type == 'cashfree') {
                $_POST['signature'] = str_replace("~plus~", "+", $_POST['signature']);
            }
            $this->view->post = $_POST;
            $this->view->render('secure/redirect');
        }
    }

    /**
     * Redirect to payment gateways page
     */
    function paytminvoke()
    {
        try {
            $pg_transaction_type = $_POST["t_type"];
            $this->set_PaytmDetail($pg_transaction_type, $_POST["ORDER_ID"]);
            $this->view->PAYTM_MERCHANT_MID = PAYTM_MERCHANT_MID;
            $this->view->PAYTM_MERCHANT_WEBSITE = PAYTM_MERCHANT_WEBSITE;
            $this->view->PAYTM_MERCHANT_KEY = PAYTM_MERCHANT_KEY;
            $this->view->PAYTM_TXN_URL = PAYTM_TXN_URL;

            require_once(UTIL . "encdec_paytm2.php");
            $checkSum = "";
            $paramList = array();
            $paramList["MID"] = PAYTM_MERCHANT_MID;
            $paramList["ORDER_ID"] = $_POST["ORDER_ID"];
            $paramList["CUST_ID"] = $_POST["CUST_ID"];
            $paramList["INDUSTRY_TYPE_ID"] = $_POST["INDUSTRY_TYPE_ID"];
            $paramList["CHANNEL_ID"] = $_POST["CHANNEL_ID"];
            $paramList["TXN_AMOUNT"] = $_POST["TXN_AMOUNT"];
            $paramList["EMAIL"] = $_POST["EMAIL"];
            $paramList["MOBILE_NO"] = $_POST["MOBILE_NO"];
            $paramList["CALLBACK_URL"] = $_POST["CALLBACK_URL"];
            $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
            $this->view->paramList = $paramList;
            $this->view->checksum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
            $this->view->render('secure/paytminvoke');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510-89]Error while paytm request Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    function paytmsubinvoke()
    {
        try {
            $pg_transaction_type = $_POST["t_type"];
            $this->set_PaytmDetail($pg_transaction_type, $_POST["ORDER_ID"]);
            $this->view->PAYTM_MERCHANT_MID = PAYTM_MERCHANT_MID;
            $this->view->PAYTM_MERCHANT_WEBSITE = PAYTM_MERCHANT_WEBSITE;
            $this->view->PAYTM_MERCHANT_KEY = PAYTM_MERCHANT_KEY;
            $this->view->PAYTM_TXN_URL = PAYTM_TXN_URL;


            require_once(UTIL . "encdec_paytm2.php");
            $checkSum = "";
            $paramList = array();
            $paramList["MID"] = PAYTM_MERCHANT_MID;
            $paramList["ORDER_ID"] = $_POST["ORDER_ID"];
            $paramList["CUST_ID"] = $_POST["CUST_ID"];
            $paramList["INDUSTRY_TYPE_ID"] = $_POST["INDUSTRY_TYPE_ID"];
            $paramList["CHANNEL_ID"] = $_POST["CHANNEL_ID"];
            $paramList["TXN_AMOUNT"] = $_POST["TXN_AMOUNT"];
            $paramList["REQUEST_TYPE"] = 'SUBSCRIBE';
            $paramList["THEME"] = 'merchant';
            $paramList["SUBS_SERVICE_ID"] = $_POST["SUBS_SERVICE_ID"];
            $paramList["SUBS_FREQUENCY_UNIT"] = 'MONTH';
            $paramList["SUBS_FREQUENCY"] = '1';
            $paramList["SUBS_ENABLE_RETRY"] = '1';
            $paramList["SUBS_EXPIRY_DATE"] = $_POST["expiry_date"];
            $paramList["SUBS_PPI_ONLY"] = 'Y';
            $paramList["SUBS_AMOUNT_TYPE"] = 'VARIABLE';
            $paramList["CALLBACK_URL"] = $_POST["CALLBACK_URL"];
            $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
            $paramList["SUBS_START_DATE"] = $_POST["start_date"];
            $paramList["SUBS_GRACE_DAYS"] = '15';
            $paramList["SUBS_MAX_AMOUNT"] = $_POST["max_amount"];


            $this->view->paramList = $paramList;
            $this->view->checksum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
            $this->view->render('secure/paytminvoke');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510-89]Error while paytm request Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    function set_PaytmDetail($type, $transaction_id)
    {
        try {
            $row = $this->model->getPaymentGatewayDetails($type, $transaction_id);
            define('PAYTM_ENVIRONMENT', $row['pg_val3']); // PROD
            define('PAYTM_MERCHANT_KEY', $row['pg_val1']); //Change this constant's value with Merchant key downloaded from portal
            define('PAYTM_MERCHANT_MID', $row['pg_val2']); //Change this constant's value with MID (Merchant ID) received from Paytm
            define('PAYTM_MERCHANT_WEBSITE', $row['pg_val4']); //Change this constant's value with Website name received from Paytm
            define('PAYTM_TXN_URL', $row['req_url']);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510-88]Error while set_PaytmDetail Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    function payuinvoke()
    {
        $this->view->post_url = $_POST['udf3'];
        $_POST['udf3'] = '';
        $this->view->post = $_POST;
        $this->view->render('secure/payuinvoke');
    }

    /**
     * Redirect to payment gateways page
     */
    function techprocessinvoke($data, $pgdetails)
    {
        try {
            $this->view->js_link1 = $pgdetails['pg_val5'];
            $this->view->js_link2 = $pgdetails['pg_val6'];
            $this->view->json_string = json_encode($data);
            $this->view->render('secure/techprocessinvoke');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510-95]Error while techprocess request Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    /**
     * handle respose from techprocess gateways
     */
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
                SwipezLogger::error(__CLASS__, '[E500+20]Error while techprocess gateway response Error: checksum mismatch : ' . json_encode($post_array));
                Sentry\captureMessage('Techprocess gateway response Error: checksum mismatch : ' . json_encode($post_array));
                $this->paymentError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510]Error while paytm response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    public function razorpayresponse()
    {
        try {
            $transaction_id = $_POST['transaction_id'];
            $pg_type = $this->session->get('transaction_type');
            SwipezLogger::info(__CLASS__, 'Razorpay payment response ' . json_encode($_POST));
            $this->view->render('secure/loader');
            if (strlen($transaction_id) == 10) {
                if (substr($transaction_id, 0, 1) == 'F') {
                    $transaction = $this->common->getSingleValue('package_transaction', 'package_transaction_id', $transaction_id);
                } else if (substr($transaction_id, 0, 1) == 'T') {
                    $transaction = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $transaction_id);
                } else {
                    $transaction = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id);
                    $transaction['payment_transaction_status'] = $transaction['xway_transaction_status'];
                }
                if (!empty($transaction)) {
                    $transaction_status = $transaction['payment_transaction_status'];
                    if ($transaction_status == 0 || $transaction_status == 4 || $transaction_status == 5) {
                        $pg_detail = $this->common->getSingleValue('payment_gateway', 'pg_id', $transaction['pg_id']);
                        $api = new Api($pg_detail['pg_val1'], $pg_detail['pg_val2']);
                        if ($pg_detail['pg_val7'] != '') {
                            $api->setHeader('x-razorpay-account', $pg_detail['pg_val7']);
                        }
                        $payment = $api->payment->fetch($_POST['razorpay_payment_id']);
                        $result = $this->model->saveRazorpayResponse($payment, $transaction_id, $_POST['name']);
                        $result['type'] = 'request';
                        $this->handleResponse($result, $pg_type);
                    } else if ($transaction_status == 1) {
                        header('Location:/patron/transaction/receipt/' . $this->encrypt->encode($transaction_id));
                        exit();
                    } else {
                        $this->setPaymentFailedError($transaction_id);
                    }
                } else {
                    $this->setPaymentFailedError($transaction_id);
                }
            } else {
                $this->setPaymentFailedError($transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'SC005+1 Error Razor pay response : ' . $e->getMessage());
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
                $pg_type = $this->session->get('transaction_type');
                $payment_intent = $stripe->paymentIntents->retrieve(
                    $intent,
                    []
                );
                if (!empty($transaction)) {
                    $transaction_status = $transaction['payment_transaction_status'];
                    if ($transaction_status == 0 || $transaction_status == 4 || $transaction_status == 5) {
                        $result = $this->model->saveStripeResponse($payment_intent);
                        $result['type'] = 'request';
                        $result['amount'] = $result['amount'] / 100;
                        $this->handleResponse($result, $pg_type);
                    } else if ($transaction_status == 1) {
                        header('Location:/patron/transaction/receipt/' . $this->encrypt->encode($transaction_id));
                        exit();
                    } else {
                        $this->setPaymentFailedError($transaction_id);
                    }
                } else {
                    $this->setPaymentFailedError($transaction_id);
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[E510+5]Error while Stripe response Error: ' . $e->getMessage());
                $this->paymentError();
            }
        } else {
            $this->setPaymentFailedError($transaction_id);
        }
    }

    /**
     * handle respose from paytm gateways
     */
    function paytmresponse()
    {
        try {
            $pg_transaction_id = $_POST['ORDERID'];
            $pg_transaction_type = $this->session->get('transaction_type');
            $this->view->render('secure/loader');
            if (strlen($pg_transaction_id) == 10) {
                $this->session->remove('transaction_type');
                $this->session->remove('transaction_id');
                $this->session->remove('paytm_cust_id');
                $this->set_PaytmDetail($pg_transaction_type, $pg_transaction_id);
                require_once(UTIL . "encdec_paytm2.php");
                $paytmChecksum = "";
                $paramList = array();
                $isValidChecksum = FALSE;
                $paramList = $_POST;
                $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
                //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
                $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

                if ($isValidChecksum === TRUE || $isValidChecksum === "TRUE") {
                    $patron_name = $this->session->get('patron_name');
                    $email = $this->session->get('email');
                    $result = $this->model->handlePaytmGatewayResponse($patron_name, $email, $_POST['MID'], $_POST['ORDERID'], $_POST['TXNAMOUNT'], $_POST['CURRENCY'], $_POST['TXNID'], $_POST['BANKTXNID'], $_POST['STATUS'], $_POST['RESPCODE'], $_POST['RESPMSG'], $_POST['TXNDATE'], $_POST['GATEWAYNAME'], $_POST['BANKNAME'], $_POST['PAYMENTMODE'], $_POST['CHECKSUMHASH'], $this->session->get('userid'), $pg_transaction_type, $pg_transaction_id);
                    $this->handleResponse($result, $pg_transaction_type);
                } else {
                    SwipezLogger::error(__CLASS__, '[E500]Error while Paytm gateway response Error: checksum mismatch : ' . $paytmChecksum);
                    Sentry\captureMessage('Paytm gateway response Error: checksum mismatch : ' . json_encode($_POST));

                    $this->setPaymentFailedError($pg_transaction_id);
                }
            } else {
                $this->setPaymentFailedError($pg_transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510]Error while paytm response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    /**
     * handle respose from paytm gateways
     */
    function cashfreeresponse()
    {
        try {
            $pg_transaction_id = $_POST['orderId'];
            SwipezLogger::info(__CLASS__, 'Cashfree payment response ' . json_encode($_POST));
            $pg_type = $this->session->get('transaction_type');
            $this->view->render('secure/loader');
            if (strlen($pg_transaction_id) == 10) {
                $transaction_status = $this->common->getRowValue('payment_transaction_status', 'payment_transaction', 'pay_transaction_id', $pg_transaction_id);
                if ($transaction_status == 1) {
                    header('Location:/patron/transaction/receipt/' . $this->encrypt->encode($pg_transaction_id));
                    exit();
                } else {
                    $patron_name = $this->session->get('patron_name');
                    $wrap = new PaymentWrapperController();
                    $email = $this->session->get('email');
                    if ($this->request_type == 1) {
                        $isValidChecksum = $wrap->validateCashfree($pg_transaction_id, $_POST);
                    } else {
                        $isValidChecksum = TRUE;
                    }

                    if ($isValidChecksum === TRUE) {
                    } else {
                        SwipezLogger::error(__CLASS__, '[E514]Error while Cashfree gateway response Error: checksum mismatch ' . json_encode($_POST));
                        Sentry\captureMessage('Cashfree gateway response Error: checksum mismatch : ' . json_encode($_POST));
                        $this->setPaymentFailedError($pg_transaction_id);
                    }
                    $result = $this->model->handleCashfreeGatewayResponse($patron_name, $email, $_POST, $this->session->get('userid'), $pg_type, $pg_transaction_id);
                    $result['pg_type'] = $pg_type;
                    $this->handleResponse($result, $pg_type);
                }
            } else {
                $this->setPaymentFailedError($pg_transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+5]Error while Cashfree response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    /**
     * handle respose from paytm gateways
     */
    function seturesponse()
    {
        try {
            $order_hash = $_POST["orderId"];
            $pg_transaction_id = $this->encrypt->decode($order_hash);
            SwipezLogger::info(__CLASS__, 'Cashfree payment response ' . json_encode($_POST));
            $pg_type = $this->session->get('transaction_type');
            $this->view->render('secure/loader');
            if (strlen($pg_transaction_id) == 10) {
                $transaction_status = $this->common->getRowValue('payment_transaction_status', 'payment_transaction', 'pay_transaction_id', $pg_transaction_id);

                if ($transaction_status == 1) {
                    header('Location:/patron/transaction/receipt/' . $this->encrypt->encode($pg_transaction_id));
                    exit();
                } else {
                    $patron_name = $this->session->get('patron_name');
                    $email = $this->session->get('email');

                    $result = $this->model->handleSetuGatewayResponse($patron_name, $email, $_POST, $this->session->get('userid'), $pg_type, $pg_transaction_id);
                    $result['pg_type'] = $pg_type;
                    $this->handleResponse($result, $pg_type);
                }
            } else {
                $this->setPaymentFailedError($pg_transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+5]Error while Cashfree response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    function cashfreeautocollect()
    {
        $data = $_POST;
        if ($data['cf_status'] == 'INITIALIZED' || empty($_POST)) {
            $this->setPaymentFailedError();
        }
        $transaction_id = $this->common->getRowValue('transaction_id', 'autocollect_transaction', 'pg_ref', $data['cf_referenceId']);
        $subscription = $this->common->getSingleValue('autocollect_subscriptions', 'subscription_id', $data['cf_subscriptionId']);
        //require_once MODEL . 'patron/PaymentrequestModel.php';
        // $model = new PaymentRequestModel();
        // $transaction_id = $model->intiatePaymentTransaction($subscription['payment_request_id'], 1, $subscription['customer_id'], $subscription['customer_id'], $subscription['created_by'], $subscription['merchant_id'], $data['cf_authAmount'], 0, 0, 0, 0, 0, 0);
        if ($transaction_id == false) {
            $this->model->saveAutocollectTransaction($data, $subscription['merchant_id'], null, $subscription['plan_id'], $subscription['customer_id']);
        }
        $data['date'] = date('Y-m-d H:m:s');
        $this->request_type = 2;
        $company_name = $this->common->getRowValue('company_name', 'merchant', 'merchant_id', $subscription['merchant_id']);
        $data['company_name'] = $company_name;

        $this->smarty->assign("response", $data);
        $this->smarty->assign("subscription", $subscription);
        $this->view->render('header/guest');
        $this->smarty->display(VIEW . 'patron/paymentrequest/autocollect.tpl');
        $this->view->render('footer/nonfooter');
    }

    function paypalresponse($ref_id = '', $pg_link = '')
    {
        try {
            if ($ref_id != '') {
                $explode = explode('/', $ref_id);
                $ref_id = $explode[0];
                $pg_link = $explode[1];
                $pg_type = $this->session->get('transaction_type');
                $pg_id = $this->encrypt->decode($pg_link);
                if ($pg_id > 0) {
                    $pg_det = $this->common->getSingleValue('payment_gateway', 'pg_id', $pg_id);
                    if (!empty($pg_det)) {
                        $paymentWrapper = new PaymentWrapperController();
                        $key = base64_encode($pg_det['pg_val4'] . ':' . $pg_det['pg_val2']);
                        $response = $paymentWrapper->PaypalOrderStatus($ref_id, $key, $pg_det['pg_val3']);
                        SwipezLogger::info(__CLASS__, 'Paypal Response ' . json_encode($response));
                        $res['ref_id'] = $ref_id;
                        $res['status'] = $response['status'];
                        $res['transaction_id'] = $response['purchase_units'][0]['reference_id'];
                        $res['amount'] = $response['purchase_units'][0]['amount']['value'];
                        $res['name'] = $response['payer']['name']['given_name'] . ' ' . $response['payer']['name']['surname'];
                        $res['email'] = $response['payer']['email_address'];
                        $result = $this->model->handlePaypalResponse($res);
                        $this->handleResponse($result, $pg_type);
                    } else {
                        $this->paymentError();
                    }
                } else {
                    $this->paymentError();
                }
            } else {
                SwipezLogger::info(__METHOD__, '[E510+5]Error while paypal response Error: Empty ref id');
                $this->paymentError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+6]Error while paypal response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    /**
     * handle respose from paytm gateways
     */
    function paytmsubresponse()
    {
        try {
            $pg_transaction_id = $this->session->get('transaction_id');
            $pg_transaction_type = $this->session->get('transaction_type');
            $this->session->remove('paytm_cust_id');
            $this->set_PaytmDetail($pg_transaction_type, $pg_transaction_id);
            require_once(UTIL . "encdec_paytm2.php");
            $paytmChecksum = "";
            $paramList = array();
            $isValidChecksum = FALSE;

            $paramList = $_POST;
            $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
            //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
            $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

            if ($isValidChecksum === TRUE) {
                $pg_type = $this->session->get('transaction_type');
                $patron_name = $this->session->get('patron_name');
                $email = $this->session->get('email');


                $this->session->remove('transaction_type');
                $this->session->remove('transaction_id');
                $result = $this->model->handlePaytmGatewayResponse($patron_name, $email, $_POST['MID'], $_POST['ORDERID'], $_POST['TXNAMOUNT'], $_POST['CURRENCY'], $_POST['TXNID'], $_POST['BANKTXNID'], $_POST['STATUS'], $_POST['RESPCODE'], $_POST['RESPMSG'], $_POST['TXNDATE'], $_POST['GATEWAYNAME'], $_POST['BANKNAME'], $_POST['PAYMENTMODE'], $_POST['CHECKSUMHASH'], $this->session->get('userid'), $pg_type, $pg_transaction_id);
                if ($result['status'] == 'success') {
                    $status = 1;
                } else {
                    $status = 2;
                }
                $subscription_id = (isset($_POST['SUBS_ID'])) ? $_POST['SUBS_ID'] : '';
                $ref_no = (isset($_POST['TXNID'])) ? $_POST['TXNID'] : '';
                $ref_1 = (isset($_POST['BANKTXNID'])) ? $_POST['BANKTXNID'] : '';
                $this->model->updateSubscriptionStatus($status, $subscription_id, $ref_no, $ref_1, $_POST['ORDERID']);
                $this->handleResponse($result, $pg_type);
            } else {
                SwipezLogger::error(__CLASS__, '[E500]Error while Paytm gateway response Error: checksum mismatch : ' . $paytmChecksum);
                $this->paymentError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510]Error while paytm response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    /**
     * Handle response from EBS gateways
     */
    function response($link)
    {
        try {
            if (isset($link)) {
                $pg_type = $this->session->get('transaction_type');
                $pg_transaction_id = $this->session->get('transaction_id');
                if (isset($pg_type) && isset($pg_transaction_id)) {

                    if ($this->session->get('userid')) {
                        $user_id = $this->session->get('userid');
                    } else {
                        $user_id = 'Guest';
                    }
                    $result = $this->model->handlePaymentGatewayResponse($link, $user_id, $pg_type, $pg_transaction_id);
                    $this->session->remove('transaction_type');
                    $this->session->remove('transaction_id');
                    $this->handleResponse($result, $pg_type);
                } else {
                    SwipezLogger::error(__CLASS__, '[E094-R]Error while payment gateway response  user id: ' . $this->session->get('userid'));
                    $this->paymentError();
                }
            } else {
                SwipezLogger::error(__CLASS__, '[E095]Error while payment gateway response  user id: ' . $this->session->get('userid'));
                $this->paymentError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    function payuresponse()
    {
        try {
            $pg_type = $this->session->get('transaction_type');
            $pg_transaction_id = $_POST["txnid"];
            SwipezLogger::info(__CLASS__, 'PAYU payment response ' . json_encode($_POST));
            if (isset($pg_transaction_id)) {
                $this->session->remove('transaction_type');
                $this->session->remove('transaction_id');
                if ($this->session->get('userid')) {
                    $user_id = $this->session->get('userid');
                } else {
                    $user_id = 'Guest';
                }
                $result = $this->model->handlePayuResponse($_POST, $user_id, $pg_type, $pg_transaction_id);
                $this->handleResponse($result, $pg_type);
            } else {
                SwipezLogger::error(__CLASS__, '[E094-P]Error while payment gateway response error session expired Response: ' . json_encode($_POST));
                $this->paymentError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    function atomresponse()
    {
        try {
            $pg_type = $this->session->get('transaction_type');
            $pg_transaction_id = $_POST['mer_txn'];
            SwipezLogger::info(__CLASS__, 'Atom payment response ' . json_encode($_POST));
            $this->session->remove('transaction_type');
            $this->session->remove('transaction_id');
            if ($this->session->get('userid')) {
                $user_id = $this->session->get('userid');
            } else {
                $user_id = 'Guest';
            }

            $row = $this->model->getPaymentGatewayDetails($pg_type, $pg_transaction_id);
            if ($row['pg_val9'] != '' && isset($_POST["signature"])) {
                $this->validateAtomResponse($_POST, $row['pg_val9']);
            }
            $result = $this->model->handleAtomResponse($_POST, $user_id, $pg_type, $pg_transaction_id);
            $this->handleResponse($result, $pg_type);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    public function validateAtomResponse($responseParams, $respHashKey)
    {
        $str = $responseParams["mmp_txn"] . $responseParams["mer_txn"] . $responseParams["f_code"] . $responseParams["prod"] . $responseParams["discriminator"] . $responseParams["amt"] . $responseParams["bank_txn"];
        $signature = hash_hmac("sha512", $str, $respHashKey, false);
        if ($signature == $responseParams["signature"]) {
            //SwipezLogger::debug(__CLASS__, '[E500]ATOM checksum match : ' . json_encode($responseParams));
        } else {
            SwipezLogger::error(__CLASS__, '[E500]Error: ATOM checksum mismatch : ' . json_encode($responseParams));
            Sentry\captureMessage('ATOM checksum mismatch : ' . json_encode($responseParams));
            $this->paymentError();
        }
    }

    public function paymentfailed($link = null)
    {
        try {
            $message = '';
            $transaction_id = 'NA';
            if ($link != null) {
                $transaction_id = $this->encrypt->decode($link);
            }
            if (strlen($transaction_id) == 10) {
                $pg_type = substr($transaction_id, 0, 1);
                if ($pg_type == 'F') {
                    $response = $this->common->getSingleValue('package_transaction', 'package_transaction_id', $transaction_id);
                    $response['narrative'] = $response['payment_info'];
                } else if ($pg_type == 'T') {
                    $response = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $transaction_id);
                } else {
                    $response = $this->common->getSingleValue('xway_transaction', 'xway_transaction_id', $transaction_id);
                }
                $message = $response['narrative'];
            }
            $this->smarty->assign("msg", $message);
            $repath = $this->session->get('return_payment_url');
            $this->smarty->assign("repath", $repath);
            $this->smarty->assign("transaction_id", $transaction_id);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'secure/failed.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E09631]Error while handle payment failed Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }

    public function handleAppTransaction($notification, $result, $link)
    {
        try {
            if ($result['status'] == 'success') {
                $url = $this->view->server_name . '/patron/paymentlink/receipt/' . $link;
                $retry_link = '';
            } else {
                $url = $this->view->server_name . '/patron/paymentlink/failed/' . $link;
                $link = $this->encrypt->encode($result['payment_request_id']);
                $retry_link = $this->view->server_name . '/patron/paymentlink/view/' . $link;
            }

            $data['type'] = 'email';
            $data['email_template_name'] = 'transaction_receipt';
            if ($result['status'] == 'success') {
                $data['email_subject'] = 'Payment receipt from ' . $result['company_name'];
            } else {
                $data['email_subject'] = 'Payment failed for ' . $result['company_name'];
            }
            $data['email_id'] = $result['patron_email'];
            $data['data']['amount'] = $result['Amount'];
            $data['data']['company_name'] = $result['company_name'];
            $data['data']['transaction_status'] = $result['status'];
            $data['data']['name'] = $result['patron_name'];
            $data['data']['mobile'] = $result['patron_mobile'];
            $data['data']['email'] = $result['patron_email'];
            $data['data']['narrative'] = $result['narrative'];
            $data['data']['bank_ref_no'] = $result['TransactionID'];
            $data['data']['pay_transaction_id'] = $result['transaction_id'];
            $data['data']['merchant_email'] = $result['merchant_email'];
            $data['data']['merchant_mobile'] = $result['mobile_no'];
            $data['data']['created_date'] = $result['DateCreated'];
            $data['data']['receipt_link'] = $url;
            $data['data']['retry_link'] = $retry_link;
            if ($result['patron_email'] != '') {
                PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));
            }
            if ($result['status'] == 'success') {
                $data['email_subject'] = 'Payment receipt from ' . $result['patron_name'];
                $data['email_id'] = $result['merchant_email'];
                PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));

                $data['type'] = 'firebase';
                $data['user_id'] = $result['merchant_user_id'];
                $data['title'] = $result['patron_name'] . ' has paid';
                $amount = $this->moneyFormatIndia($result['Amount']);
                $data['message'] = 'You have received ' . $amount . ' from ' . $result['patron_name'] . '. Amount will be credited your account in 2 working days';
                PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));
            }
            if ($result['sms_available'] == 1) {
                if ($result['status'] == 'success') {
                    $shortUrl = $notification->saveShortUrl($url);
                    $this->common->updateShortURL($result['transaction_id'], $shortUrl, 2);
                    $result['transaction_short_url'] = $shortUrl;
                    $response = $notification->sendSMSReceiptMerchant($result, $result['mobile_no'], $result['sms_gateway'], 1);
                    $data = array();
                    $data['type'] = 'sms';
                    $data['sms'] = $response['sms'];
                    $data['mobile'] = $result['mobile_no'];
                    $data['sms_gateway'] = $result['sms_gateway'];
                    if ($response['sms_details'] != null) {
                        $data['sms_gateway_sg_val1'] = $response['sms_details']['sg_val1'];
                        $data['sms_gateway_sg_val2'] = $response['sms_details']['sg_val2'];
                        $data['sms_gateway_sg_val3'] = $response['sms_details']['sg_val3'];
                        $data['sms_gateway_sg_val4'] = $response['sms_details']['sg_val4'];
                        $data['sms_gateway_url'] = $response['sms_details']['req_url'];
                    }
                    PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));
                    if ($result['patron_mobile'] != '') {
                        $response = $notification->sendSMSReceiptCustomer($result, $result['patron_mobile'], $result['sms_gateway'], 1);
                        $data['sms'] = $response['sms'];
                        $data['mobile'] = $result['patron_mobile'];
                        PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));
                    }
                } else {
                    if ($result['patron_mobile'] != '') {
                        $message = $this->model->fetchMessage('p4');
                        $shortUrl = $notification->saveShortUrl($retry_link);
                        $message = str_replace('<Merchant Name>', $result['company_name'], $message);
                        $message = str_replace('<URL>', $shortUrl, $message);
                        $message = str_replace('xxxx', $result['Amount'] . '/-', $message);
                        $response = $notification->sendSMSReceiptCustomer($result, $result['patron_mobile'], $result['sms_gateway'], 1);
                        $data = array();
                        $data['type'] = 'sms';
                        $data['sms_gateway'] = $result['sms_gateway'];
                        $data['sms'] = $message;
                        $data['mobile'] = $result['patron_mobile'];
                        if ($response['sms_details'] != null) {
                            $data['sms_gateway_sg_val1'] = $response['sms_details']['sg_val1'];
                            $data['sms_gateway_sg_val2'] = $response['sms_details']['sg_val2'];
                            $data['sms_gateway_sg_val3'] = $response['sms_details']['sg_val3'];
                            $data['sms_gateway_sg_val4'] = $response['sms_details']['sg_val4'];
                            $data['sms_gateway_url'] = $response['sms_details']['req_url'];
                        }
                        PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));
                    }
                }
            }
            header('Location:' . $url);
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, __METHOD__ . ' Error: ' . $e->getMessage());
        }
    }

    public function handleResponse($result, $pg_type)
    {
        require_once CONTROLLER . 'Notification.php';
        $notification = new Notification();
        if (substr($result['transaction_id'], 0, 1) == 'F') {
            $package_transaction = $this->common->getSingleValue('package_transaction', 'package_transaction_id', $result['MerchantRefNo']);
            if ($result['status'] == 'success') {
                $this->session->remove('package_expire');
                $package_det = $this->common->getSingleValue('package_transaction_details', 'package_transaction_id', $result['MerchantRefNo']);
                $merchant_plan = $this->common->getRowValue('merchant_plan', 'merchant', 'merchant_id', $result['merchant_id']);
                $this->session->set('merchant_plan', $merchant_plan);

                require_once CONTROLLER . 'InvoiceWrapper.php';
                $wrapper = new InvoiceWrapper($this->common);
                $inv_details = $wrapper->getPackageInvoiceDetails($result['transaction_id']);

                $file = ($inv_details['file_name'] != '') ? $inv_details['file_name'] : null;
                $this->sendMailPackageReceipt($result['company_name'], $package_det['email'], $package_det['mobile'], $package_transaction['narrative'], $package_transaction['discount'], $result['TransactionID'], $result['MerchantRefNo'], $result['DateCreated'], $result['Amount'], $result['payment_mode'], $file);
                $result['discount'] = $package_transaction['discount'];
                $this->session->remove('return_payment_url');
                header('Location:/merchant/package/success/' . $this->encrypt->encode($result['transaction_id']));
            } else {
                $this->setPaymentFailedError($result['MerchantRefNo']);
            }
        } else {
            $merchantMobiles[] = $result['mobile_no'];
            $patronMobile = $result['patron_mobile'];
            $result['customer_mobile_no'] = $patronMobile;
            $transaction_link = $this->encrypt->encode($result['transaction_id']);

            if ($result['request_type'] == 2) {
                $this->handleAppTransaction($notification, $result, $transaction_link);
            }
            if ($result['status'] == 'success') {
                SwipezLogger::info(__CLASS__, 'Transaction success Transaction id: ' . $result['transaction_id'] . 'Payment request id:' . $result['payment_request_id']);
                $plugin = json_decode($result['plugin_value'], 1);
                if ($plugin['franchise_notify_sms'] == 1 && $result['franchise_mobile'] != '') {
                    $merchantMobiles[] = $result['franchise_mobile'];
                }
                if ($plugin['franchise_notify_email'] != 1) {
                    $result['franchise_email'] = '';
                }
                $notification->franchise_email = $result['franchise_email'];

                $notification->sendMailReceipt($result['transaction_id'], 1);
                if ($plugin['has_supplier'] == 1) {
                    $supplierlist = $this->common->getInvoiceSupplierlist($plugin['suppliers']);
                    foreach ($supplierlist as $supplier) {
                        $merchantMobiles[] = $supplier['mobile1'];
                        $merchantMobiles[] = $supplier['mobile2'];
                        $this->sendMailReceipt($result, $pg_type);
                    }
                }
                if ($pg_type == 'booking') {
                    $booking_details = $this->common->getBookingDetails($result['transaction_id']);
                    $cal_detail = $this->common->getSingleValue('booking_calendars', 'calendar_id', $booking_details[0]['calendar_id']);
                    if ($cal_detail['notification_mobile'] != '') {
                        $booking_mobiles = explode(',', $cal_detail['notification_mobile']);
                        foreach ($booking_mobiles as $mob) {
                            $merchantMobiles[] = $mob;
                        }
                    }
                }
                #merchant SMS notification
                foreach ($merchantMobiles as $mobile) {
                    $notification->sendSMSReceiptMerchant($result, $mobile, $result['sms_gateway']);
                }


                $long_url = $this->view->server_name . '/patron/transaction/receipt/' . $transaction_link;
                $shortUrl = $notification->saveShortUrl($long_url);
                $this->common->updateShortURL($result['transaction_id'], $shortUrl, 2);
                $result['transaction_short_url'] = $shortUrl;

                #customer SMS notification
                $notification->sendSMSReceiptCustomer($result, $patronMobile, $result['sms_gateway']);

                $result['pg_type'] = $pg_type;
                $this->handleWebhook($result);
                $this->session->remove('return_payment_url');
                $this->session->remove('event_payment_post');
                $this->session->remove('req_confirm_post');
                $this->session->remove('coupon_id');
                header('Location:/patron/transaction/receipt/' . $transaction_link . '/success');
            } else {
                SwipezLogger::info(__CLASS__, 'Transaction failed Transaction id: ' . $result['transaction_id'] . 'Payment request id:' . $result['payment_request_id']);
                $payment_request_id = $this->encrypt->encode($result['payment_request_id']);
                if ($result['short_url'] == '' && $result['payment_request_id'] != '') {
                    $long_url = $this->view->server_name . '/patron/paymentrequest/pay/' . $payment_request_id;
                    $shortUrl = $notification->saveShortUrl($long_url);
                    $this->common->updateShortURL($result['payment_request_id'], $shortUrl);
                    $result['short_url'] = $shortUrl;
                }

                $message = $this->model->fetchMessage('p4');
                $message = str_replace('<Merchant Name>', $result['sms_name'], $message);
                $message = str_replace('<URL>', $result['short_url'], $message);
                $message = str_replace('xxxx', $result['Amount'] . '/-', $message);
                $response = $this->model->sendSMS($this->session->get('userid'), $message, $patronMobile, $result['merchant_id'], $result['sms_gateway_type'], $sg_details);
                SwipezLogger::info(__CLASS__, 'SMS Sending to Patron Mobile: ' . $patronMobile . ' Response:' . $response);

                $repath = $this->session->get('return_payment_url');
                if (isset($repath)) {
                    $this->session->remove('return_payment_url');
                } else {
                    if ($pg_type == 'event') {
                        $repath = '/patron/event/pay/' . $payment_request_id;
                    } else {
                        $repath = '/patron/paymentrequest/pay/' . $payment_request_id;
                    }
                }
                $this->session->set('return_payment_url', $repath);
                $this->setPaymentFailedError($result['transaction_id']);
            }
        }
    }

    public function sendMailReceipt($response, $pg_type, $file = null, $custom_receipt = null, $custom_subject = null)
    {
        try {
            $coupon_id = 0;
            $patron_email = $response['BillingEmail'];
            $merchant_email = $response['merchant_email'];
            $merchant_user_id = $response['merchant_user_id'];
            $payment_transaction_id = $response['MerchantRefNo'];
            $patron_name = $response['BillingName'];
            if ($response['main_company_name'] != '') {
                $payment_towards = $response['main_company_name'];
            } else {
                $payment_towards = $response['merchant_name'];
            }

            $this->smarty->assign("response", $response);
            if ($response['image'] != '') {
                $logo = $this->app_url . "/uploads/images/logos/" . $response['image'];
            } elseif ($response['merchant_logo'] != '') {
                $logo = $this->app_url . "/uploads/images/landing/" . $response['merchant_logo'];
            }
            if ($pg_type == 'event') {
                $info = $this->common->getPaymentRequestDetails($response['payment_request_id'], NULL, 2);
                $attendee_details = $this->common->getAttendeeDetails($payment_transaction_id);
                $customer_details = $this->common->getCustomerValueDetail($response['customer_id']);
                $this->smarty->assign("payee_capture", json_decode($info['payee_capture'], 1));
                $this->smarty->assign("attendees_capture", json_decode($info['attendees_capture'], 1));
                $this->smarty->assign("customer_details", $customer_details);
                $this->smarty->assign("info", $info);
                $this->smarty->assign("host", $this->view->server_name);
                $this->smarty->assign("event_link", $this->view->server_name . '/patron/event/view/' . $this->encrypt->encode($response['payment_request_id']));
                $this->smarty->assign("receipt_link", $this->view->server_name . '/patron/transaction/receipt/' . $this->encrypt->encode($payment_transaction_id));

                foreach ($attendee_details as $det) {
                    if ($det['coupon_code'] != '') {
                        $coupon_id = $det['coupon_code'];
                    }
                }
                if ($coupon_id > 0) {
                    $coupon_details = $this->common->getSingleValue('coupon', 'coupon_id', $coupon_id);
                }
            } elseif ($pg_type == 'booking') {
                $booking_details = $this->common->getBookingDetails($payment_transaction_id);
            }
            $this->smarty->assign("pg_type", $pg_type);
            $this->smarty->assign("logo", $logo);
            $this->smarty->assign("coupon_code", $coupon_details['coupon_code']);
            $this->smarty->assign("attendee_details", $attendee_details);
            $this->smarty->assign("booking_details", $booking_details);
            if ($response['is_offline'] == 1) {
                $message = $this->smarty->fetch(VIEW . 'mailer/offline_receipt.tpl');
                $pg_type = 'directpay';
            } else {
                if ($pg_type == 'event') {
                    $message = $this->smarty->fetch(VIEW . 'mailer/event_receipt.tpl');
                } else {
                    $message = $this->smarty->fetch(VIEW . 'mailer/receipt.tpl');
                }
            }

            if ($custom_receipt != null) {
                $message = $this->smarty->fetch(VIEW . 'mailer/' . $custom_receipt . '.tpl');
            }
            $emailWrapper = new EmailWrapper();

            if ($payment_towards != NULL) {
                $emailWrapper->from_name_ = $payment_towards;
            }

            if ($response['from_email'] != '') {
                $emailWrapper->from_email_ = $response['from_email'];
            }

            $emailWrapper->merchant_email_ = $response['merchant_email'];
            if ($pg_type == 'event') {
                $subject = 'Your booking is confirmed!';
            } else {
                $subject = 'Payment receipt from ' . $payment_towards;
            }
            if ($file != null) {
                $emailWrapper->attachment = $file;
            }
            if ($custom_subject != null) {
                $subject = $custom_subject;
            }
            $emailWrapper->sendMail($patron_email, "", $subject, $message);
            $subject = 'Payment receipt from ' . $patron_name;
            $message = str_replace('Your', 'Customer', $message);
            $message = str_replace('your', 'customer', $message);
            $message = str_replace('" id="impinfo"', ' display:none;" id="impinfo"', $message);
            $emailWrapper->from_name_ = 'Swipez';
            $emailWrapper->from_email_ = 'support@swipez.in';
            $emailWrapper->merchant_email_ = $patron_email;
            $is_email = 1;
            if ($pg_type != 'directpay') {
                $result = $this->model->getpreferences($merchant_user_id);
                if (!empty($result)) {
                    if ($result['send_email'] == 0) {
                        $is_email = 0;
                    }
                }
            }
            if ($is_email == 1) {
                if ($file != null) {
                    $emailWrapper->attachment = $file;
                }
                $emailWrapper->sendMail($merchant_email, "", $subject, $message);
                if ($response['franchise_email'] != '') {
                    $emailWrapper->sendMail($response['franchise_email'], "", $subject, $message);
                }
            }
            if ($file != null) {
                unlink($file);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E096]Error while payment response Error: ' . $e->getMessage());
        }
    }

    public function sendMailPackageReceipt($merchant_name, $merchant_email, $mobile, $narrative, $discount, $receipt_no, $transaction_no, $payment_date, $payment_amount, $payment_mode, $file = null)
    {
        try {
            $emailWrapper = new EmailWrapper();
            $mailcontents = $emailWrapper->fetchMailBody("merchant.package_receipt");
            if ($discount > 0) {
                $discount = '<tr>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px; width: 40%;">Coupon Discount (Rs)</td>
                        <td style="border: 1px solid #e2e2e2;border-top: 0px;border-left: 0px;">' . $discount . '/- &nbsp;</td>
                    </tr>';
            } else {
                $discount = '';
            }
            if (isset($mailcontents[0]) && isset($mailcontents[1])) {
                $message = $mailcontents[0];
                $message = str_replace('__MERCHANT_NAME__', $merchant_name, $message);
                $message = str_replace('__MERCHANT_EMAIL__', $merchant_email, $message);
                $message = str_replace('__RECEIPT_NO__', $receipt_no, $message);
                $message = str_replace('__TRANSACTION_NO__', $transaction_no, $message);
                $message = str_replace('__PAYMENT_DATE__', $payment_date, $message);
                $message = str_replace('__PAYMENT_AMOUNT__', $payment_amount, $message);
                $message = str_replace('__DISCOUNT__', $discount, $message);
                $message = str_replace('__PAYMENT_MODE__', $payment_mode, $message);

                if ($merchant_name != NULL) {
                    $emailWrapper->from_name_ = $merchant_name;
                }

                if ($file != null) {
                    $emailWrapper->attachment = $file;
                }

                #($toEmail_, $toName_, $subject_, $messageHTML_, $messageText_ = NULL)
                $emailWrapper->sendMail($merchant_email, "", $mailcontents[1], $message);
            } else {
                SwipezLogger::warn(__CLASS__, "Mail could not be sent with verify email link to : " . $merchant_email);
            }
            if ($this->env == 'PROD') {
                $subject = "Licences bought";
                $body_message = "Name: " . $merchant_name . "<br> Email: " . $merchant_email . "<br>" . "Mobile: " . $mobile . "<br>" . "Transaction ID: " . $transaction_no . "<br>" . "Package name: " . $narrative;
                SupportTeamNotification::dispatch($subject, $body_message, 'SUPPORT_PLUS')->onQueue(env('SQS_MERCHANT_REGISTRATION_NOTIFICATION_QUEUE'));
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E2012]Error while sending mail Error: email link to : ' . $toEmail_ . $e->getMessage());
        }
    }

    function handleWebhook($result)
    {
        try {
            if ($result['webhook_id'] > 0) {
                $det = $this->common->getSingleValue('merchant_webhook', 'webhook_id', $result['webhook_id']);
                $salt = $this->common->getRowValue('salt_key', 'merchant_setting', 'merchant_id', $det['merchant_id']);
                $hash = $salt . "|" . $result['Amount'] . "|" . $result['transaction_id'] . "|" . $result['customer_code'];
                $checksum = md5($hash);
                $response['checksum'] = $checksum;
                $response['invoice_id'] = $result['payment_request_id'];
                $response['transaction_id'] = $result['transaction_id'];
                $response['bank_ref'] = $result['TransactionID'];
                $response['status'] = $result['status'];
                $response['date'] = $result['DateCreated'];
                $response['amount'] = $result['Amount'];
                $response['payment_mode'] = $result['payment_mode'];
                $response['customer_code'] = $result['customer_code'];
                $response['customer_name'] = $result['BillingName'];
                $response['email'] = $result['BillingEmail'];
                SwipezLogger::info(__CLASS__, 'Webhook response Redirected successfully : Transaction id: ' . $result['transaction_id']);
                if ($result['status'] == 'success') {
                    $this->view->post_url = $det['success_url'];
                } else {
                    $this->view->post_url = $det['failed_url'];
                }
                $this->session->remove('return_payment_url');
                $this->view->post = $response;
                $this->view->render('secure/redirect');
                die();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E2012+98]Webhook response Redirected : Data ' . json_encode($result));
        }
    }

    function paymentError()
    {
        $repath = $this->session->get('return_payment_url');
        $this->session->remove('return_payment_url');
        if (isset($repath)) {
            $this->setError("Payment failed", 'Your payment attempt has failed. Please click <a href="' . $repath . '" >here</a> to re-try this payment');
            header('Location:/error');
            exit();
        } else {
            $this->setPaymentFailedError();
        }
    }


    function payoneerwebhook()
    {
        $data = file_get_contents('php://input');
        SwipezLogger::error(__CLASS__, $data);
        $response = json_decode($data, 1);
        $det = $this->model->getTransactionDetail($response['Client Reference Id']);
        $pg_details = $det['pg'];
        $api_url = $pg_details['pg_val5'] . '/v2/programs/' . $pg_details['pg_val2'] . '/payment-requests/' . $det['transaction_id'] . '/status';
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
        $response = json_decode($response, 1);
        if (substr($det['transaction_id'], 0, 1) == 'T') {
            $result = $this->model->savePayoneerResponse($response['audit_id'], $det['transaction_id'], $response['payment']['total_amount_charged'], $response['payment']['payment_method']);
            $result['type'] = 'request';
            $result['amount'] = $response['payment']['total_amount_charged'];
            $this->handleResponse($result, 'request');
            die();
        } else {
            require_once MODEL . 'XwayModel.php';
            $xwayModel = new XwayModel();
            $xwayModel->savePaymentResponse($response, $det['name'], $det['email'], date('Y-m-d'), $det['transaction_id'], $response['audit_id'], $response['audit_id'], $response['payment']['total_amount_charged'], $response['payment']['payment_method'], '', 'success', 'Guest');
        }
    }

    function cashfreewebhook()
    {
        try {
            $transaction_id = $_POST['orderId'];
            if (strlen($transaction_id) == 10) {
                require_once UTIL . 'ReconcileWrapper.php';
                $reconcile = new ReconcileWrapper();
                $reconcile->transactionIds = "'" . $transaction_id . "'";
                $transaction_rows = $reconcile->fetchSwipezDataFromDB();
                if (!empty($transaction_rows)) {
                    $reconcile->ReconcileIncompleteTransaction($transaction_rows, 'Swipez');
                    die();
                }
                $xway_rows = $reconcile->fetchXwayDataFromDB();
                if (!empty($xway_rows)) {
                    $reconcile->ReconcileIncompleteTransaction($xway_rows, 'Xway');
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E2012+98]Webhook response Redirected : Data ' . json_encode($result));
        }
    }

    function easebuzzresponse()
    {
        try {
            $pg_transaction_id = $_POST['txnid'];
            SwipezLogger::info(__CLASS__, 'Easebuzz payment response ' . json_encode($_POST));
            $pg_type = $this->session->get('transaction_type');
            $this->view->render('secure/loader');
            if (strlen($pg_transaction_id) == 10) {
                $transaction_detail = $this->common->getSingleValue('payment_transaction', 'pay_transaction_id', $pg_transaction_id);
                $transaction_status = $transaction_detail['payment_transaction_status'];
                if ($transaction_status == 1) {
                    header('Location:/patron/transaction/receipt/' . $this->encrypt->encode($pg_transaction_id));
                    exit();
                } else {
                    $patron_name = $this->session->get('patron_name');
                    $wrap = new PaymentWrapperController();
                    $email = $this->session->get('email');
                    if ($this->request_type == 1) {
                        $pg_details = $this->common->getSingleValue('payment_gateway', 'pg_id', $transaction_detail['pg_id']);
                        $easebuzzObj = new Easebuzz($pg_details['pg_val1'], $pg_details['pg_val2'], $pg_details['pg_val3']);
                        $result = $easebuzzObj->easebuzzResponse($_POST);
                        $response = json_decode($result, true);
                        if ($response['status'] == 1) {
                            $isValidChecksum = TRUE;
                        } else {
                            $isValidChecksum = FALSE;
                        }
                        //$isValidChecksum = $wrap->validateCashfree($pg_transaction_id, $_POST);
                    }
                    // else {
                    //     $isValidChecksum = TRUE;
                    // }

                    if ($isValidChecksum === TRUE) {
                    } else {
                        SwipezLogger::error(__CLASS__, '[E514]Error while Easebuzz response Error: checksum mismatch ' . json_encode($_POST));
                        Sentry\captureMessage('Easebuzz response Error: checksum mismatch : ' . json_encode($_POST));
                        $this->setPaymentFailedError($pg_transaction_id);
                    }
                    $result = $this->model->handleEasebuzzGatewayResponse($patron_name, $email, $_POST, $this->session->get('userid'), $pg_type, $pg_transaction_id);
                    $result['pg_type'] = $pg_type;
                    $this->handleResponse($result, $pg_type);
                }
            } else {
                $this->setPaymentFailedError($pg_transaction_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E510+5]Error while easebuzz response Error: ' . $e->getMessage());
            $this->paymentError();
        }
    }
}
