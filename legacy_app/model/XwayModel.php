<?php

class XwayModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function validateXwayTransactionResponse($transaction_id, $amount)
    {
        try {
            $sql = "select absolute_cost,surcharge_amount,xway_transaction_status,merchant_id from xway_transaction where xway_transaction_id=:xway_transaction_id";
            $params = array(':xway_transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $request_amount = $row['absolute_cost'] + $row['surcharge_amount'];
            $merchant_id = $row['merchant_id'];
            $transaction_status = $row['xway_transaction_status'];
            if ($request_amount != $amount) {
                SwipezLogger::error(__CLASS__, '[Ex009]Error while validating payment gateway xwaypaymentresponse Error: Request amount does not match. xway_transaction_id: ' . $transaction_id . '');
            }
            if ($transaction_status != 0) {
                SwipezLogger::error(__CLASS__, '[Ex010]Error while validating payment gateway xwaypaymentresponse Error: transaction status does not match');
            }
            return $request_amount;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex011]Error while validating payment gateway xwaypaymentresponse Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function validatexway($mode, $merchant_id, $amount, $reference_no)
    {
        try {
            $sql = "call validate_xwayrequest(:mode,:merchant_id,:amount,:reference_no)";
            $params = array(':mode' => $mode, ':merchant_id' => $merchant_id, ':amount' => $amount, ':reference_no' => $reference_no);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex012]Error while validate xway Error:  param [' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function savexwaytransaction($pg_id, $absolute_cost, $merchant_id, $req_url, $return_url, $reference_no, $amount, $surcharge, $description, $name, $address, $city, $state, $account_id, $postal_code, $phone, $email, $udf1, $udf2, $udf3, $udf4, $udf5, $mdd, $hash, $customer_code = '', $franchise_id = 0, $vendor_id = 0, $currency = 'INR', $is_random_id = 0, $type = 1, $webhook_id = 0, $discount = 0, $create_invoice_api = 0)
    {
        try {
            $currency = ($currency == '') ? 'INR' : $currency;
            $surcharge = ($surcharge > 0) ? $surcharge : 0;
            $req_url = substr($req_url, 0, 200);
            $sql = "call save_xwaytransaction(:pg_id,:merchant_id,:referrer_url,:account_id,:secure_hash,:return_url,:reference_no,:amount,:surcharge,:absolute_cost,:description,:name,:address,:city,:state,:postal_code,:phone,:email,:udf1,:udf2,:udf3,:udf4,:udf5,:mdd,:customer_code,:franchise_id,:vendor_id,:currency,:random_id,:type,:webhook_id,:discount,:create_invoice_api);";
            $params = array(':pg_id' => $pg_id, ':merchant_id' => $merchant_id, ':referrer_url' => $req_url, ':account_id' => $account_id, ':secure_hash' => $hash, ':return_url' => $return_url, ':reference_no' => $reference_no, ':amount' => $amount, ':surcharge' => $surcharge, ':absolute_cost' => $absolute_cost, ':description' => $description, ':name' => $name, ':address' => $address, ':city' => $city, ':state' => $state, ':postal_code' => $postal_code, ':phone' => $phone, ':email' => $email, ':udf1' => $udf1, ':udf2' => $udf2, ':udf3' => $udf3, ':udf4' => $udf4, ':udf5' => $udf5, ':mdd' => $mdd, ':customer_code' => $customer_code, ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id, ':currency' => $currency, ':random_id' => $is_random_id, ':type' => $type, ':webhook_id' => $webhook_id, ':discount' => $discount, ':create_invoice_api' => $create_invoice_api);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $space_position = strpos($name, ' ');
            if ($space_position > 0) {
                $row['@patron_first_name'] = substr($name, 0, $space_position);
                $row['@patron_last_name'] = substr($name, $space_position);
            } else {
                $row['@patron_first_name'] = $name;
                $row['@patron_last_name'] = '';
            }

            $row['vendor_id'] = $vendor_id;
            $row['@patron_address1'] = $address;
            $row['@patron_city'] = $city;
            $row['@patron_state'] = $state;
            $row['@patron_zipcode'] = $postal_code;
            $row['@narrative'] = $description;
            $row['@absolute_cost'] = $absolute_cost;

            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex013]Error while save xway transaction Error:  param [' . json_encode($params) . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateXwayTable($id, $plan_id, $coupon_id)
    {
        try {
            $sql = "update xway_transaction set plan_id=:plan_id,coupon_id=:coupon_id where xway_transaction_id=:xway_transaction_id";
            $params = array(':xway_transaction_id' => $id, ':plan_id' => $plan_id, ':coupon_id' => $coupon_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex014+1]Error while update Xway Table Error:  param [' . json_encode($params) . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMerchantXwayDeatails($merchant_id, $franchise_id = 0)
    {
        try {
            $sql = "select * from xway_merchant_detail where merchant_id=:merchant_id and franchise_id=:franchise_id";
            $params = array(':merchant_id' => $merchant_id, ':franchise_id' => $franchise_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex014+2]Error while getting xway gateway details Error:  param [' . json_encode($params) . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getXwayDeatails($xway_id)
    {
        try {
            $sql = "select * from xway_merchant_detail where xway_merchant_detail_id=:xway_merchant_detail_id";
            $params = array(':xway_merchant_detail_id' => $xway_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex014+3]Error while getting xway gateway details Error:  param [' . json_encode($params) . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getXwaySurcharge($amount, $fee_id)
    {
        try {
            $sql = "select get_xway_surcharge_amount(:amount,:fee_id) as amount";
            $params = array(':amount' => $amount, ':fee_id' => $fee_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result['amount'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E300]Error while fetching supplier list in template Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getPGDeatails($id, $type = NULL, $franchise_id = 0)
    {
        try {
            if ($type == 'transaction') {
                $sql = "select xway_merchant_detail_id,vendor_id  ,pg_type,pg.pg_id,pg.pg_val1,pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,pg.pg_val5,pg.pg_val6,pg.pg_val7,pg.pg_val8,pg.pg_val9,status_url,pg.type from payment_gateway as pg inner join xway_transaction as fd on fd.pg_id=pg.pg_id where fd.xway_transaction_id=:id";
            } else if ($type == 'payment_gateway') {
                $sql = "select xway_merchant_detail_id,vendor_id,fd.referrer_url,fd.return_url,fd.surcharge_enable,fd.pg_surcharge_enabled,fd.xway_merchant_detail_id,ga_tag,pg_type,pg.pg_id,pg.pg_val1,pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,pg.pg_val5,pg.pg_val6,pg.pg_val7,pg.pg_val8,pg.pg_val9,status_url,pg.type
from payment_gateway as pg inner join xway_merchant_detail as fd on fd.pg_id=pg.pg_id where fd.xway_merchant_detail_id=:id limit 1";
            } else {
                $sql = "select count(xway_merchant_detail_id) as pg_count,fd.xway_merchant_detail_id,vendor_id,fd.surcharge_enable,ga_tag,fd.pg_surcharge_enabled,fd.referrer_url,fd.return_url,pg_type,pg.pg_id,pg.pg_val1,pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,pg.pg_val5,pg.pg_val6,pg.pg_val7,pg.pg_val8,pg.pg_val9,status_url,pg.type from payment_gateway as pg inner join xway_merchant_detail as fd on fd.pg_id=pg.pg_id where fd.merchant_id=:id and fd.franchise_id=" . $franchise_id;
            }
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex015]Error while getting payment gateway details Error:  for id[' . $id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPaymentGatewayDetails($pg_transaction_id)
    {
        $sql = "select pg_id from xway_transaction where xway_transaction_id=:transaction_id";
        $params = array(':transaction_id' => $pg_transaction_id);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        $sql = "select pg.pg_id,pg.pg_val1,pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,pg.pg_val5,pg_val6,pg_val7,pg.type from payment_gateway as pg where pg.pg_id=:pg_id";
        $params = array(':pg_id' => $row['pg_id']);
        $this->db->exec($sql, $params);
        return $this->db->single();
    }

    public function handleAtomResponse($post, $user_id, $pg_type, $pg_transaction_id)
    {
        try {
            $post['MerchantRefNo'] = $post['mer_txn'];
            $post['Amount'] = $post['amt'];
            $result = $this->saveAtomResponse($post, $user_id);
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex017]Error while handeling Atom gateway response Error: for payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function handlePaytmGatewayResponse($patron_name, $email, $mid = NULL, $order_id = NULL, $amount = 0, $currency = NULL, $txn_id = NULL, $banktxn_id = NULL, $status = NULL, $resp_code = NULL, $resp_message = NULL, $txn_date = NULL, $gateway_name = NULL, $bank_name = NULL, $payment_mode = NULL, $checksum = NULL, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL)
    {
        try {
            $res = array();
            $res['MerchantRefNo'] = $order_id;
            $res['Amount'] = $amount;
            $res['amount'] = $amount;

            $result = $this->savePaytmResponse($patron_name, $email, $mid, $order_id, $amount, $currency, $txn_id, $banktxn_id, $status, $resp_code, $resp_message, $txn_date, $gateway_name, $bank_name, $payment_mode, $checksum, $pg_type, $user_id);
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function handlePaypalResponse($response)
    {
        try {
            $response['paymentMode'] = 'Paypal';
            $response['MerchantRefNo'] = $response['transaction_id'];
            $response['Amount'] = $response['amount'];

            $sql = "INSERT INTO `pg_ret_bank8`(`orderId`,`orderAmount`,`referenceId`,`txStatus`,`paymentMode`,`txTime`,`created_date`,`created_by`)
VALUES(:orderId,:orderAmount,:referenceId,:txStatus,:paymentMode,now(),CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':orderId' => $response['transaction_id'], ':orderAmount' => $response['amount'], ':referenceId' => $response['ref_id'], ':txStatus' => $response['status'], ':paymentMode' => $response['paymentMode'], ':user_id' => $user_id
            );

            if ($response['status'] == 'COMPLETED') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }

            $response = $this->savePaymentResponse($response, $response['name'], $response['email'], date('Y-m-d h:i'), $response['transaction_id'], $response['transaction_id'], time(), $amount, 'Paypal', '', $response['status'], 'Guest');
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function handleCashfreeGatewayResponse($patron_name, $email, $post, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL)
    {
        try {
            $res = array();
            $res['MerchantRefNo'] = $post['orderId'];
            $res['Amount'] = $post['orderAmount'];
            $res['amount'] = $post['orderAmount'];
            if ($post['txStatus'] == 'SUCCESS') {
                $res['status'] = 'success';
            } else {
                $res['status'] = 'failed';
            }
            $result = $this->saveCashfreeResponse(
                $patron_name,
                $email,
                $post['orderId'],
                $post['orderAmount'],
                $post['referenceId'],
                $post['txStatus'],
                $post['paymentMode'],
                $post['txMsg'],
                $post['txTime'],
                $post['signature'],
                $user_id
            );
            $result['type'] = 'request';
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }

    public function handleSetuGatewayResponse($patron_name, $email, $post, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL)
    {
        try {
            $exist = $this->existRetBank('billerBillID', 'pg_ret_bank11', $post['orderId']);
            if ($exist == FALSE) {
                $sql = "INSERT into `pg_ret_bank11` (`billerBillID`, `amountPaid`, `currencyCode`, `payerVpa`, `platformBillID`, `receiptId`, 
                `sourceAccount_ifsc`, `sourceAccount_name`, `sourceAccount_number`, `transactionNote`, `transactionId`, `status`, 
                `timeStamp`, `type`, `trans_unique_id`)
                 values (:billerBillID,:amountPaid
                 ,:currencyCode,:payerVpa
                 ,:platformBillID,:receiptId
                 ,:sourceAccount_ifsc,:sourceAccount_name
                 ,:sourceAccount_number,:transactionNote
                 ,:transactionId,:status
                 ,:timeStamp,:type
                 ,:trans_unique_id)";


                $params = array(
                    ':billerBillID' => $post["billerBillID"],
                    ':amountPaid' => $post["amountPaid"],
                    ':currencyCode' => $post["amountPaid"],
                    ':payerVpa' =>  $post["payerVpa"],
                    ':platformBillID' =>  $post["platformBillID"],
                    ':receiptId' =>  $post["receiptId"],
                    ':sourceAccount_ifsc' => '',
                    ':sourceAccount_name' =>  '',
                    ':sourceAccount_number' => '',
                    ':transactionNote' => $post["transactionNote"],
                    ':transactionId' => $post["transactionId"],
                    ':status' => $post["status"],
                    ':timeStamp' => $post["timeStamp"],
                    ':type' => '',
                    ':trans_unique_id' => ''
                );
            } else {
                $sql = "update pg_ret_bank11 set status=:txStatus,transactionNote=:transactionNote where billerBillID=:billerBillID";
                $params = array(
                    ':txStatus' => $post['status'], ':transactionNote' => $post['transactionNote'],
                    ':billerBillID' => $post['orderId']
                );
            }
            $this->db->exec($sql, $params);

            if ($post['status'] == 'SUCCESS') {
                $post['status'] = 'success';
            } else {
                $post['status'] = 'failed';
            }
            $response = $this->savePaymentResponse(
                $post,
                $patron_name,
                $email,
                $post["timeStamp"],
                $post['orderId'],
                $post['platformBillID'],
                $post['platformBillID'],
                $post["amountPaid"],
                'UPI',
                $post["transactionNote"],
                $post["status"],
                $user_id,
                'UPI'
            );

            $response['type'] = 'request';
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }

    public function handleTechProcessResponse($patron_name, $email, $data, $user_id = '')
    {
        try {
            $res = array();
            $res['MerchantRefNo'] = $data[3];
            $res['Amount'] = $data[6];
            $res['amount'] = $data[6];
            if ($data[0] == '0300') {
                $res['status'] = 'success';
            } else {
                $res['status'] = 'failed';
            }
            $status = $res['status'];
            $result = $this->saveTechProcessResponse($patron_name, $email, $data, $status, $user_id);
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function handlePayuResponse($post, $user_id, $pg_type, $pg_transaction_id, $total_amount)
    {
        try {
            $row = $this->getPGDeatails($pg_transaction_id, 'transaction');
            $status = $post["status"];
            $firstname = $post["firstname"];
            $amount = $total_amount;
            $txnid = $post["txnid"];
            $posted_hash = $post["hash"];
            $key = $post["key"];
            $productinfo = $post["productinfo"];
            $email = $post["email"];
            $salt = $row['pg_val2'];

            if (isset($post["additionalCharges"])) {
                $additionalCharges = $post["additionalCharges"];
                $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            } else {

                $retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            }
            $hash = hash("sha512", $retHashSeq);

            if ($hash != $posted_hash) {
                SwipezLogger::error(__CLASS__, '[Ex018]Error while handeling PAYU Invalid hash Error: for Transaction id[' . $pg_transaction_id . '] ');
            } else {
            }

            if ($post['txnid'][0] == "X") {
                $post['MerchantRefNo'] = $post['txnid'];
                $post['Amount'] = $post['amount'];
                $result = $this->savePayuResponse($post, $user_id);
                return $result;
            } else {
                SwipezLogger::error(__CLASS__, '[Ex019]Error invalid transaction id ' . $post['txnid'][0]);
                header('Location:/error');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex020]Error while handeling payment gateway response Error: for payment transaction id [' . $post['txnid'][0] . ']' . $e->getMessage());
            $this->setGenericError();
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
                    ':PG_TYPE' => $response['PG_TYPE'], ':encryptedPaymentId' => $response['encryptedPaymentId'], ':bank_ref_num' => $response['bank_ref_num'], ':bankcode' => $response['bankcode'],
                    ':error' => $response['error'], ':error_Message' => $response['error_Message'], ':cardToken' => $response['cardToken'], ':name_on_card' => $response['name_on_card'],
                    ':cardnum' => $response['cardnum'], ':cardhash' => $response['cardhash'], ':card_merchant_param' => $response['card_merchant_param'], ':amount_split' => $response['amount_split'], ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank4 set `status`=:status,field9=:field9,error_Message=:error_Message where payuMoneyId=:payuMoneyId";
                $params = array(':payuMoneyId' => $response['payuMoneyId'], ':status' => $response['status'], ':field9' => $response['field9'], ':error_Message' => $response['error_Message']);
            }

            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex021]Error while saving PAYU response Error: for param [' . json_encode($params) . ']' . $e->getMessage());
        }
        if ($response['status'] == 'success') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }
        $response = $this->savePaymentResponse($response, $response['firstname'] . ' ' . $response['lastname'], $response['email'], $response['addedon'], $response['txnid'], $response['payuMoneyId'], $response['mihpayid'], $response['amount'], $response['mode'], $response['error_Message'], $response['status'], $user_id, $response['mode']);
        return $response;
    }

    public function saveRazorpayResponse($data, $transaction_id, $name)
    {
        try {
            $datetime = date('Y-m-d H:i:s');
            $amount = $data->amount / 100;
            $exist = $this->existRetBank('payment_id', 'pg_ret_bank9', $data->id);
            if ($exist == false) {
                $sql = "INSERT INTO `pg_ret_bank9`(`payment_id`,`entity`,`currency`,`status`,`order_id`,`amount`,`card_id`,`bank`,`wallet`,`vpa`,`email`,`contact`,`fee`,`tax`,`method`,`error_description`,`txTime`,`error_code`,`created_date`)
VALUES(:payment_id,:entity,:currency,:status,:order_id,:amount,:card_id,:bank,:wallet,:vpa,:email,:contact,:fee,:tax,:method,:error_description,:txTime,:error_code,:created_date);";
                $params = array(
                    ':payment_id' => $data->id, ':entity' => $data->entity, ':currency' => $data->currency, ':status' => $data->status,
                    ':order_id' => $data->order_id, ':amount' => $amount, ':card_id' => $data->card_id, ':bank' => $data->bank, ':wallet' => $data->wallet, ':vpa' => $data->vpa, ':email' => $data->email,
                    ':contact' => $data->contact, ':fee' => $data->fee / 100, ':tax' => $data->tax / 100, ':method' => $data->method, ':error_description' => $data->error_description, ':error_code' => $data->error_code, ':txTime' => $datetime, ':created_date' => $datetime
                );
            } else {
                $sql = "update pg_ret_bank9 set `status`=:status where payment_id=:id";
                $params = array(':status' => $data->status, ':id' => $data->id);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E509]Error while saving razorpay ret bank detail for transaction_id[' . $transaction_id . ']' . $e->getMessage());
        }

        if ($data->status == 'captured' || $data->status == 'authorized') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }
        $response = $this->savePaymentResponse($response, $name, $data->email, $datetime, $transaction_id, $data->order_id, $data->id, $amount, 9998, '', $response['status'], '', $data->method);
        return $response;
    }

    public function saveStripeResponse($data, $transaction_id, $name)
    {
        try {
            $datetime = date('Y-m-d H:i:s');
            $amount = $data->amount / 100;
            $exist = $this->existRetBank('payment_intent_id', 'pg_ret_bank10', $data->id);
            if ($exist == false) {
                $sql = "insert INTO `pg_ret_bank10`(`payment_intent_id`,`stripe_customer_id`,`amount`,`destination`,`currency`,`created_date`,`payment_method`, `stripe_charge_id`, `stripe_transaction_id`, `receipt_url`, `status`)values(:PaymentIntentId,:CustomerId,:Amount,:Destination,:Currency,CURRENT_TIMESTAMP(),:PaymentMethod,:ChargeId,:TransactionId,:ReceiptURL,:Status)";
                $params = array(':PaymentIntentId' => $data->id, ':CustomerId' => $data->customer, ':Amount' => $data->amount / 100, ':Destination' => $data->transfer_data->destination, ':Currency' => $data->currency, ':PaymentMethod' => $data->charges->data[0]->payment_method_details->type, ':ChargeId' => $data->charges->data[0]->id, ':TransactionId' => $data->charges->data[0]->transfer, ':ReceiptURL' => $data->charges->data[0]->receipt_url, ':Status' => $data->status);
            } else {
                $sql = "update pg_ret_bank10 set `status`=:status where payment_intent_id=:id";
                $params = array(':status' => $data->status, ':id' => $data->id);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E509]Error while saving stripe ret bank detail for transaction_id[' . $transaction_id . ']' . $e->getMessage());
        }

        if ($data->status == 'succeeded') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }
        $response = $this->savePaymentResponse($response, $name, $data->charges->data[0]->billing_details->email, $datetime, $transaction_id, $data->id, $data->id, $data->amount, 9998, '', $response['status'], '', $data->charges->data[0]->payment_method_details->type);
        return $response;
    }

    public function saveAtomResponse($response, $user_id)
    {
        try {

            $exist = $this->existRetBank('mmp_txn', 'pg_ret_bank5', $response['mmp_txn']);
            if ($exist == false) {


                $sql = "INSERT INTO `pg_ret_bank5`(`mmp_txn`,`mer_txn`,`amt`,`prod`,`date`,`bank_txn`,`f_code`,`clientcode`,`bank_name`,`auth_code`,`ipg_txn_id`,`merchant_id`,`desc`,`udf9`,`discriminator`,`surcharge`,`CardNumber`,`udf1`,`udf2`,`udf3`,`udf4`,`udf5`,`udf6`,`created_date`,`created_by`)
                VALUES(:mmp_txn,:mer_txn,:amt,:prod,now(),:bank_txn,:f_code,:clientcode,:bank_name,:auth_code,:ipg_txn_id,:merchant_id,:desc,:udf9,:discriminator,:surcharge,:CardNumber,:udf1,:udf2,:udf3,:udf4,:udf5,:udf6,now(),:user_id);";

                $params = array(
                    ':mmp_txn' => $response['mmp_txn'], ':mer_txn' => $response['mer_txn'], ':amt' => $response['amt'],
                    ':prod' => $response['prod'], ':bank_txn' => $response['bank_txn'], ':f_code' => $response['f_code'], ':clientcode' => $response['clientcode'], ':bank_name' => $response['bank_name'],
                    ':auth_code' => $response['auth_code'],
                    ':ipg_txn_id' => $response['ipg_txn_id'], ':merchant_id' => $response['merchant_id'], ':desc' => $response['desc'], ':udf9' => $response['udf9'],
                    ':discriminator' => $response['discriminator'], ':surcharge' => $response['surcharge'], ':CardNumber' => $response['CardNumber'], ':udf1' => $response['udf1'], ':udf2' => $response['udf2'],
                    ':udf3' => $response['udf3'], ':udf4' => $response['udf4'], ':udf5' => $response['udf5'], ':udf6' => $response['udf6'], ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank5 set `f_code`=:f_code,`desc`='Reconcile Status changed' where mmp_txn=:mmp_txn";
                $params = array(':mmp_txn' => $response['mmp_txn'], ':f_code' => $response['f_code']);
            }
            if ($response['f_code'] != 'C') {
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex022]Error while saving ATOM request response Error: for param [' . json_encode($params) . ']' . $e->getMessage());
        }

        if ($response['f_code'] == 'Ok') {
            $response['status'] = 'success';
        } elseif ($response['f_code'] == 'C') {
            $response['status'] = 'canceled';
        } else {
            $response['status'] = 'failed';
        }
        $date = date("Y-m-d h:i:s");
        $response = $this->savePaymentResponse($response, $response['udf1'], $response['udf2'], $date, $response['mer_txn'], $response['mmp_txn'], $response['ipg_txn_id'], $response['amt'], $response['discriminator'], $response['desc'], $response['status'], $user_id, $response['discriminator']);
        return $response;
    }

    public function savePaytmResponse($patron_name, $email, $mid, $order_id, $amount, $currency, $txn_id, $banktxn_id, $status, $resp_code, $resp_message, $txn_date, $gateway_name, $bank_name, $payment_mode, $checksum, $pg_type, $user_id)
    {
        try {
            $exist = $this->existRetBank('MID', 'pg_ret_bank3', $mid);
            if ($exist == FALSE) {
                $sql = "insert INTO `pg_ret_bank3`(`MID`,`ORDERID`,`TXNAMOUNT`,`CURRENCY`,`TXNID`,`BANKTXNID`,`STATUS`,`RESPCODE`,`RESPMSG`,`TXNDATE`,`GATEWAYNAME`,`BANKNAME`,`PAYMENTMODE`,`CHECKSUMHASH`,`payment_method`,created_by,created_date)VALUES
                (:MID,:ORDERID,:TXNAMOUNT,:CURRENCY,:TXNID, :BANKTXNID,:STATUS,:RESPCODE,:RESPMSG,:TXNDATE,:GATEWAYNAME,:BANKNAME, :PAYMENTMODE,:CHECKSUMHASH,:payment_method,:user_id,CURRENT_TIMESTAMP())";
                $params = array(':MID' => $mid, ':ORDERID' => $order_id, ':TXNAMOUNT' => $amount, ':CURRENCY' => $currency, ':TXNID' => $txn_id, ':BANKTXNID' => $banktxn_id, ':STATUS' => $status, ':RESPCODE' => $resp_code, ':RESPMSG' => $resp_message, ':TXNDATE' => $txn_date, ':GATEWAYNAME' => $gateway_name, ':BANKNAME' => $bank_name, ':PAYMENTMODE' => $payment_mode, ':CHECKSUMHASH' => $checksum, ':payment_method' => 9999, ':user_id' => $user_id);
            } else {
                $sql = "update pg_ret_bank3 set BANKTXNID=:BANKTXNID,STATUS=:STATUS,RESPCODE=:RESPCODE,RESPMSG=:RESPMSG where MID=:MID";
                $params = array(':MID' => $mid, ':BANKTXNID' => $banktxn_id, ':STATUS' => $status, ':RESPCODE' => $resp_code, ':RESPMSG' => $resp_message);
            }
            $this->db->exec($sql, $params);

            if ($status == 'TXN_SUCCESS') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }

            $response = $this->savePaymentResponse($response, $patron_name, $email, $txn_date, $order_id, $txn_id, $banktxn_id, $amount, $payment_mode, $resp_message, $response['status'], $user_id, 'PAYTM');
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E509]Error while saving Paytm response from EBS list Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveCashfreeResponse($patron_name, $email, $orderId, $orderAmount, $referenceId, $txStatus, $paymentMode, $txMsg, $txTime, $signature, $user_id)
    {
        try {

            $exist = $this->existRetBank('orderId', 'pg_ret_bank7', $orderId);
            if ($exist == FALSE) {

                $sql = "INSERT INTO `pg_ret_bank7`(`orderId`,`orderAmount`,`referenceId`,`txStatus`,`paymentMode`,`txMsg`,`txTime`,`signature`,`email`,`created_date`,`created_by`)
VALUES(:orderId,:orderAmount,:referenceId,:txStatus,:paymentMode,:txMsg,:txTime,:signature,:email,CURRENT_TIMESTAMP(),:user_id);";
                $params = array(
                    ':orderId' => $orderId, ':orderAmount' => $orderAmount, ':referenceId' => $referenceId, ':txStatus' => $txStatus, ':paymentMode' => $paymentMode, ':txMsg' => $txMsg, ':txTime' => $txTime, ':signature' => $signature, ':email' => $email, ':user_id' => $user_id
                );
            } else {
                $sql = "update pg_ret_bank7 set txStatus=:txStatus,paymentMode=:paymentMode,txMsg=:txMsg where orderId=:orderId";
                $params = array(':txStatus' => $txStatus, ':paymentMode' => $paymentMode, ':txMsg' => $txMsg, ':orderId' => $orderId);
            }
            $this->db->exec($sql, $params);

            if ($txStatus == 'SUCCESS') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }
            $response = $this->savePaymentResponse($response, $patron_name, $email, $txTime, $orderId, $referenceId, $referenceId, $orderAmount, $paymentMode, $txMsg, $response['status'], $user_id, $paymentMode);
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E509]Error while saving cashfree response from EBS list Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function saveTechProcessResponse($patron_name, $email, $data, $status, $user_id)
    {
        try {
            $mobile = substr($data[7], -11, 10);
            $date = new DateTime($data[8]);
            $txndate = $date->format('Y-m-d H:i:00');
            $sql = "INSERT INTO `pg_ret_bank6`(`tpsl_txn_id`,`txn_status`,`txn_msg`,`txn_err_msg`,`clnt_txn_ref`,`tpsl_bank_cd`,`txn_amt`,
                `email`,`mobile`,`tpsl_txn_time`,`bal_amt`,`card_id`,`alias_name`,`BankTransactionID`,`mandate_reg_no`,`ipg_txn_id`,`token`
                ,`hash`,`merchant_id`,`created_date`,`created_by`)VALUES(:tpsl_txn_id,:txn_status,:txn_msg,:txn_err_msg,:clnt_txn_ref,:tpsl_bank_cd
                ,:txn_amt,:email,:mobile,:tpsl_txn_time,:bal_amt,:card_id,:alias_name,:BankTransactionID,:mandate_reg_no,:ipg_txn_id,:token,:hash,:merchant_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':tpsl_txn_id' => $data[5], ':txn_status' => $data[0], ':txn_msg' => $data[1], ':txn_err_msg' => $data[2],
                ':clnt_txn_ref' => $data[3], ':tpsl_bank_cd' => $data[4], ':txn_amt' => $data[6], ':email' => $email, ':mobile' => $mobile,
                ':tpsl_txn_time' => $txndate, ':bal_amt' => $data[9], ':card_id' => $data[10], ':alias_name' => $data[11], ':BankTransactionID' => $data[12],
                ':mandate_reg_no' => $data[13], ':ipg_txn_id' => '', ':token' => $data[14], ':hash' => $data[15],
                ':merchant_id' => '', ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            $response['status'] = $status;
            $response = $this->savePaymentResponse($response, $patron_name, $email, $txndate, $data[3], $data[5], $data[5], $data[6], 9997, $data[1], $response['status'], $user_id);
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E509+tech]Error while saving techprocess response Error: for param[' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function savePaymentResponse($response, $name, $email, $date, $transaction_id, $payment_id, $pg_transaction_id, $amount, $payment_method, $message, $status, $user_id, $discriminator = '')
    {
        try {

            if ($response['status'] == 'success') {
                SwipezLogger::info(__CLASS__, 'Payment completed : Transaction id: ' . $transaction_id . ' , amount: ' . $amount);
            } else {
                SwipezLogger::info(__CLASS__, 'Failure received from PG: Transaction id: ' . $transaction_id . ' , amount: ' . $amount);
            }

            $sql = "call save_xway_payment_response(:transaction_id,:payment_id,:pg_transaction_id,:amount,:payment_method,:message,:status,:payment_mode)";
            $params = array(':transaction_id' => $transaction_id, ':payment_id' => $payment_id, ':pg_transaction_id' => $pg_transaction_id, ':amount' => $amount, ':payment_method' => $payment_method, ':message' => $message, ':status' => $status, ':payment_mode' => $discriminator);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex023]Error while saving payment request response Error: for param [' . json_encode($params) . ']' . $e->getMessage());
            return $response;
        }
    }

    public function getMerchantLOGO($merchant_id)
    {
        try {
            $sql = "select logo from merchant_landing where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['logo'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[Ex036]Error while merchant logo Error:  param [' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function getMerchantPG($merchant_id)
    {
        try {
            $sql = "select pg_type,f.pg_id,f.xway_merchant_detail_id,f.xway_merchant_detail_id as fee_detail_id,pg_val4 from xway_merchant_detail f inner join payment_gateway g on f.pg_id=g.pg_id where  merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E106+98]Error while fetching pg fee details. merchant id ' . $merchant_id . ' Error: ' . $e->getMessage());
        }
    }

    function existRetBank($column, $table, $value)
    {
        try {
            $sql = "select " . $column . " from " . $table . " where " . $column . "=:id";
            $params = array(':id' => $value);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E275879]Error while checking retbank exist Transaction id' . $value . $e->getMessage());
        }
    }

    function saveAutoInvoiceApiRequest($transaction_id = null, $api_request_json, $merchant_id)
    {
        try {
            $sql = "INSERT INTO `auto_invoice_api_request`(`transaction_id`,`merchant_id`,`api_request_json`,`status`,`payment_request_id`,`created_date`)
                    VALUES(:transaction_id,:merchant_id,:api_request_json,0,NULL,CURRENT_TIMESTAMP());";

            $params = array(
                ':transaction_id' => $transaction_id, ':merchant_id' => $merchant_id, ':api_request_json' => $api_request_json
            );

            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
            return $id;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E509]Error while saving Auto invoice api request: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    function handleEasebuzzGatewayResponse($patron_name, $email, $post, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL) {
        try {
            $exist = $this->existRetBank('txn_id', 'pg_ret_bank12', $pg_transaction_id);
            
            if ($exist == FALSE) {
                $sql = "INSERT into `pg_ret_bank12` (`txn_id`, `easepayid`, `amount`, `cash_back_percentage`, `deduction_percentage`, `card_type`, 
                `mode`, `name`, `email`, `status`, `pg_type`, `addedon`)
                 values (:txn_id,:easepayid,:amount,:cash_back_percentage,:deduction_percentage,
                 :card_type,:mode,:name,:email,:status,:pg_type,:addedon)";

                $params = array(
                    ':txn_id' => $post["txnid"],
                    ':easepayid' => $post["easepayid"],
                    ':amount' => $post["amount"],
                    ':cash_back_percentage' =>  $post["cash_back_percentage"],
                    ':deduction_percentage' =>  $post["deduction_percentage"],
                    ':card_type' =>  $post["card_type"],
                    ':mode' => $post["mode"],
                    ':name' =>  $post["firstname"],
                    ':email' => $post["email"],
                    ':status' => $post["status"],
                    ':pg_type' => $post["PG_TYPE"],
                    ':addedon' => $post["addedon"],
                );
            } else {
                $sql = "update pg_ret_bank12 set status=:status where txn_id=:txn_id";
                $params = array(
                    ':status' => $post['status'],
                    ':mode' => $post['mode'],
                    ':txn_id' => $pg_transaction_id
                );
            }
            $this->db->exec($sql, $params);
            $response['status'] = $post["status"];
            
            $response = $this->savePaymentResponse($response,$patron_name,$email,$post["addedon"],
                $pg_transaction_id,$post["easepayid"],$pg_transaction_id,$post["amount"],9992,$post["error_Message"],
                $post["status"],$user_id,$post['mode']
            );
            $response['type'] = 'request';
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handling easebuzz payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }
}
