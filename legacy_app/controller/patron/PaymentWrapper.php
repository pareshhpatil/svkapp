<?php

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redis;

class PaymentWrapper
{

    public function paymentProcced($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address, $user_id, $model, $view, $encrypt = null)
    {
        $name = $first_name . ' ' . $last_name;
        if ($pg_details['pg_type'] == 3) {
            $this->InvokePAYUGateway($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address);
        } else if ($pg_details['pg_type'] == 1) {
            $this->InvokeEBSGateway($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address);
        } else if ($pg_details['pg_type'] == 2) {
            $this->InvokePAYTMGateway('payment', $transaction_id, $user_detail, $pg_details, $email, $mobile, $first_name, $last_name);
        } else if ($pg_details['pg_type'] == 4) {
            $this->InvokeATOMGateway($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $address);
        } else if ($pg_details['pg_type'] == 5) {
            $start_date = date('Y-m-d H:i:s');
            $date = strtotime($start_date . ' 12 month');
            $expiry_date = date('Y-m-d H:i:s', $date);
            $grace_days = 15;
            $subs_id = $model->savePaytmSubscription($transaction_id, $user_detail['customer_id'], $user_id, $user_detail['@company_merchant_id'], $user_detail['@absolute_cost'], 'MONTH', 1, $expiry_date, $pg_details['pg_val7'], $pg_details['pg_id'], $pg_details['fee_detail_id'], $start_date, $grace_days);
            $this->InvokePAYTMSubscription('payment', $transaction_id, $subs_id, $user_detail, $pg_details, $email, $mobile, $first_name, $last_name, $start_date, $expiry_date);
        } else if ($pg_details['pg_type'] == 6) {
            $merchant_logo = $model->getRowValue('logo', 'merchant_landing', 'merchant_id', $user_detail['@company_merchant_id']);
            $user_detail['logo'] = $merchant_logo;
            $user_detail['host'] = $view->server_name;
            $this->InvokeTECHPROCESSGateway('payment', $transaction_id, $user_detail, $pg_details, $email, $mobile, $first_name, $last_name);
        } else if ($pg_details['pg_type'] == 7 || $pg_details['pg_type'] == 9) {
            $merchant_logo = $model->getRowValue('logo', 'merchant_landing', 'merchant_id', $user_detail['@company_merchant_id']);
            $view->merchant_company_name = $user_detail['@company_name'];
            if ($merchant_logo != '') {
                $view->merchant_logo = '/uploads/images/landing/' . $merchant_logo;
            }
            $this->InvokeCashfreeGatewayOld($transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $view);
        } else if ($pg_details['pg_type'] == 8) {
            $result = $this->InvokePaypalGateway('payment', $transaction_id, $user_detail, $pg_details, $first_name, $last_name, $email, $mobile, $view);
            $model->genericupdate('payment_transaction', 'pg_ref_no', $result['id'], 'pay_transaction_id', $transaction_id);
            SwipezLogger::debug(__CLASS__, 'Paypal request JSON: ' . json_encode($result));
            echo json_encode($result);
            die();
        } else if ($pg_details['pg_type'] == 10) {
            $smarty = new Smarty();
            $smarty->setCompileDir(SMARTY_FOLDER);
            $post_data = $this->InvokeRazorPayGateway($transaction_id, $user_detail, $pg_details, $name, $email, $mobile);
            switch (substr($transaction_id, 0, 1)) {
                case 'T':
                    $model->genericupdate('payment_transaction', 'pg_ref_no', $post_data['order_id'], 'pay_transaction_id', $transaction_id);
                    break;
                case 'F':
                    $model->genericupdate('package_transaction', 'pg_ref_no', $post_data['order_id'], 'package_transaction_id', $transaction_id);
                    break;
                default:
                    $model->genericupdate('xway_transaction', 'pg_ref_no1', $post_data['order_id'], 'xway_transaction_id', $transaction_id);
                    break;
            }
            if ($view->global_tag != '') {
                $smarty->assign('global_tag', $view->global_tag);
            }
            $smarty->assign('data', $post_data);
            $smarty->assign('json_data', json_encode($post_data['data']));
            $smarty->display(VIEW . 'secure/razorpay.tpl');
            die();
        } else if ($pg_details['pg_type'] == 11) {
            return $this->InvokeStripeGateway($transaction_id, $user_detail, $pg_details, $view, $user_id);
        } else if ($pg_details['pg_type'] == 12) {
            return $this->InvokePayoneerGateway($transaction_id, $user_detail, $pg_details, $view, $user_id);
        } else if ($pg_details['pg_type'] == 13) {
            return $this->InvokeSetuGateway($transaction_id, $user_detail, $pg_details, $view, $user_id, $encrypt);
        }
    }

    public function InvokeSetuGateway($transaction_id, $user_detail, $pg_details, $view, $user_id, $encrypt)
    {
        try {
            $hash = $encrypt->encode($user_detail["xtransaction_id"]);
            $token = $this->setuGenerateToken($pg_details);
            $curl = curl_init();
            // $payload = [];
            // $payload["billerBillID"] = $user_detail["xtransaction_id"];
            // $payload = json_encode( $payload );
            

            $payload = '{
                "billerBillID"    : "' . $user_detail["xtransaction_id"] . '",
                "amount": {
                    "value"        : ' . $user_detail["@absolute_cost"] . '00' . ',
                    "currencyCode" : "' . $user_detail["currency"] . '"
                },
                "amountExactness" : "EXACT", 
                "name" : "payment for ' . $user_detail["@company_name"] . '", 
                "transactionNote": "' . $user_detail["@patron_email"] . " - " . $user_detail["@patron_mobile_no"] . '", 
                "additionalInfo" : {
                    "UUID":"' . $user_detail["xtransaction_id"] . '"
                }
            } ';

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://uat.setu.co/api/v2/payment-links',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $payload,
                CURLOPT_HTTPHEADER => array(
                    'X-Setu-Product-Instance-ID: ' . $pg_details["pg_val6"],
                    'authorization: Bearer ' . $token,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $response = json_decode($response, true);
            $qr = new \chillerlan\QRCode\QRCode();

            $image_src =  $qr->render($response["data"]["paymentLink"]["upiLink"]);
            $res  = [];
            $res["img_src"] = $image_src;
            $res["platform_bill_id"] = $response["data"]["platformBillID"];
            $res["upiLink"] = $response["data"]["paymentLink"]["upiLink"];
            $res["payment_status"] = "PENDING";
            $res["hash"] = $hash;
            Redis::set($hash, json_encode($res), 'EX', 604800);
            echo json_encode($res);
            die;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokeSETUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function setuGenerateToken($pg_details){
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
    }
    public function setuCURLcall($transaction_id, $platform_bill_id, $pg_details, $view, $user_id)
    {
        
    }

    public function setuCheckReconStatus($platform_bill_id, $pg_details)
    {
        try {
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


            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://uat.setu.co/api/v2/payment-links/' . $platform_bill_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'X-Setu-Product-Instance-ID: ' . $pg_details["pg_val6"],
                    'authorization: Bearer ' . $token,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true);

            echo json_encode($response);
            die;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EPG02]Error while Invoke SETU Gateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function setuTrackTransactionStatus($hash, $platform_bill_id)
    {
        try {
            $order_hash = json_decode(Redis::get($hash), 1);
            $res  = [];
            $res["order_hash"] = $order_hash["hash"];
            $res["status"] = $order_hash["payment_status"];
            echo json_encode($res);
            die;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokeSETUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function setuSettlement($platform_bill_id, $pg_details)
    {
        try {
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


            $curl = curl_init();

            $payload = '{
                "productInstanceIDOfReport" : "1901239821389323"
                "filters" : {
                    "txnStartDate"        : "2019-02-01T03:25:29.865+05:30",
                    "txnEndDate"          : "2019-03-01T03:25:29.865+05:30",
                    "settlementStartDate" : "2019-02-02T03:25:29.865+05:30",
                    "settlementEndDate"   : "2019-03-02T03:25:29.865+05:30",
                    "billStatus"          : "SETTLEMENT_SUCCESSFUL",
                },
            }';

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://uat.setu.co/api/v2/reports',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>  $payload,
                CURLOPT_HTTPHEADER => array(
                    'X-Setu-Product-Instance-ID: ' . $pg_details["pg_val6"],
                    'authorization: Bearer ' . $token,
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $response = json_decode($response, true);

            echo json_encode($response);
            die;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EPG02]Error while Invoke SETU Gateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokeStripeGateway($transaction_id, $user_detail, $pg_details, $view, $user_id)
    {
        try {
            \Stripe\Stripe::setApiKey($pg_details['pg_val4']);
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $pg_details['status_url'] = str_replace('secure', 'xway', $pg_details['status_url']);
            }
            $session = \Stripe\Checkout\Session::create([
                //'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $user_detail['currency'],
                        'product_data' => [
                            'name' => $user_detail['@patron_first_name'] . ' ' . $user_detail['@patron_last_name'],
                            'images' => [$pg_details['pg_val5']],
                        ],
                        'unit_amount' => $user_detail['@absolute_cost'] * 100,
                    ],
                    'quantity' => 1,

                ]],
                'payment_intent_data' => [
                    //'application_fee_amount' => 1,
                    'transfer_data' => [
                        'destination' => $pg_details['pg_val1'],
                    ],
                    'metadata' => ['transaction_id' => $transaction_id]
                ],
                // 'metadata' => ['order_id' => '123'],
                'mode' => 'payment',
                'success_url' => $pg_details['status_url'],
                'cancel_url' => $pg_details['status_url'],
            ]);
            $_SESSION['payment_intent'] = $session->payment_intent;

            header('Location: ' . $session->url, true, 303);
            exit();
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePAYUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokePayoneerGateway($transaction_id, $user_detail, $pg_details, $view, $user_id)
    {
        try {
            $api_url = $pg_details['pg_val5'] . '/v2/programs/' . $pg_details['pg_val2'] . '/payment-requests';
            $secret = base64_encode($pg_details['pg_val6'] . ':' . $pg_details['pg_val7']);
            $header = array(
                "Authorization: Basic " . $secret,
                "Content-Type: application/json"
            );
            $data['description'] = 'Online payment';
            $data['currency'] = $user_detail['currency'];
            $data['amount'] =  $user_detail['@absolute_cost'];
            $data['payee_id'] =  $pg_details['pg_val1'];
            $data['client_reference_id'] =  $transaction_id;
            $data['buyer']['type'] = 'INDIVIDUAL';
            $data['buyer']['contact']['first_name'] = $user_detail['@patron_first_name'];
            $data['buyer']['contact']['last_name'] = $user_detail['@patron_last_name'];
            $data['buyer']['contact']['email'] = $user_detail['@patron_email'];
            $data['buyer']['contact']['phone'] = $user_detail['@patron_mobile_no'];
            $data['buyer']['address']['address_line_1'] = substr($user_detail['@patron_address1'], 0, 40);
            $data['buyer']['address']['address_line_2'] = substr($user_detail['@patron_address1'], 40, 40);
            $data['buyer']['address']['city'] = $user_detail['@patron_city'];
            $data['buyer']['address']['zip_code'] = $user_detail['@patron_zipcode'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER =>  $header,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $response = json_decode($response, 1);
            if (isset($response['payment_link'])) {
                header('Location: ' . $response['payment_link']);
                die();
            } else {
                throw new Exception(json_encode($response));
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePayoneerGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokePAYUGateway($transaction_id, $details, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address)
    {
        try {
            $merchant_key = isset($pg_details['pg_val1']) ? $pg_details['pg_val1'] : '';
            $salt = isset($pg_details['pg_val2']) ? $pg_details['pg_val2'] : '';
            $return_url = isset($pg_details['pg_val4']) ? $pg_details['pg_val4'] : '';
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $return_url = str_replace('secure', 'xway', $return_url);
            }
            $request_url = isset($pg_details['req_url']) ? $pg_details['req_url'] : '';
            $post_url = isset($pg_details['pg_val5']) ? $pg_details['pg_val5'] : '';
            $service_provider = isset($pg_details['pg_val6']) ? $pg_details['pg_val6'] : '';

            $first_name = ($first_name != '') ? $first_name : $details['@patron_first_name'];
            $last_name = ($last_name != '') ? $last_name : $details['@patron_last_name'];
            $address = ($address != '') ? $address : $details['@patron_address1'];
            $city = ($city != '') ? $city : $details['@patron_city'];
            $state = ($state != '') ? $state : $details['@patron_state'];
            $zip = ($zipcode != '') ? $zipcode : $details['@patron_zipcode'];
            $phone = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $info = ($details['@narrative'] != '') ? $details['@narrative'] : 'Empty Narrative';
            $amount = $details['@absolute_cost'];
            $country = 'IND';
            $merchant_id = $details['@company_merchant_id'];
            $name = $first_name . ' ' . $last_name;
            $name = $this->filterdata($name);
            $address = $this->filterdata($address);
            $city = $this->filterdata($city);
            $state = $this->filterdata($state);
            $info = $this->filterdata($info);
            $company_name = $this->filterdata($details['@company_name']);

            $hash_string = $merchant_key . "|" . $transaction_id . "|" . $amount . "|" . $info . "|" . $name . "|" . $email . "|" . $merchant_id . "|" . $company_name . "|||||||||" . $salt;
            $hash = strtolower(hash('sha512', $hash_string));


            $post_data['key'] = $merchant_key;
            $post_data['hash'] = $hash;
            $post_data['txnid'] = $transaction_id;
            $post_data['amount'] = $amount;
            $post_data['firstname'] = $name;
            $post_data['lastname'] = '';
            $post_data['email'] = $email;
            $post_data['phone'] = $phone;
            $post_data['productinfo'] = $info;
            $post_data['surl'] = $return_url;
            $post_data['furl'] = $return_url;
            $post_data['service_provider'] = $service_provider;
            $post_data['curl'] = '';
            $post_data['address1'] = $address;
            $post_data['address2'] = '';
            $post_data['city'] = $city;
            $post_data['state'] = $state;
            $post_data['country'] = $country;
            $post_data['zipcode'] = $zip;
            $post_data['udf1'] = $merchant_id;
            $post_data['udf2'] = $company_name;
            $post_data['udf3'] = $request_url;
            $post_data['udf4'] = '';
            $post_data['udf5'] = '';
            $post_data['pg'] = '';

            foreach ($post_data as $key => $value) {
                $post_items[] = $key . '=' . $value;
            }
            //create the final string to be posted using implode()
            $post_string = implode('&', $post_items);
            /**
             * curl function start here
             */
            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            if ($host == 'https') {
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $data1 = curl_exec($ch) or die(curl_error($ch));

            curl_close($ch);
            $response = $data1;
            echo $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePAYUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokeCashfreeGateway($transaction_id, $details, $pg_details, $first_name, $last_name, $email, $mobile, $view)
    {
        try {
            $app_id = isset($pg_details['pg_val1']) ? $pg_details['pg_val1'] : '';
            $secretKey = isset($pg_details['pg_val2']) ? $pg_details['pg_val2'] : '';
            $return_url = isset($pg_details['status_url']) ? $pg_details['status_url'] : '';
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $return_url = str_replace('secure', 'xway', $return_url);
            }
            $request_url = isset($pg_details['req_url']) ? $pg_details['req_url'] : '';
            $post_url = isset($pg_details['pg_val5']) ? $pg_details['pg_val5'] : '';
            $first_name = ($first_name != '') ? $first_name : $details['@patron_first_name'];
            $last_name = ($last_name != '') ? $last_name : $details['@patron_last_name'];
            $phone = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $info = ($details['@narrative'] != '') ? $details['@narrative'] : 'Empty Narrative';
            $amount = $details['@absolute_cost'];
            $vendors = "";
            if ($details['vendor_id'] > 0 || $details['franchise_id'] > 0) {
                $common = new CommonModel();
                if ($details['vendor_id'] > 0) {
                    $vendor_detail = $common->getSingleValue('vendor', 'vendor_id', $details['vendor_id']);
                } else {
                    $vendor_detail = $common->getSingleValue('franchise', 'franchise_id', $details['franchise_id']);
                }

                if ($vendor_detail['online_pg_settlement'] == 1 && $vendor_detail['settlement_type'] == 2) {
                    if ($vendor_detail['commision_type'] == 1) {
                        $comm = ',"commission":' . $vendor_detail['commission_percentage'] . '}]';
                    } else if ($vendor_detail['commision_type'] == 2) {
                        $comm = ',"commissionAmount":' . $vendor_detail['commision_amount'] . '}]';
                    } else {
                        $comm = '}]';
                    }
                    $vendors = '[{"vendorId":"' . $vendor_detail['pg_vendor_id'] . '"' . $comm;
                    $vendors = base64_encode($vendors);
                }
            }
            $name = $first_name . ' ' . $last_name;
            $name = $this->filterdata($name);
            $info = $this->filterdata($info);
            Session::put('patron_name', $name);
            Session::put('email', $email);
            if (isset($details['currency'])) {
                if ($details['currency'] != '') {
                    $currency = $details['currency'];
                } else {
                    $currency = 'INR';
                }
            } else {
                $currency = 'INR';
            }
            $post_data = array(
                "appId" => $app_id,
                "orderId" => $transaction_id,
                "orderAmount" => $amount,
                "orderCurrency" => $currency,
                "orderNote" => $info,
                "customerName" => $name,
                "customerPhone" => $phone,
                "customerEmail" => $email,
                "vendor" => $vendors,
                "returnUrl" => $return_url,
            );

            $paymentModes = "";
            $tokenData = "appId=" . $app_id . "&orderId=" . $transaction_id . "&orderAmount=" . $amount . "&returnUrl=" . $return_url . "&paymentModes=" . $paymentModes;
            $token = hash_hmac('sha256', $tokenData, $secretKey, true);
            $paymentToken = base64_encode($token);
            $post_data['signature'] = $paymentToken;
            $smarty = new Smarty();
            $smarty->setCompileDir(SMARTY_FOLDER);
            if ($pg_details['pg_val3'] == 'TEST') {
                $mode = 'TEST';
            } else {
                $mode = 'PROD';
                $partner_code = getenv('CASHFREE_PARTNER_CODE');
                if (isset($partner_code)) {
                    $post_data['pc'] = $partner_code;
                }
            }
            $smarty->assign("logo", $view->merchant_logo);
            $smarty->assign("company_name", $view->merchant_company_name);
            $smarty->assign("amount", $amount);
            $smarty->assign("data", $post_data);
            $smarty->assign("cashfreeMode", $mode);
            $view->title = "Payment";
            $view->is_secure = 1;
            $view->render('header/nonlogoheader');
            $smarty->display(VIEW . 'secure/cashfree.tpl');
            $view->render('footer/invoice');
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePAYUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokeRazorPayGateway($transaction_id, $details, $pg_details, $name, $email, $mobile)
    {
        try {
            $keyId = isset($pg_details['pg_val1']) ? $pg_details['pg_val1'] : '';
            $keySecret = isset($pg_details['pg_val2']) ? $pg_details['pg_val2'] : '';
            $post_data['return_url'] = isset($pg_details['status_url']) ? $pg_details['status_url'] : '';
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $post_data['return_url'] = str_replace('secure', 'xway', $post_data['return_url']);
            }
            $name = ($name != '') ? $name : $details['@patron_first_name'] . ' ' . $details['@patron_last_name'];
            $phone = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $info = $pg_details['pg_val4'];
            $img_url = $pg_details['pg_val6'];
            $corporate_name = $pg_details['pg_val5'];
            $amount = $details['@absolute_cost'];
            $name = $this->filterdata($name);
            $api = new Api($keyId, $keySecret);
            if ($pg_details['pg_val7'] != '') {
                $api->setHeader('x-razorpay-account', $pg_details['pg_val7']);
            }
            $orderData = [
                'receipt' => $transaction_id,
                'amount' => $amount * 100, // 2000 rupees in paise
                'currency' => 'INR',
                'payment_capture' => 1 // auto capture,
            ];

            $razorpayOrder = $api->order->create($orderData);
            $razorpayOrderId = $razorpayOrder['id'];
            $post_data['order_id'] = $razorpayOrderId;
            $post_data['transaction_id'] = $transaction_id;
            Session::put('razorpay_order_id', $razorpayOrderId);
            $displayAmount = $amount;

            $data = [
                "key" => $keyId,
                "amount" => $amount,
                "name" => $corporate_name,
                "description" => $info,
                "image" => $img_url,
                "prefill" => [
                    "name" => $name,
                    "email" => $email,
                    "contact" => $phone,
                ],
                "notes" => [
                    "address" => "",
                    "merchant_order_id" => $transaction_id,
                ],
                "theme" => [
                    "color" => "#F37254"
                ],
                "order_id" => $razorpayOrderId,
            ];

            if ($pg_details['pg_val7'] != '') {
                $data['account_id'] = $pg_details['pg_val7'];
            }
            $json = json_encode($data);
            $post_data['repath'] = $pg_details['repath'];
            $post_data['data'] = $data;
            $post_data['json'] = $json;
            $post_data['name'] = $name;
            $post_data['email'] = $email;
            $post_data['mobile'] = $mobile;
            return $post_data;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePAYUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokeCashfreeGatewayOld($transaction_id, $details, $pg_details, $first_name, $last_name, $email, $mobile)
    {
        try {
            $app_id = isset($pg_details['pg_val1']) ? $pg_details['pg_val1'] : '';
            $secretKey = isset($pg_details['pg_val2']) ? $pg_details['pg_val2'] : '';
            $notify_url = isset($pg_details['pg_val6']) ? $pg_details['pg_val6'] : '';
            $return_url = isset($pg_details['status_url']) ? $pg_details['status_url'] : '';
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $return_url = str_replace('secure', 'xway', $return_url);
            }
            $request_url = isset($pg_details['req_url']) ? $pg_details['req_url'] : '';
            $post_url = isset($pg_details['pg_val5']) ? $pg_details['pg_val5'] : '';

            $first_name = ($first_name != '') ? $first_name : $details['@patron_first_name'];
            $last_name = ($last_name != '') ? $last_name : $details['@patron_last_name'];
            $phone = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $info = ($details['@narrative'] != '') ? $details['@narrative'] : 'Empty Narrative';
            $amount = $details['@absolute_cost'];
            $vendors = "";

            // if ($details['vendor_id'] > 0 || $details['franchise_id'] > 0 || $pg_details['type'] == 2) {
            //     $common = new CommonModel();

            //     if ($details['vendor_id'] > 0) {
            //         $vendor_detail = $common->getSingleValue('vendor', 'vendor_id', $details['vendor_id']);
            //     } elseif ($details['franchise_id'] > 0) {
            //         $vendor_detail = $common->getSingleValue('franchise', 'franchise_id', $details['franchise_id']);
            //     }

            //     if ($pg_details['type'] == 2) {
            //         if (substr($transaction_id, 0, 1) == 'X') {
            //             $fdetail = $common->getSingleValue('xway_fee_detail', 'xway_id', $pg_details['fee_id']);
            //             if (empty($fdetail)) {
            //                 $fdetail = $common->getSingleValue('merchant_fee_detail', 'fee_detail_id', $pg_details['fee_id']);
            //             }
            //         } else {
            //             $fdetail = $common->getSingleValue('merchant_fee_detail', 'fee_detail_id', $pg_details['fee_id']);
            //         }
            //         $vendor_detail['pg_vendor_id'] = $common->getRowValue('pg_vendor_id', 'vendor', 'vendor_id', $fdetail['vendor_id']);
            //         $vendor_detail['online_pg_settlement'] = 1;
            //         $vendor_detail['settlement_type'] = 2;
            //         $vendor_detail['commision_type'] = 1;
            //         $vendor_detail['commission_percentage'] = 100.00;
            //     }

            //     if (!empty($vendor_detail)) {
            //         if ($vendor_detail['online_pg_settlement'] == 1 && $vendor_detail['settlement_type'] == 2) {
            //             if ($vendor_detail['commision_type'] == 1) {
            //                 $comm = ',"commission":' . $vendor_detail['commission_percentage'] . '}]';
            //             } else if ($vendor_detail['commision_type'] == 2) {
            //                 $comm = ',"commissionAmount":' . $vendor_detail['commision_amount'] . '}]';
            //             } else {
            //                 $comm = '}]';
            //             }
            //             $vendors = '[{"vendorId":"' . $vendor_detail['pg_vendor_id'] . '"' . $comm;
            //             $vendors = base64_encode($vendors);
            //         }
            //     }
            // }
            $name = $first_name . ' ' . $last_name;
            $name = $this->filterdata($name);
            $info = $this->filterdata($info);
            Session::put('patron_name', $name);
            Session::put('email', $email);
            $partner_code = getenv('CASHFREE_PARTNER_CODE');
            $post_data = array(
                "appId" => $app_id,
                "orderId" => $transaction_id,
                "orderAmount" => $amount,
                "orderCurrency" => 'INR',
                "orderNote" => $info,
                "pc" => $partner_code,
                "vendorSplit" => $vendors,
                "customerName" => $name,
                "customerPhone" => $phone,
                "customerEmail" => $email,
                "returnUrl" => $return_url
            );

            if ($notify_url != '') {
                $post_data['notifyUrl'] = $notify_url;
            }
            ksort($post_data);
            $signatureData = "";
            foreach ($post_data as $key => $value) {
                $signatureData .= $key . $value;
            }
            $signature = hash_hmac('sha256', $signatureData, $secretKey, true);
            $signature = base64_encode($signature);
            $signature = str_replace("+", "~plus~", $signature);

            $post_data['signature'] = $signature;
            $post_data['post_url'] = $request_url;

            foreach ($post_data as $key => $value) {
                $post_items[] = $key . '=' . $value;
            }
            //create the final string to be posted using implode()
            $post_string = implode('&', $post_items);
            /**
             * curl function start here
             */

            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            if ($host == 'https') {
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $data1 = curl_exec($ch) or die(curl_error($ch));
            curl_close($ch);
            $response = $data1;
            echo $response;
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePAYUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function calculateCommission($detail, $amount)
    {
        try {
            $swipez_comm = 0;
            $pg_comm = 0;
            if ($detail['swipez_fee_type'] == 'F') {
                $swipez_comm = $detail['swipez_fee_val'];
            }
            if ($detail['swipez_fee_type'] == 'P') {
                $swipez_comm = round($detail['swipez_fee_val'] * $amount / 100, 2);
            }

            if ($detail['pg_fee_type'] == 'F') {
                $pg_comm = $detail['pg_fee_val'];
            }
            if ($detail['pg_fee_type'] == 'P') {
                $pg_comm = round($detail['pg_fee_val'] * $amount / 100, 2);
            }

            $total_com = $pg_comm + $swipez_comm;
            $gst = round($detail['pg_tax_val'] * $total_com / 100, 2);
            $commission = $amount - $total_com - $gst;
            return $commission;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, 'Error while calculate vendor commission  : ' . json_encode($detail) . $e->getMessage());
            return $amount;
        }
    }

    public function InvokePaypalGateway($type, $transaction_id, $details, $pg_details, $first_name, $last_name, $email, $mobile, $view)
    {
        try {
            $app_id = isset($pg_details['pg_val1']) ? $pg_details['pg_val1'] : '';
            $secretKey = isset($pg_details['pg_val2']) ? $pg_details['pg_val2'] : '';
            $client_id = isset($pg_details['pg_val4']) ? $pg_details['pg_val4'] : '';
            $merchant_email = isset($pg_details['pg_val5']) ? $pg_details['pg_val5'] : '';
            $secretKey = base64_encode($client_id . ':' . $secretKey);
            SwipezLogger::error(__CLASS__, 'Secret' . $secretKey);
            $return_url = isset($pg_details['status_url']) ? $pg_details['status_url'] : '';
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $return_url = str_replace('secure', 'xway', $return_url);
            }
            $request_url = isset($pg_details['req_url']) ? $pg_details['req_url'] : '';
            $first_name = ($first_name != '') ? $first_name : $details['@patron_first_name'];
            $last_name = ($last_name != '') ? $last_name : $details['@patron_last_name'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $info = ($details['@narrative'] != '') ? $details['@narrative'] : 'Empty Narrative';
            $amount = $details['@absolute_cost'];
            $name = $first_name . ' ' . $last_name;
            $name = $this->filterdata($name);
            $info = $this->filterdata($info);
            Session::put('patron_name', $name);
            Session::put('email', $email);
            Session::put('transaction_type', $type);
            $json = '{"intent":"CAPTURE","purchase_units":[{"reference_id":"' . $transaction_id . '","description":"' . $info . '","custom_id":"' . $transaction_id . '-CUST","soft_descriptor":"' . $app_id . '","amount":{"currency_code":"INR","value":"' . $amount . '","breakdown":{"item_total":{"currency_code":"INR","value":"' . $amount . '"},"shipping":{"currency_code":"INR","value":"00.00"},"handling":{"currency_code":"INR","value":"00.00"},"tax_total":{"currency_code":"INR","value":"00.00"},"shipping_discount":{"currency_code":"INR","value":"00"}}},"payee":{"email_address":"' . $merchant_email . '"}}]}';

            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $request_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("authorization: Basic " . $secretKey, "content-type: application/json"));
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            if ($host == 'https') {
                //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $response = curl_exec($ch) or die(curl_error($ch));
            curl_close($ch);
            $array = json_decode($response, 1);
            return $array;
            die();
            header('Location: https://www.sandbox.paypal.com/checkoutnow?token=' . $array['id']);
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG01]Error while Invoke Paypal Gateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function PaypalOrderStatus($id, $secretKey, $mode)
    {
        try {
            SwipezLogger::debug(__CLASS__, 'paypal transaction status id: /' . $id . '/' . ' Key:/' . $secretKey . '/');
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
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Basic " . $secretKey,
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }


            die();









            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "");
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("authorization: Basic " . $secretKey, "content-type: application/json"));
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            if ($host == 'https') {
                //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $response = curl_exec($ch) or die(curl_error($ch));
            SwipezLogger::debug(__CLASS__, 'paypal Gateway response: ' . $response);
            $array = json_decode($response, 1);
            return $array;
            die();

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
                CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Authorization: Basic " . $secretKey)
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                SwipezLogger::error(__CLASS__, 'paypal response Curl Error: ' . $err);
            } else {
                SwipezLogger::debug(__CLASS__, 'paypal Gateway response: ' . $response);
                $array = json_decode($response, 1);
                return $array;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG01]Error while InvokePAYUGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokeEBSGateway($transaction_id, $details, $pg_details, $first_name, $last_name, $email, $mobile, $city, $state, $zipcode, $address)
    {
        try {
            $account_id = isset($pg_details['pg_val1']) ? $pg_details['pg_val1'] : '';
            $return_url = isset($pg_details['pg_val4']) ? $pg_details['pg_val4'] : '';
            $request_url = isset($pg_details['req_url']) ? $pg_details['req_url'] : '';
            $post_url = isset($pg_details['pg_val5']) ? $pg_details['pg_val5'] : '';
            $mode = isset($pg_details['pg_val3']) ? $pg_details['pg_val3'] : '';
            $ebskey = isset($pg_details['pg_val2']) ? $pg_details['pg_val2'] : '';

            $hash = $ebskey . "|" . $account_id . "|" . $details['@absolute_cost'] . "|" . $transaction_id . "|" . $return_url . "|" . $mode;
            $secure_hash = md5($hash);
            //create array of data to be posted      
            $patron_name = $details['@patron_first_name'] . ' ' . $details['@patron_last_name'];
            $patron_name = ($first_name != '') ? $first_name . ' ' . $last_name : $patron_name;
            $address = ($address != '') ? $address : $details['@patron_address1'];
            $city = ($city != '') ? $city : $details['@patron_city'];
            $state = ($state != '') ? $state : $details['@patron_state'];
            $zip = ($zipcode != '') ? $zipcode : $details['@patron_zipcode'];
            $phone = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $country = 'IND';

            $address = ($address != '') ? $address : 'Empty address';
            $city = ($city != '') ? $city : 'Unknown';
            $state = ($state != '') ? $state : 'Unknown';
            $zip = ($zip != '') ? $zip : '000000';

            $post_data['req_url'] = $request_url;
            $post_data['return_url'] = $return_url;
            $post_data['mode'] = $mode;
            $post_data['reference_no'] = $transaction_id;
            $post_data['amount'] = $details['@absolute_cost'];
            $post_data['description'] = ($details['@narrative'] != '') ? $details['@narrative'] : 'Empty Narrative';
            $post_data['name'] = $patron_name;
            $post_data['address'] = $address;
            $post_data['city'] = $city;
            $post_data['state'] = $state;
            $post_data['account_id'] = $account_id;
            $post_data['postal_code'] = $zip;
            $post_data['country'] = $country;
            $post_data['phone'] = $phone;
            $post_data['email'] = $email;

            $post_data['ship_name'] = $patron_name;
            $post_data['ship_address'] = $address;
            $post_data['ship_city'] = $city;
            $post_data['ship_state'] = $state;
            $post_data['ship_postal_code'] = $zip;
            $post_data['ship_country'] = $country;
            $post_data['ship_phone'] = $phone;
            $post_data['hash'] = $secure_hash;
            //traverse array and prepare data for posting (key1=value1)
            // echo '<PRE>';
            // print_r($post_data);
            // echo $post_url;
            //die();
            foreach ($post_data as $key => $value) {
                $post_items[] = $key . '=' . $value;
            }
            //create the final string to be posted using implode()
            $post_string = implode('&', $post_items);
            /**
             * curl function start here
             */
            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            if ($host == 'https') {
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $data1 = curl_exec($ch) or die(curl_error($ch));

            curl_close($ch);
            $response = $data1;
            echo $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG02]Error while InvokeEBSGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokePAYTMGateway($type, $transaction_id, $details, $pg_details, $email, $mobile, $first_name, $last_name)
    {
        try {
            Session::put('paytm_cust_id', $details['@merchant_id']);
            $post_url = isset($pg_details['pg_val5']) ? $pg_details['pg_val5'] : '';
            $mobile = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $patron_name = $details['@patron_first_name'] . ' ' . $details['@patron_last_name'];
            $patron_name = ($first_name != '') ? $first_name . ' ' . $last_name : $patron_name;

            $post_data['ORDER_ID'] = $transaction_id;
            $post_data['t_type'] = $type;
            $post_data['CUST_ID'] = $details['@company_merchant_id'];
            $post_data['INDUSTRY_TYPE_ID'] = $pg_details['pg_val7'];
            $post_data['CHANNEL_ID'] = $pg_details['pg_val6'];
            $post_data['TXN_AMOUNT'] = $details['@absolute_cost'];
            $post_data['EMAIL'] = $email;
            $post_data['MOBILE_NO'] = $mobile;

            $post_data['CALLBACK_URL'] = $pg_details['status_url'];
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $post_data['CALLBACK_URL'] = str_replace('secure', 'xway', $post_data['CALLBACK_URL']);
            }
            Session::put('transaction_type', $type);
            Session::put('patron_name', $patron_name);
            Session::put('email', $email);
            //traverse array and prepare data for posting (key1=value1)
            foreach ($post_data as $key => $value) {
                $post_items[] = $key . '=' . $value;
            }
            //create the final string to be posted using implode()
            $post_string = implode('&', $post_items);
            /**
             * curl function start here
             */
            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            if ($host == 'https') {
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $data1 = curl_exec($ch) or die(curl_error($ch));

            curl_close($ch);
            $response = $data1;
            echo $response;
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG03]Error while InvokePAYTMGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokeTECHPROCESSGateway($type, $transaction_id, $details, $pg_details, $email, $mobile, $first_name, $last_name)
    {
        try {
            $mobile = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];
            $patron_name = $details['@patron_first_name'] . ' ' . $details['@patron_last_name'];
            $patron_name = ($first_name != '') ? $first_name . ' ' . $last_name : $patron_name;
            $customer_id = ($details['customer_id'] != '') ? $details['customer_id'] : $mobile;
            Session::put('email', $email);
            Session::put('patron_name', $patron_name);
            if ($details['logo'] != '') {
                $logo_url = $details['host'] . '/uploads/images/landing/' . $details['logo'];
            } else {
                $logo_url = 'https://www.swipez.in/assets/admin/layout/img/logo.png?v=2';
            }
            $string_token = $pg_details['pg_val1'] . '|' . $transaction_id . '|' . $details['@absolute_cost'] . '||' . $customer_id . '|' . $mobile . '|' . $email . '||||||||||' . $pg_details['pg_val2'];
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $pg_details['status_url'] = str_replace('secure', 'xway', $pg_details['status_url']);
            }
            $token = md5($string_token);
            $configArray['tarCall'] = false;
            $configArray['features']['showPGResponseMsg'] = true;
            $configArray['features']['enableNewWindowFlow'] = false;
            $consumer_data['deviceId'] = "WEBMD5";
            $consumer_data['token'] = $token;
            $consumer_data['returnUrl'] = $pg_details['status_url'];
            $consumer_data['responseHandler'] = "null";
            $consumer_data['paymentMode'] = $pg_details['pg_val3'];
            $consumer_data['merchantLogoUrl'] = $logo_url;
            $consumer_data['merchantId'] = $pg_details['pg_val1'];
            $consumer_data['consumerId'] = $customer_id;
            $consumer_data['consumerMobileNo'] = $mobile;
            $consumer_data['consumerEmailId'] = $email;
            $consumer_data['checkoutElement'] = "#checkoutElement";
            $consumer_data['redirectOnClose'] = false;
            $consumer_data['txnId'] = $transaction_id;
            $consumer_data['items'][0]['itemId'] = $pg_details['pg_val4'];
            $consumer_data['items'][0]['amount'] = $details['@absolute_cost'];
            $consumer_data['items'][0]['comAmt'] = 0;
            $consumer_data['customStyle']['PRIMARY_COLOR_CODE'] = "#3977b7";
            $consumer_data['customStyle']['SECONDARY_COLOR_CODE'] = "#FFFFFF";
            $consumer_data['customStyle']['BUTTON_COLOR_CODE_1'] = "#1969bb";
            $consumer_data['customStyle']['BUTTON_COLOR_CODE_2'] = "#FFFFFF";
            $configArray['consumerData'] = $consumer_data;

            require_once CONTROLLER . 'Secure.php';
            $secure = new Secure();
            $secure->techprocessinvoke($configArray, $pg_details);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG03]Error while InvokePAYTMGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokePAYTMSubscription($type, $transaction_id, $subs_id, $details, $pg_details, $email, $mobile, $first_name, $last_name, $start_date, $expiry_date)
    {
        try {
            Session::put('paytm_cust_id', $details['@merchant_id']);
            $patron_name = $details['@patron_first_name'] . ' ' . $details['@patron_last_name'];
            $patron_name = ($first_name != '') ? $first_name . ' ' . $last_name : $patron_name;
            $email = ($email != '') ? $email : $details['@patron_email'];
            Session::put('patron_name', $patron_name);
            Session::put('email', $email);
            $post_url = isset($pg_details['pg_val5']) ? $pg_details['pg_val5'] : '';
            $post_data['ORDER_ID'] = $transaction_id;
            $post_data['t_type'] = $type;
            $post_data['CUST_ID'] = $details['customer_id'];
            $post_data['INDUSTRY_TYPE_ID'] = $pg_details['pg_val6'];
            $post_data['CHANNEL_ID'] = 'WEB';
            $post_data['TXN_AMOUNT'] = $details['@absolute_cost'];
            $post_data['SUBS_SERVICE_ID'] = 'swipezsubs' . $subs_id;
            $post_data['max_amount'] = $pg_details['pg_val7'];
            $post_data['CALLBACK_URL'] = $pg_details['status_url'];
            $post_data['start_date'] = $start_date;
            $post_data['expiry_date'] = $expiry_date;
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $post_data['CALLBACK_URL'] = str_replace('secure', 'xway', $post_data['CALLBACK_URL']);
            }
            Session::put('transaction_type', $type);
            //traverse array and prepare data for posting (key1=value1)
            foreach ($post_data as $key => $value) {
                $post_items[] = $key . '=' . $value;
            }
            //create the final string to be posted using implode()
            $post_string = implode('&', $post_items);
            /**
             * curl function start here
             */
            $ch = curl_init() or die(curl_error());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($ch, CURLOPT_PORT, 443); // port 443
            curl_setopt($ch, CURLOPT_URL, $post_url); // here the request is sent to payment gateway 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //create a SSL connection object server-to-server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $env = getenv('ENV');
            $host = ($env == 'LOCAL') ? 'http' : 'https';
            if ($host == 'https') {
                curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
            }
            $data1 = curl_exec($ch) or die(curl_error($ch));

            curl_close($ch);
            $response = $data1;
            echo $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG03]Error while InvokePAYTMGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function InvokeATOMGateway($transaction_id, $details, $pg_details, $first_name, $last_name, $email, $mobile, $address)
    {
        try {
            $payment_url = $pg_details['req_url'];
            $Login = $pg_details['pg_val1'];
            $Password = $pg_details['pg_val2'];
            $TxnCurr = $pg_details['pg_val6'];
            $clientcode = $pg_details['pg_val5'];
            $AccountNo = $pg_details['pg_val7'];
            $reqHashKey = $pg_details['pg_val8'];
            $return_url = $pg_details['status_url'];
            if (substr($transaction_id, 0, 1) != 'T' && substr($transaction_id, 0, 1) != 'F') {
                $return_url = str_replace('secure', 'xway', $return_url);
            }

            $TType = $pg_details['pg_val3'];
            $product = $pg_details['pg_val4'];
            $TxnScAmt = 0;

            $patron_name = $details['@patron_first_name'] . ' ' . $details['@patron_last_name'];
            $patron_name = ($first_name != '') ? $first_name . ' ' . $last_name : $patron_name;
            $address = ($address != '') ? $address : $details['@patron_address1'];
            $phone = ($mobile != '') ? $mobile : $details['@patron_mobile_no'];
            $email = ($email != '') ? $email : $details['@patron_email'];

            $patron_name = $this->filterdata($patron_name);
            $address = $this->filterdata($address);

            $address = ($address != '') ? $address : 'None';
            $datenow = date("d/m/Y h:m:s");
            $modifiedDate = str_replace(" ", "%20", $datenow);


            require_once UTIL . 'AtomTransactionRequest.php';

            $transactionRequest = new AtomTransactionRequest();
            //Setting all values here
            $transactionRequest->setLogin($Login);
            $transactionRequest->setPassword($Password);
            $transactionRequest->setProductId($product);
            $transactionRequest->setAmount($details['@absolute_cost']);
            $transactionRequest->setTransactionCurrency($TxnCurr);
            $transactionRequest->setTransactionUrl($payment_url);
            $transactionRequest->setTransactionAmount($TxnScAmt);
            $transactionRequest->setReturnUrl($return_url);
            $transactionRequest->setClientCode(urlencode(base64_encode($clientcode)));
            $transactionRequest->setTransactionId($transaction_id);
            $transactionRequest->setTransactionDate($modifiedDate);
            $transactionRequest->setCustomerName($patron_name);
            $transactionRequest->setCustomerEmailId($email);
            $transactionRequest->setCustomerMobile($phone);
            $transactionRequest->setCustomerBillingAddress($address);
            $transactionRequest->setCustomerAccount($AccountNo);
            $transactionRequest->setReqHashKey($reqHashKey);
            $response = $transactionRequest->getPGUrl();
            if ($response['status'] == 0) {
                SwipezLogger::error(__CLASS__, '[EATPG01]Error while get atom tempTxnId Error: ' . $response['return_data'] . ' Request: ' . $response['send_url']);
                $this->failedTransaction($transaction_id);
                exit();
            } else {
                header("Location: " . $response['url']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG04]Error while InvokeATOMGateway Error: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    function filterdata($data)
    {
        $data = str_replace('&', 'and', $data);
        $data = str_replace('+', ' ', $data);
        $data = str_replace('#', '', $data);
        return $data;
    }

    function failedTransaction($transaction_id)
    {
        try {
            $session = new SessionLegacy();
            $session->set('errorTitle', 'Transaction Failed');
            $session->set('errorMessage', 'An error occurred while making this transaction. Please contact Swipez support on suppport@swipez.in and quote the transaction id ' . $transaction_id . ' to help us track your transaction.');
            header("Location: /error");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EPG0696]Error while print error Error: ' . $e->getMessage());
        }
    }

    function getPGRadio($pg_details, $encrypt)
    {
        $paypal_account_id = '';
        foreach ($pg_details as $pg) {
            if ($pg['pg_type'] == 4 || $pg['pg_type'] == 6) {
                $netbanking_pg = $encrypt->encode($pg['pg_id']);
                $net_fee_id = $encrypt->encode($pg['fee_detail_id']);
            } else if ($pg['pg_type'] == 2) {
                $paytm_pg = $encrypt->encode($pg['pg_id']);
                $paytm_fee_id = $encrypt->encode($pg['fee_detail_id']);
            } else if ($pg['pg_type'] == 5) {
                $paytm_sub_pg = $encrypt->encode($pg['pg_id']);
                $paytm_sub_fee_id = $encrypt->encode($pg['fee_detail_id']);
            } else if ($pg['pg_type'] == 9) {
                $international_pg = $encrypt->encode($pg['pg_id']);
                $international_fee_id = $encrypt->encode($pg['fee_detail_id']);
            } else if ($pg['pg_type'] == 8) {
                $paypal_pg = $encrypt->encode($pg['pg_id']);
                $paypal_fee_id = $encrypt->encode($pg['fee_detail_id']);
                $paypal_account_id = $pg['pg_val4'];
            } else if ($pg['pg_type'] == 12) {
                $payoneer_fee_id = $encrypt->encode($pg['fee_detail_id']);
                $payoneer_id = $encrypt->encode($pg['pg_id']);
            } else if ($pg['pg_type'] == 11) {
                $stripe_fee_id = $encrypt->encode($pg['fee_detail_id']);
                $stripe_id = $encrypt->encode($pg['pg_id']);
            } else {
                $credit_card_pg = $encrypt->encode($pg['pg_id']);
                $credit_fee_id = $encrypt->encode($pg['fee_detail_id']);
            }
        }
        if (!isset($credit_fee_id)) {
            $credit_fee_id = $net_fee_id;
            $credit_card_pg = $netbanking_pg;
        }
        $radio[] = array('name' => 'UPI', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);
        $radio[] = array('name' => 'Credit/Debit card', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);

        if (isset($netbanking_pg)) {
            $radio[] = array('name' => 'Net banking', 'pg_id' => $netbanking_pg, 'fee_id' => $net_fee_id);
        } else {
            $radio[] = array('name' => 'Net banking', 'pg_id' => $credit_card_pg, 'fee_id' => $credit_fee_id);
        }
        if (isset($paytm_pg)) {
            $radio[] = array('name' => 'Paytm', 'pg_id' => $paytm_pg, 'fee_id' => $paytm_fee_id);
        }
        if (isset($international_pg)) {
            $radio[] = array('name' => 'International Card <a  class="popovers" data-container="body" data-trigger="hover" data-content="Cards issued by banks outside of India" data-original-title="" title=""><i class="fa fa-info-circle"></i></a>', 'pg_id' => $international_pg, 'fee_id' => $international_fee_id);
        }
        if (isset($paytm_sub_pg)) {
            $radio[] = array('name' => 'PAYTM_SUB', 'pg_id' => $paytm_sub_pg, 'fee_id' => $paytm_sub_fee_id);
        }
        if (isset($paypal_pg)) {
            $radio[] = array('name' => 'PAYPAL', 'pg_id' => $paypal_pg, 'fee_id' => $paypal_fee_id);
        }

        if (isset($payoneer_id)) {
            $radio[] = array('name' => 'Payoneer', 'pg_id' => $payoneer_id, 'fee_id' => $payoneer_fee_id);
        }
        if (isset($stripe_id)) {
            $radio[] = array('name' => 'Stripe', 'pg_id' => $stripe_id, 'fee_id' => $stripe_fee_id);
        }
        $array['radio'] = $radio;
        $array['paypal_id'] = $paypal_account_id;
        return $array;
    }

    public function validateCashfree($transaction_id, $post)
    {
        $common = new CommonModel();
        if (substr($transaction_id, 0, 1) == 'T') {
            $pg_id = $common->getRowValue('pg_id', 'payment_transaction', 'pay_transaction_id', $transaction_id);
        } else if (substr($transaction_id, 0, 1) == 'F') {
            $pg_id = $common->getRowValue('pg_id', 'package_transaction', 'package_transaction_id', $transaction_id);
        } else {
            $pg_id = $common->getRowValue('pg_id', 'xway_transaction', 'xway_transaction_id', $transaction_id);
        }
        $secretkey = $common->getRowValue('pg_val2', 'payment_gateway', 'pg_id', $pg_id);
        $orderId = $post["orderId"];
        $orderAmount = $post["orderAmount"];
        $referenceId = $post["referenceId"];
        $txStatus = $post["txStatus"];
        $paymentMode = $post["paymentMode"];
        $txMsg = $post["txMsg"];
        $txTime = $post["txTime"];
        $signature = $post["signature"];
        $data = $orderId . $orderAmount . $referenceId . $txStatus . $paymentMode . $txMsg . $txTime;
        $hash_hmac = hash_hmac('sha256', $data, $secretkey, true);
        $computedSignature = base64_encode($hash_hmac);
        if ($signature == $computedSignature) {
            return true;
        } else {
            return false;
        }
    }

    function cashfreeRefund($app_id, $secret_id, $transaction_id, $reference_id, $amount, $note)
    {
        $env = getenv('ENV');
        if ($env == 'PROD') {
            $url = "https://api.cashfree.com/api/v1/order/refund";
        } else {
            $url = "https://test.cashfree.com/api/v1/order/refund";
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "appId=$app_id&secretKey=$secret_id&orderId=$transaction_id&referenceId=$reference_id&refundAmount=$amount&refundNote=$note",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            SwipezLogger::error(__CLASS__, 'Refund Cashfree Curl error:' . $err);
            echo "cURL Error #:" . $err;
        } else {
            SwipezLogger::debug(__CLASS__, 'Refund Cashfree Response:' . $response);
            $row = json_decode($response, true);
            if ($row['status'] == 'OK') {
                $row['refund_id'] = $row['refundId'];
            }
            return $row;
        }
    }

    function setuRefund($pg_details, $transaction_id, $reference_id, $amount, $note)
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


        $curl = curl_init();
        $payload = '{
            "refunds": [
                {
                    "seqNo": 1,
                    "identifier": "' . $transaction_id . '",
                    "identifierType": "BILL_ID",
                    "refundType": "FULL"
                }
            ]
        }';

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://uat.setu.co/api/v2/refund/batch',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $payload,
            CURLOPT_HTTPHEADER => array(
                'X-Setu-Product-Instance-ID: ' . $pg_details["pg_val6"],
                'authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $err = curl_error($curl);
        if ($err) {
            SwipezLogger::error(__CLASS__, 'Refund setu Curl error:' . $err);
            echo "cURL Error #:" . $err;
        } else {
            SwipezLogger::debug(__CLASS__, 'Refund setu Response:' . $response);
            $row = json_decode($response, true);
            if ($row['success'] == true) {
                $res = [];
                $res["batch_id"] = $row['data']['batchID'];
                $res["refund_id"] = $row['data']['refunds'][0]['id'];
                $res["refund_status"] = $row['data']['refunds'][0]['status'];
                $res["trans_ref_id"] = $row['data']['refunds'][0]['transactionRefID'];
                $res["platform_bill_id"] = $row['data']['refunds'][0]['billID'];
                return $res;
            } else {
                SwipezLogger::error(__CLASS__, 'Refund setu Curl error:' . $row);
            }
        }
    }

    public function razorpayRefund($key, $secret_key, $payment_id, $amount, $note, $account_id)
    {
        try {
            $api = new Api($key, $secret_key);
            if ($account_id != '') {
                $api->setHeader('x-razorpay-account', $account_id);
            }
            $amount = $amount * 100;
            $result = $api->refund->create(array('payment_id' => $payment_id, 'amount' => $amount));
            SwipezLogger::debug(__CLASS__, 'Refund Razorpay Response:' . json_encode($result));
            return $result->id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error razorpay refund. Payment id: ' . $payment_id . ' Error: ' . $e->getMessage());
        }
    }

    public function stripeRefund($key, $secret_key, $payment_id, $amount, $note, $account_id)
    {
        try {
            // dd('yes');
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );
            $result = $stripe->refunds->create([
                'payment_intent' => $payment_id,
            ]);
            // dd($result);
            SwipezLogger::debug(__CLASS__, 'Refund Stripe Response:' . json_encode($result));
            // return $result->id;
            return $result->id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error stripe refund. Payment id: ' . $payment_id . ' Error: ' . $e->getMessage());
        }
    }

    public function payuRefund($secret_key, $payment_id, $amount)
    {
        try {
            $env = getenv('ENV');
            if ($env == 'PROD') {
                $url = "https://www.payumoney.com/";
            } else {
                $url = "https://test.payumoney.com/";
            }
            $url = $url . 'treasury/merchant/refundPayment?paymentId=' . $payment_id . '&refundAmount=' . $amount;
            SwipezLogger::debug(__CLASS__, 'Payu Resquest:' . $url);
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
                SwipezLogger::error(__CLASS__, '[E36]Exception occured while get payu api response payment id: ' . $payment_id);
            }
            SwipezLogger::debug(__CLASS__, 'Refund Payu Response:' . $result);
            $row = json_decode($result, true);
            if ($row['result'] != '') {
                $row['refund_id'] = $row['result'];
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error payu refund. Payment id: ' . $payment_id . ' Error: ' . $e->getMessage());
        }
    }

    function paytmRefund($merchantMid, $merchantKey, $orderId, $refId, $refundAmount, $paytm_transactionId)
    {
        try {
            /**
             * import checksum generation utility
             * You can get this utility from https://developer.paytm.com/docs/checksum/
             */
            require_once(UTIL . "encdec_paytm2.php");

            /* initialize an array */
            $paytmParams = array();

            /* body parameters */
            $paytmParams["body"] = array(
                /* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
                "mid" => $merchantMid,
                /* This has fixed value for refund transaction */
                "txnType" => "REFUND",
                /* Enter your order id for which refund needs to be initiated */
                "orderId" => $orderId,
                /* Enter transaction id received from Paytm for respective successful order */
                "txnId" => $paytm_transactionId,
                /* Enter numeric or alphanumeric unique refund id */
                "refId" => $refId,
                /* Enter amount that needs to be refunded, this must be numeric */
                "refundAmount" => $refundAmount,
            );

            /**
             * Generate checksum by parameters we have in body
             * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
             */
            $checksum = getChecksumFromString(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchantKey);
            /* head parameters */
            $paytmParams["head"] = array(
                /* This is used when you have two different merchant keys. In case you have only one please put - C11 */
                "clientId" => "C11",
                /* put generated checksum value here */
                "signature" => $checksum
            );
            /* prepare JSON string for request */
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            $env = getenv('ENV');
            if ($env == 'PROD') {
                /* for Production */
                $url = "https://securegw.paytm.in/refund/apply";
            } else {
                /* for Staging */
                $url = "https://securegw-stage.paytm.in/refund/apply";
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            $response = curl_exec($ch);
            SwipezLogger::debug(__CLASS__, 'Refund Paytm Response:' . $response);
            $row = json_decode($response, true);
            $row['message'] = $row['RESPMSG'];
            $code = $row['body']['resultInfo']['resultCode'];
            if ($code == 601 || $code == 10) {
                $row['refund_id'] = $row['body']['refundId'];
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error Paytm refund Transaction id: ' . $orderId . ' Error: ' . $e->getMessage());
        }
    }
}
