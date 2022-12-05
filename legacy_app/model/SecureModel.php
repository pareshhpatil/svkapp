<?php

class SecureModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function handlePaymentGatewayResponse($link, $user_id, $pg_type, $pg_transaction_id)
    {
        try {
            $row = $this->getPaymentGatewayDetails($pg_type, $pg_transaction_id);
            $secret_key = $row['pg_val2'];
            require(UTIL . 'Rc43.php');
            $DR = preg_replace("/\s/", "+", $link);
            $rc4 = new Crypt_RC4($secret_key);
            $QueryString = base64_decode($DR);
            $rc4->decrypt($QueryString);
            $QueryString = explode('&', $QueryString);
            $response = array();
            foreach ($QueryString as $param) {
                $param = explode('=', $param);
                $response[$param[0]] = urldecode($param[1]);
            }
            $res = array();
            foreach ($response as $key => $value) {
                $res[$key] = $value;
            }
            if ($res['ResponseCode'] == 0) {
                $res['status'] = 'success';
            } else {
                $res['status'] = 'failed';
            }
            $res['email'] = $res['BillingEmail'];
            $res['amount'] = $res['Amount'];
            $result = $this->savePaymentRequestResponse($res, $user_id);
            $result['type'] = 'request';
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }

    public function handlePayuResponse($post, $user_id, $pg_type, $pg_transaction_id)
    {
        try {
            $row = $this->getPaymentGatewayDetails($pg_type, $pg_transaction_id);
            $status = $post["status"];
            $udf1 = $post["udf1"];
            $udf2 = $post["udf2"];
            $firstname = $post["firstname"];
            $amount = $post["amount"];
            $txnid = $post["txnid"];
            $posted_hash = $post["hash"];
            $key = $post["key"];
            $productinfo = $post["productinfo"];
            $email = $post["email"];
            $salt = $row['pg_val2'];

            if (isset($post["additionalCharges"])) {
                $additionalCharges = $post["additionalCharges"];
                $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            } else {

                $retHashSeq = $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
            }
            $hash = hash("sha512", $retHashSeq);

            if ($hash != $posted_hash) {
                SwipezLogger::error(__CLASS__, '[E9945]Error while handeling PAYU Invalid hash Error: for Transaction id[' . $pg_transaction_id . '] ');
            } else {
            }
            $post['MerchantRefNo'] = $post['txnid'];
            $post['Amount'] = $post['amount'];
            if ($post['status'] == 'success') {
                $post['status'] = 'success';
            } else {
                $post['status'] = 'failed';
            }
            $post['merchant_name'] = $post['udf2'];
            $result = $this->savePayuResponse($post, $user_id);
            $result['type'] = 'request';
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }

    public function handleAtomResponse($post, $user_id, $pg_type, $pg_transaction_id)
    {
        try {
            $post['MerchantRefNo'] = $post['mer_txn'];
            $post['Amount'] = $post['amt'];
            if ($post['f_code'] == 'Ok') {
                $post['status'] = 'success';
            } else {
                $post['status'] = 'failed';
            }
            $post['email'] = $post['udf2'];
            $post['amount'] = $post['amt'];
            $result = $this->saveAtomResponse($post, $user_id);
            $result['type'] = 'request';
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }

    public function handlePaytmGatewayResponse($patron_name, $email, $mid = NULL, $order_id = NULL, $amount = 0, $currency = NULL, $txn_id = NULL, $banktxn_id = NULL, $status = NULL, $resp_code = NULL, $resp_message = NULL, $txn_date = NULL, $gateway_name = NULL, $bank_name = NULL, $payment_mode = NULL, $checksum = NULL, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL)
    {
        try {
            $res = array();
            $res['MerchantRefNo'] = $order_id;
            $res['Amount'] = $amount;
            $res['amount'] = $amount;
            if ($status == 'TXN_SUCCESS') {
                $res['status'] = 'success';
            } else {
                $res['status'] = 'failed';
            }
            $pg_type = 'payment_request';
            $result = $this->savePaytmResponse($patron_name, $email, $mid, $order_id, $amount, $currency, $txn_id, $banktxn_id, $status, $resp_code, $resp_message, $txn_date, $gateway_name, $bank_name, $payment_mode, $checksum, $pg_type, $user_id);
            $result['type'] = 'request';
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
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
            $pg_type = 'payment_request';
            $result = $this->saveCashfreeResponse($patron_name, $email, $post['orderId'], $post['orderAmount'], $post['referenceId'], $post['txStatus'], $post['paymentMode'], $post['txMsg'], $post['txTime'], $post['signature'], $user_id);
            $result['type'] = 'request';
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }

    function existSetuBankDetails($billerBillID)
    {
        try {
            $sql = "select * from pg_ret_bank11 where billerBillID =:id";
            $params = array(':id' => $billerBillID);
            $this->db->exec($sql, $params);

            $row = $this->db->single();
            return  $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E275879]Error while ge5tting setu details  Transaction id' . $billerBillID . $e->getMessage());
        }
    }

    public function handleSetuGatewayResponse($patron_name, $email, $post, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL)
    {
        try {
            $order_amount  = $post["orderAmount"];
            $exist = $this->existRetBank('billerBillID', 'pg_ret_bank11', $pg_transaction_id);
            $post = $this->existSetuBankDetails($pg_transaction_id);

            if ($post["status"] == 'PAYMENT_SUCCESSFUL') {
                $post['status'] = 'SUCCESS';
            }

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
                    ':amountPaid' => $order_amount,
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
                $sql = "update pg_ret_bank11 set status=:txStatus where billerBillID=:billerBillID";
                $params = array(
                    ':txStatus' => $post['status'],
                    ':billerBillID' => $pg_transaction_id
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
                $pg_transaction_id,
                $post['platformBillID'],
                $post['platformBillID'],
                $order_amount,
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

    public function handleTechProcessResponse($patron_name, $email, $data, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL)
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
            $pg_type = 'payment_request';
            $result = $this->saveTechProcessResponse($patron_name, $email, $data, $status, $user_id);
            $result['type'] = 'request';
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }

    public function savePayuResponse($response, $user_id)
    {
        try {
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

                SwipezLogger::error(__CLASS__, '[E195+29]Error while saving pg_ret_bank4 response from PAYU Error: for transaction id[' . $response['txnid'] . ']' . $e->getMessage());
            }

            $response['payment_mode'] = $response['mode'];
            if ($response['status'] != 'success') {
                $response['status'] = 'failed';
            }
            $response = $this->savePaymentResponse($response, $response['firstname'] . ' ' . $response['lastname'], $response['email'], $response['addedon'], $response['txnid'], $response['payuMoneyId'], $response['mihpayid'], $response['amount'], 9992, $response['field9'], $response['status'], $user_id, $response['mode']);
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E195+2]Error while saving PAYU response from PAYU Error: for transaction id[' . $response['txnid'] . '] Json ' . json_encode($response) . $e->getMessage());
        }
    }

    public function saveAtomResponse($response, $user_id)
    {
        try {
            if ($response['mmp_txn'] > 0) {

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
                $this->db->exec($sql, $params);
            }
            $response['payment_mode'] = $response['discriminator'];
            if ($response['f_code'] == 'Ok') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }
            $date = date("Y-m-d h:i:s");
            $response = $this->savePaymentResponse($response, $response['udf1'], $response['udf2'], $date, $response['mer_txn'], $response['mmp_txn'], $response['ipg_txn_id'], $response['amt'], 9996, $response['desc'], $response['status'], $user_id, $response['discriminator']);
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E195]Error while saving ATOM request response from ATOM Error: for user id[' . $user_id . '] Json ' . json_encode($response) . $e->getMessage());
        }
    }

    public function savePaymentRequestResponse($response, $user_id)
    {
        try {
            $sql = "insert INTO `pg_ret_bank2`(`payment_id`,`pay_transaction_id`,`transaction_id`,`is_flagged`,`response_code`,`response_message`,
`date_created`,`amount`,`payment_method`,`mode`,`billing_name`,`billing_address`,`billing_city`,`billing_state`,`billing_postal_code`,`billing_country`,`billing_phone`,
`billing_email`,`delivery_name`,`delivery_address`,`delivery_city`,`delivery_state`,`delivery_postal_code`,`delivery_country`,`delivery_phone`,`created_by`,
`created_date`,`last_update_by`,`last_update_date`)values(:PaymentID,:MerchantRefNo,:TransactionID,:IsFlagged,:ResponseCode, :ResponseMessage,:DateCreated,:Amount,:payment_method
,:Mode,:BillingName,:BillingAddress, :BillingCity,:BillingState,:BillingPostalCode,:BillingCountry,:BillingPhone,:BillingEmail, :DeliveryName,:DeliveryAddress,:DeliveryCity
,:DeliveryState,:DeliveryPostalCode,:DeliveryCountry,:DeliveryPhone,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP())";
            $params = array(':PaymentID' => $response['PaymentID'], ':MerchantRefNo' => $response['MerchantRefNo'], ':TransactionID' => $response['TransactionID'], ':IsFlagged' => $response['IsFlagged'], ':ResponseCode' => $response['ResponseCode'], ':ResponseMessage' => $response['ResponseMessage'], ':DateCreated' => $response['DateCreated'], ':Amount' => $response['Amount'], ':payment_method' => $response['PaymentMethod'], ':Mode' => $response['Mode'], ':BillingName' => $response['BillingName'], ':BillingAddress' => $response['BillingAddress'], ':BillingCity' => $response['BillingCity'], ':BillingState' => $response['BillingState'], ':BillingPostalCode' => $response['BillingPostalCode'], ':BillingCountry' => $response['BillingCountry'], ':BillingPhone' => $response['BillingPhone'], ':BillingEmail' => $response['BillingEmail'], ':DeliveryName' => $response['DeliveryName'], ':DeliveryAddress' => $response['DeliveryAddress'], ':DeliveryCity' => $response['DeliveryCity'], ':DeliveryState' => $response['DeliveryState'], ':DeliveryPostalCode' => $response['DeliveryPostalCode'], ':DeliveryCountry' => $response['DeliveryCountry'], ':DeliveryPhone' => $response['DeliveryPhone'], ':user_id' => $user_id);
            $this->db->exec($sql, $params);

            if ($response['ResponseCode'] == 0) {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }

            $response = $this->savePaymentResponse($response, $response['BillingName'], $response['BillingEmail'], $response['DateCreated'], $response['MerchantRefNo'], $response['PaymentID'], $response['TransactionID'], $response['Amount'], $response['PaymentMethod'], $response['ResponseMessage'], $response['status'], $user_id);
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E195-1]Error while saving EBS request response from EBS Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function savePaymentResponse($response, $name, $email, $date, $transaction_id, $payment_id, $pg_transaction_id, $amount, $payment_method, $message, $status, $user_id, $discriminator = '')
    {
        try {
            $type = 'payment_request';
            if (substr($transaction_id, 0, 1) == 'F') {
                $type = 'package';
            }

            if (!isset($user_id)) {
                $user_id = 'User';
            }

            $sql = "call insert_payment_response(:type,:transaction_id,:payment_id,:pg_transaction_id,:amount,:payment_method,:payment_mode,:message,:status,:user_id)";
            $params = array(
                ':type' => $type, ':transaction_id' => $transaction_id, ':payment_id' => $payment_id,
                ':pg_transaction_id' => $pg_transaction_id, ':amount' => $amount, ':payment_method' => $payment_method, ':payment_mode' => $discriminator, ':message' => $message, ':status' => $status, ':user_id' => $user_id
            );

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
                foreach ($row as $key => $value) {
                    $response[$key] = $value;
                }
                $response['merchant_name'] = $row['company_name'];
                $response['Amount'] = $amount;
                $response['transaction_id'] = $transaction_id;
                $response['payment_mode'] = $row['payment_mode'] . ' ' . $discriminator;
                $response['BillingName'] = $name;
                $response['BillingEmail'] = $email;
                $response['TransactionID'] = $pg_transaction_id;
                $response['MerchantRefNo'] = $transaction_id;
                $response['DateCreated'] = $date;
            } else {
                SwipezLogger::error(__CLASS__, '[E96195]Error while updating payment response status Transaction_id: ' . $transaction_id . ' payment status: ' . $status . ' Error:' . json_encode($row));
            }
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E195+1]Error while saving payment request response from PG Error: for user id[' . $user_id . ']' . $e->getMessage());
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

            $response = $this->savePaymentResponse($response, $response['name'], $response['email'], date('Y-m-d h:i'), $response['transaction_id'], $response['transaction_id'], time(), $amount, 9991, '', $response['status'], 'Guest');
            $response['type'] = 'request';
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handeling payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
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
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E509]Error while saving Paytm response from EBS list Error: for user id[' . $user_id . ']' . $e->getMessage());
        }

        if ($status == 'TXN_SUCCESS') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        $response = $this->savePaymentResponse($response, $patron_name, $email, $txn_date, $order_id, $txn_id, $banktxn_id, $amount, 9999, $resp_message, $response['status'], $user_id, 'PAYTM');
        return $response;
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

    public function saveCashfreeResponse($patron_name, $email, $orderId, $orderAmount, $referenceId, $txStatus, $paymentMode, $txMsg, $txTime, $signature, $user_id)
    {
        try {
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
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[E509]Error while saving cashfree response Error: for transaction id[' . $orderId . ']' . $e->getMessage());
            }

            if ($txStatus == 'SUCCESS') {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'failed';
            }

            $response = $this->savePaymentResponse($response, $patron_name, $email, $txTime, $orderId, $referenceId, $referenceId, $orderAmount, 9998, $txMsg, $response['status'], $user_id, $paymentMode);
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E509]Error while handle cashfree response Error: for transaction id[' . $orderId . ']' . $e->getMessage());
        }
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

        if ($data->status == 'captured') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }
        $response = $this->savePaymentResponse($response, $name, $data->email, $datetime, $transaction_id, $data->order_id, $data->id, $amount, $data->method, '', $response['status'], '', $data->method);
        return $response;
    }

    public function saveStripeResponse($payment_intent)
    {
        try {
            $datetime = date('Y-m-d H:i:s');
            $exist = $this->existRetBank10($payment_intent->id);
            if ($exist == false) {
                $sql = "insert INTO `pg_ret_bank10`(`payment_intent_id`,`stripe_customer_id`,`amount`,`destination`,`currency`,`created_date`,`payment_method`, `stripe_charge_id`, `stripe_transaction_id`, `receipt_url`,`status`)values(:PaymentIntentId,:CustomerId,:Amount,:Destination,:Currency,CURRENT_TIMESTAMP(),:PaymentMethod,:ChargeId,:TransactionId,:ReceiptURL,:Status)";
                $params = array(':PaymentIntentId' => $payment_intent->id, ':CustomerId' => $payment_intent->customer, ':Amount' => $payment_intent->amount / 100, ':Destination' => $payment_intent->transfer_data->destination, ':Currency' => $payment_intent->currency, ':PaymentMethod' => $payment_intent->charges->data[0]->payment_method_details->type, ':ChargeId' => $payment_intent->charges->data[0]->id, ':TransactionId' => $payment_intent->charges->data[0]->transfer, ':ReceiptURL' => $payment_intent->charges->data[0]->receipt_url, ':Status' => $payment_intent->status);
            } else {
                $sql = "update pg_ret_bank10 set `status` = :status where payment_intent_id=:id";
                $params = array(':status' => $payment_intent->status, ':id' => $payment_intent->id);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E195-1]Error while saving EBS request response from EBS Error:' . $e->getMessage());
        }
        if ($payment_intent->status == 'succeeded') {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

        $response = $this->savePaymentResponse($response, $payment_intent->charges->data[0]->billing_details->name, $payment_intent->charges->data[0]->billing_details->email, $datetime, $payment_intent->metadata->transaction_id, $payment_intent->id, $payment_intent->id, $payment_intent->amount / 100, 9998, '', $response['status'], null, $payment_intent->charges->data[0]->payment_method_details->type);
        return $response;
    }

    public function savePayoneerResponse($audit_id, $transaction_id, $amount, $mode)
    {
        $datetime = date('Y-m-d H:i:s');
        //if ($payment_intent->status == 'succeeded') {
        $response['status'] = 'success';
        //  } else {
        //    $response['status'] = 'failed';
        //  }

        $response = $this->savePaymentResponse($response, '', '', $datetime, $transaction_id, $audit_id, $audit_id, $amount, 9998, '', $response['status'], null, $mode);
        return $response;
    }

    public function existRetBank10($id)
    {
        try {
            $sql = "select payment_intent_id from pg_ret_bank10 where payment_intent_id =:id";
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E275879]Error while checking retbank exist Transaction id' . $id . $e->getMessage());
        }
    }

    public function validatePaymentRequestResponse($response, $user_id, $pg_type)
    {
        try {
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E202]Error while validating payment gateway response Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function getPaymentGatewayDetails($pg_type, $pg_transaction_id)
    {
        try {
            $pg_id = $this->getTransactionPGid($pg_type, $pg_transaction_id);
            $sql = "select * from payment_gateway where pg_id=:pg_id";
            $params = array(':pg_id' => $pg_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E204]Error while getting payment gateway details Error: for payment transaction id[' . $pg_transaction_id . '] ' . 'Type:' . $pg_type . $e->getMessage());
        }
    }

    function getTransactionPGid($pg_type, $pg_transaction_id)
    {
        try {
            $pg_type = substr($pg_transaction_id, 0, 1);
            if ($pg_type == 'F') {
                $sql = "select pg_id from package_transaction where package_transaction_id=:transaction_id";
            } else if ($pg_type == 'T') {
                $sql = "select pg_id from payment_transaction where pay_transaction_id=:transaction_id";
            } else {
                $sql = "select pg_id from xway_transaction where xway_transaction_id=:transaction_id";
            }
            $params = array(':transaction_id' => $pg_transaction_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['pg_id'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E204]Error while getting payment gateway details Error: for payment transaction id[' . $pg_transaction_id . '] ' . 'Type:' . $pg_type . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateMerchantStatus($user_id)
    {
        try {
            $sql = "update merchant set merchant_type=2 , merchant_status=4 where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E205]Error while updating merchant status Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function updateSubscriptionStatus($status, $subscription_id, $ref_no, $ref_1, $transaction_id)
    {
        try {
            $sql = "update paytm_subscription set subscription_status=:status,paytm_subscription_id=:subscription_id,pg_ref_no=:ref_no,pg_ref_1=:ref_1,last_paid_date=now()  where transaction_id=:transaction_id";
            $params = array(':status' => $status, ':subscription_id' => $subscription_id, ':ref_no' => $ref_no, ':ref_1' => $ref_1, ':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E205879]Error while updating Subscription Status Error: for transaction id[' . $transaction_id . ']' . $e->getMessage());
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

    public function getMobileNo($user_id)
    {
        try {
            $sql = "select mobile_no from user where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (empty($result)) {
                $sql = "select mobile as mobile_no from customer where customer_id=:user_id";
                $params = array(':user_id' => $user_id);
                $this->db->exec($sql, $params);
                $result = $this->db->single();
            }
            return $result['mobile_no'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E206]Error while getting mobile number Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getTransactionDetail($transaction_id)
    {
        try {
            if (substr($transaction_id, 0, 1) == 'T') {
                $sql = "select pay_transaction_id,pg_id from payment_transaction where pay_transaction_id=:transaction_id";
            } else {
                $sql = "select xway_transaction_id as pay_transaction_id,pg_id,name,email,phone from xway_transaction where xway_transaction_id=:transaction_id";
            }
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            $sql = "select * from payment_gateway where pg_id=" . $result['pg_id'];
            $params = array();
            $this->db->exec($sql, $params);
            $pg = $this->db->single();
            $result['transaction_id'] = $result['pay_transaction_id'];
            $result['pg'] = $pg;
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E206]Error while getting mobile number Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }



    public function saveAutocollectTransaction($data, $merchant_id, $payment_transaction_id, $plan_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `autocollect_transaction`(`merchant_id`,`subscription_id`,`plan_id`,`payment_transaction_id`,`amount`,`description`,`status`,
            `pg_ref`,`pg_ref1`,`pg_ref2`,`pg_ref3`,`created_by`,`created_date`,`last_update_by`)
            VALUES(:merchant_id,:subscription_id,:plan_id,:payment_transaction_id,:amount,:description,:status,:pg_ref,:pg_ref1,:pg_ref2,:pg_ref3,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':merchant_id' => $merchant_id, ':subscription_id' => $data['cf_subscriptionId'], ':plan_id' => $plan_id, ':payment_transaction_id' => $payment_transaction_id, ':amount' => $data['cf_authAmount'], ':description' => $data['cf_message'], ':status' => $data['cf_status'], ':pg_ref' => $data['cf_referenceId'], ':pg_ref1' => $data['cf_orderId'], ':pg_ref2' => $data['cf_subReferenceId'], ':pg_ref3' => '', ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E206]Error while getting mobile number Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function handleEasebuzzGatewayResponse($patron_name, $email, $post, $user_id = NULL, $pg_type = NULL, $pg_transaction_id = NULL)
    {
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

            $response = $this->savePaymentResponse(
                $response,
                $patron_name,
                $email,
                $post["addedon"],
                $pg_transaction_id,
                $post["easepayid"],
                $pg_transaction_id,
                $post["amount"],
                9992,
                $post["error_Message"],
                $post["status"],
                $user_id,
                $post['mode']
            );
            $response['type'] = 'request';
            return $response;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E194]Error while handling easebuzz payment gateway response Error: for user id[' . $user_id . '] and payment transaction id [' . $pg_transaction_id . ']' . $e->getMessage());
        }
    }
}
