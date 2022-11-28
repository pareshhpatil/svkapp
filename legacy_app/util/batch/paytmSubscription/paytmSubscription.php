<?php

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
include('../config.php');
require_once(UTIL . "encdec_paytm2.php");
require_once MODEL . 'CommonModel.php';
require_once UTIL . 'SMS/SMSMessage.php';

class PaytmSubscription extends Batch {

    public $logger = NULL;
    public $messagelist = NULL;
    public $common = NULL;

    function __construct() {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->renewSubscription();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    function renewSubscription() {
        try {
            require_once MODEL . 'patron/PaymentrequestModel.php';
            $req_model = new PaymentRequestModel();
            require_once MODEL . 'SecureModel.php';
            $secureModel = new SecureModel();
            $common = new CommonModel();
            $this->logger->info(__CLASS__, 'Renew Subscription initiate');
            $subscriptionlist = $common->getPaytmSubscriptionList();
            $this->logger->info(__CLASS__, 'Subscription count ' . count($subscriptionlist));
            foreach ($subscriptionlist as $subscription) {
                try {
                    $pending_bills = array();
                    $customer_id = $subscription['customer_id'];
                    $subs_id = $subscription['paytm_subscription_id'];
                    $start_date = $subscription['start_date'];
                    $pending_bills = $common->getCustomerPendingBill($customer_id, $start_date);
                    $this->logger->info(__CLASS__, 'Subscription Bills for customer_id' . $customer_id . ' Sub ID: ' . $subs_id . ' Count: ' . count($pending_bills));
                    foreach ($pending_bills as $request) {
                        #get Surcharge Amount
                        $surcharge_amount = $common->getSurcharge($request['merchant_id'], $request['absolute_cost'], $request['fee_id']);
                        $request['absolute_cost'] = $request['absolute_cost'] + $surcharge_amount;
                        #update payment request status as initiate
                        $req_model->updatePaymentStatus($request['customer_id'], 1);
                        #save Transaction and get transaction id
                        $this->logger->info(__CLASS__, 'Renew subscription initiated for request ID :' . $request['payment_request_id']);
                        $transaction_id = $req_model->intiatePaymentTransaction($request['payment_request_id'], $request['payment_request_type'], $request['customer_id'], $request['customer_id'], $request['merchant_user_id'],$request['merchant_id'], $request['absolute_cost'], $surcharge_amount, 0, 0, '', $request['pg_id'], $request['fee_id'],$request['franchise_id'],$request['vendor_id']);
                        $pg_response = $this->paytmRenewRequest($request, $transaction_id, $subs_id);
                        $this->logger->info(__CLASS__, 'PG Response :' . json_encode($pg_response));
                        if (isset($pg_response['STATUS'])) {
                            $pg_response['merchant_id'] = $request['merchant_id'];
                            $pg_response['customer_id'] = $customer_id;
                            $this->saveRenewTransaction($pg_response);
                            if ($pg_response['STATUS'] == 'TXN_ACCEPTED') {
                                $pg_response['STATUS'] = 'TXN_SUCCESS';
                                $result = $secureModel->handlePaytmGatewayResponse($request['customer_name'], $request['email'], $request['mid'], $pg_response['ORDERID'], $pg_response['TXNAMOUNT'], 'INR', $pg_response['TXNID'], $pg_response['TXNID'], $pg_response['STATUS'], $pg_response['RESPCODE'], $pg_response['RESPMSG'], date("Y-m-d h:i:s"), 'WALLET', '', 'PPI', '', $request['customer_id'], 'payment', $transaction_id);
                                $result['mobile'] = $request['mobile'];
                                $this->notification($result, 'payment');
                            }
                        } else {
                            
$this->logger->error(__CLASS__, '[PR00]paytm Invalid Response');
                        }
                    }
                } catch (Exception $e) {
Sentry\captureException($e);
                    
$this->logger->error(__CLASS__, '[PR01]Error while get sending subscription Error: ' . $e->getMessage());
                }
            }
            // $this->sending($payment_request_id, $due_date);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PR02]Error while get sending subscription Error: ' . $e->getMessage());
        }
    }

    function paytmRenewRequest($request, $transaction_id, $subs_id) {
        try {
            $checkSum = "";
            $post_data = array();
            $post_data["REQUEST_TYPE"] = 'RENEW_SUBSCRIPTION';
            $post_data['MID'] = $request['mid'];
            $post_data['ORDER_ID'] = $transaction_id;
            //$post_data['CUST_ID'] = $request['customer_id'];
            $post_data['TXN_AMOUNT'] = $request['absolute_cost'];
            $post_data['SUBS_ID'] = $subs_id;

            $checkSum = getChecksumFromArray($post_data, $request['paytm_key']);
            $post_data["CHECKSUMHASH"] = urlencode($checkSum);

            foreach ($post_data as $key => $value) {
                $post_items[] = $key . '=' . $value;
            }
            //create the final string to be posted using implode()
            $post_string = implode('&', $post_items);
            /**
             * curl function start here
             */
            $url = $request['txn_url'];
            $ch = curl_init() or die(curl_error($ch));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if ($this->host == 'https') {
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $response = curl_exec($ch) or die(curl_error($ch));
            curl_close($ch);
            return json_decode($response, true);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PR03]Error while renew paytm subscription Error: ' . $e->getMessage());
        }
    }

    public function saveRenewTransaction($response) {
        try {
            $sql = "INSERT INTO `paytm_renew_transaction`(`TXNID`,`ORDERID`,`customer_id`,`merchant_id`,`TXNAMOUNT`,`SUBS_ID`,`STATUS`,`RESPCODE`,`RESPMSG`)VALUES(:TXNID,:ORDERID,:customer_id,:merchant_id,:TXNAMOUNT,:SUBS_ID,:STATUS,:RESPCODE,:RESPMSG);";
            $params = array(':TXNID' => $response['TXNID'], ':ORDERID' => $response['ORDERID'], ':customer_id' => $response['customer_id'], ':merchant_id' => $response['merchant_id'], ':TXNAMOUNT' => $response['TXNAMOUNT'], ':SUBS_ID' => $response['SUBS_ID'], ':STATUS' => $response['STATUS'], ':RESPCODE' => $response['RESPCODE'], ':RESPMSG' => $response['RESPMSG']);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PR04]Error while update bulk upload status Error: ' . $e->getMessage());
        }
    }

    private function notification($result, $pg_type) {
        $common = new CommonModel();
        $sg_details = NULL;
        if ($result['sms_gateway'] > 1) {
            $sg_details = $common->getSingleValue('sms_gateway', 'sg_id', $result['sms_gateway']);
        }
        //$messagelist = new SMSMessage();
        $merchantMobile = $result['mobile_no'];
        //$message = $messagelist->fetchMessage('m7');
        $message = 'You have received xxxx amount from <Patron Name>. Amount will be credited your account in 2-3 business days, subject to clearing.';
        $message = str_replace('<Patron Name>', $result['BillingName'] . ' (' . $result['customer_code'] . ')', $message);
        $message = str_replace('xxxx', $result['Amount'], $message);
        $response = $this->sendSMS($result['merchant_user_id'], $message, $merchantMobile, $result['merchant_id'], $result['sms_gateway_type'], $sg_details);
        $this->logger->info(__CLASS__, 'SMS Sending to Merchant Mobile: ' . $merchantMobile . ' Response:' . $response);
        $this->sendMailReceipt($result, $pg_type);
        //send message to patron for successfull payment 
        // $message = $messagelist->fetchMessage('p3');
        $message = 'You have made a payment to <Merchant Name> for an amount of xxxx/-. Transaction ref id is <Transaction id>. Merchant credits subject to clearing';
        $message = str_replace('<Merchant Name>', $result['sms_name'], $message);
        $message = str_replace('xxxx', $result['Amount'], $message);
        $message = str_replace('<Transaction id>', $result['transaction_id'], $message);
        $response = $this->sendSMS(NULL, $message, $result['mobile'], $result['merchant_id'], $result['sms_gateway_type'], $sg_details);
        $this->logger->info(__CLASS__, 'SMS Sending to Patron Mobile: ' . $result['mobile'] . ' Response:' . $response);
    }

    public function sendMailReceipt($response) {
        try {
            $smarty = new Smarty();
            $smarty->setCompileDir(SMARTY_FOLDER);
            $patron_email = $response['BillingEmail'];
            $merchant_email = $response['merchant_email'];
            $merchant_user_id = $response['merchant_user_id'];
            $patron_name = $response['BillingName'];
            $payment_towards = $response['merchant_name'];
            $smarty->assign("response", $response);
            $merchant_domain = $response['merchant_domain'];
            if ($response['image'] != '') {
                $logo = $merchant_domain . "/uploads/images/logos/" . $response['image'];
            } elseif ($response['merchant_logo'] != '') {
                $logo = $merchant_domain . "/uploads/images/landing/" . $response["merchant_logo"];
            }
            $pg_type = 'payment_request';
            $smarty->assign("pg_type", $pg_type);
            $smarty->assign("logo", $logo);
            $smarty->assign("coupon_code", '');
            $message = $smarty->fetch(VIEW . 'mailer/receipt.tpl');

            $emailWrapper = new EmailWrapper();
            if ($payment_towards != NULL) {
                $emailWrapper->from_name_ = $payment_towards;
            }

            if ($response['from_email'] != '') {
                $emailWrapper->from_email_ = $response['from_email'];
            }

            $emailWrapper->merchant_email_ = $response['merchant_email'];

            $subject = 'Payment receipt from ' . $payment_towards;
            $emailWrapper->sendMail($patron_email, "", $subject, $message);
            $subject = 'Payment receipt from ' . $patron_name;
            $message = str_replace('Your', 'Customer', $message);
            $message = str_replace('your', 'customer', $message);
            $emailWrapper->from_name_ = 'Swipez';
            $emailWrapper->from_email_ = 'support@swipez.in';
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
                $emailWrapper->sendMail($merchant_email, "", $subject, $message);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PR05]Error while payment response Error: ' . $e->getMessage());
        }
    }

}

new PaytmSubscription();
?>