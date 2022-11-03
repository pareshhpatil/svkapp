<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class PaymentRequestModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch list of merchant associated with a user id
     * 
     * @return type
     */
    public function getMerchantList($userid)
    {
        try {
            $sql = "call get_merchant_cycledetail(:user_id,:user_type);";
            $params = array(':user_id' => $userid, ':user_type' => 'Request');
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E147]Error while fetching merchant list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getStatusList()
    {
        try {
            $sql = "select config_key, config_value from config where config_type=:type";
            $params = array(':type' => 'payment_request_status');
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E148]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    public function getPaymentRequestList($f_fromdate, $f_todate, $name, $user_id)
    {
        try {
            $converter = new Encryption;
            $sql = "call get_patron_viewrequest(:user_id,:from_date,:to_date,:name);";
            $params = array(':user_id' => $user_id, ':from_date' => $f_fromdate, ':to_date' => $f_todate, ':name' => $name);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['paylink'] = $this->app_url . '/patron/paymentrequest/view/' . $converter->encode($item['payment_request_id']);
                $int++;
            }
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E149]Error while getting payment request list Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getBankList()
    {
        try {
            $sql = "select config_key, config_value from config where config_type='Bank_name'";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E150]Error while fetching bank list Error: ' . $e->getMessage());
        }
    }

    public function getPatronDetails($patron_id)
    {
        try {
            $sql = "select u.user_id as patron_id,concat(u.first_name,' ',u.last_name) as name,u.email_id,u.mobile_no from user u where u.user_id=:patron_id";
            $params = array(':patron_id' => $patron_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E151-1]Error while getting patron details Error: for user id[' . $patron_id . ']' . $e->getMessage());
        }
    }

    public function updatePatrondetails($patron_id, $first_name, $last_name, $email, $mobile, $city, $address, $state, $zipcode)
    {
        try {
            $sql = "update non_registered_patron set first_name=:first_name,last_name=:last_name,email_id=:email,mobile_no=:mobile,city=:city,address1=:address,state=:state,zipcode=:zipcode where patron_id=:patron_id;";
            $params = array(':first_name' => $first_name, ':last_name' => $last_name, ':email' => $email, ':mobile' => $mobile, ':city' => $city, ':address' => $address, ':state' => $state, ':zipcode' => $zipcode, ':patron_id' => $patron_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E151-2]Error while updating patron details Error: for user id[' . $patron_id . ']' . $e->getMessage());
        }
    }

    public function validatePaymetLink($payment_request_id)
    {
        try {
            $sql = "select customer_id,payment_request_id,patron_type from payment_request where payment_request_id=:payment_request_id";
            $params = array(':payment_request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
            } else {
                return $row;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E152]Error while validate payment request link Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPaymentDetails($payment_req, $user_id)
    {
        try {
            $sql = "call `getuser_details`(:user_id,:payment_req_id)";
            $params = array(':user_id' => $user_id, ':payment_req_id' => $payment_req);
            $this->db->exec($sql, $params);
            $user_detail = $this->db->single();
            return $user_detail;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E155-1]Error while call getuser_details Error: for Req_id : ' . $payment_req . ' user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function intiatePaymentTransaction($payment_req, $payment_request_type, $customer_id, $patron_id, $user_id, $merchant_id, $amount, $convenience_fee, $discount, $deduct_amount, $deduct_text, $pg_id, $fee_id, $franchise_id = 0, $vendor_id = 0, $narrative = '', $quantity = 1, $currency = 'INR')
    {
        try {
            //TODO : This is a workaround to the problem of not being able to perform insert's or updates.
            $this->closeConnection();
            $this->db = new DBWrapper();

            $sql = "SELECT generate_sequence('Pay_Trans') as uid";
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $pay_id = isset($row['uid']) ? $row['uid'] : '';
            $currency = isset($currency) ? $currency : 'INR';
            $currency = ($currency != '') ? $currency : 'INR';
            $convenience_fee = ($convenience_fee > 0) ? $convenience_fee : 0;
            $fee_id = ($fee_id > 0) ? $fee_id : 0;
            $quantity = ($quantity > 0) ? $quantity : 1;
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $vendor_id = ($vendor_id > 0) ? $vendor_id : 0;
            $status = 0;
            $empty = NULL;
            $zero = 0;
            $sql = "INSERT INTO `payment_transaction`(`pay_transaction_id`,`payment_request_id`,`payment_request_type`,`customer_id`,`patron_user_id`,
            `merchant_user_id`,`merchant_id`,`quantity`,`amount`,`convenience_fee`,`discount`,`deduct_amount`,`deduct_text`,`payment_transaction_status`,`bank_status`,`pg_id`,`fee_id`,`franchise_id`,`vendor_id`,`pg_ref_no`, `pg_ref_1`,`pg_ref_2`,`paid_on`,`narrative`,`currency`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
            VALUES(:pay_transaction_id,:payment_request_id,:payment_request_type,:customer_id,:patron_user_id,:merchant_user_id,:merchant_id,:quantity,:amount,:convenience_fee,:discount,:deduct_amount,:deduct_text,:payment_transaction_status,:bank_status,:pg_id,:fee_id,:franchise_id,:vendor_id,:pg_ref_no,:pg_ref_1,:pg_ref_2,CURDATE(),:narrative,:currency,:user_id,
            CURRENT_TIMESTAMP(),:user_id2,CURRENT_TIMESTAMP())";
            $params = array(
                ':pay_transaction_id' => $pay_id, ':payment_request_id' => $payment_req, ':payment_request_type' => $payment_request_type,
                ':customer_id' => $customer_id, ':patron_user_id' => $patron_id, ':merchant_user_id' => $user_id, ':merchant_id' => $merchant_id, ':quantity' => $quantity, ':amount' => $amount, ':convenience_fee' => $convenience_fee, ':discount' => $discount, ':deduct_amount' => $deduct_amount, ':deduct_text' => $deduct_text,
                ':payment_transaction_status' => $status, ':bank_status' => $zero, ':pg_id' => $pg_id, ':fee_id' => $fee_id, ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id,
                ':pg_ref_no' => $empty, ':pg_ref_1' => $empty, ':pg_ref_2' => $empty, ':narrative' => $narrative, ':currency' => $currency, ':user_id' => $patron_id,
                ':user_id2' => $patron_id
            );

            $this->db->exec($sql, $params);
            $sql = "show errors";
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $pay_id;
        } catch (Exception $e) {
            dd($e);
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E156]Error while initiated payment request Error: for patron id[' . $patron_id . '] and for payment request id [' . $payment_req . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function respond($amount, $bank_name, $payment_req_id, $date, $response_type, $bankTransactionNo, $chequeNo, $cashPaidTo, $coupon_id, $discount, $user_id, $deduct_amount = 0, $deduct_text = '')
    {
        try {
            $payment_request_status = 2;
            $chequeNo = ($chequeNo > 0) ? $chequeNo : 0;
            switch ($response_type) {
                case 1:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    break;
                case 2:
                    $bankTransactionNo = '';
                    $cashPaidTo = '';
                    break;
                case 3:
                    $bankTransactionNo = '';
                    $chequeNo = 0;
                    break;
                case 5:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    break;
            }
            if (strlen($this->system_user_id) > 9) {
                $user_id = $this->system_user_id;
            }
            $sql = "call offlinerespond(:amount,:bank_name,:payment_request_id,:date,:bank_transaction_no,:response_type,:cheque_no
                ,:cash_paidto,:user_id,:payment_request_status,:coupon_id,:discount,:deduct_amount,:deduct_text,'')";

            $params = array(
                ':amount' => $amount, ':payment_request_id' => $payment_req_id,
                ':response_type' => $response_type, ':date' => $date, ':bank_transaction_no' => $bankTransactionNo,
                ':bank_name' => $bank_name, ':cheque_no' => $chequeNo, ':cash_paidto' => $cashPaidTo, ':user_id' => $user_id, ':payment_request_status' => $payment_request_status, ':coupon_id' => $coupon_id, ':discount' => $discount, ':deduct_amount' => $deduct_amount, ':deduct_text' => $deduct_text
            );

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E111]Error while respond payment request list Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function respondUpdate($amount, $bank_name, $OfflineRespondId, $date, $response_type, $bankTransactionNo, $chequeNo, $cashPaidTo, $user_id)
    {
        try {
            $this->closeConnection();
            $this->db = new DBWrapper();
            $chequeNo = ($chequeNo > 0) ? $chequeNo : 0;
            switch ($response_type) {
                case 1:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    break;
                case 2:
                    $bankTransactionNo = '';
                    $cashPaidTo = '';
                    break;
                case 3:
                    $bankTransactionNo = '';
                    $chequeNo = 0;
                    break;
                case 5:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    break;
            }
            if (strlen($this->system_user_id) > 9) {
                $update_by = $this->system_user_id;
            } else {
                $update_by = $user_id;
            }

            $sql = "UPDATE `offline_response` SET offline_response_type=:offline_response_type,settlement_date=:settlement_date,bank_transaction_no=:bankTransactionNo,cheque_no=:chequeNo,cash_paid_to=:cashPaidTo,bank_name=:bank_name,amount=:amount,last_update_by=:update_by WHERE offline_response_id=:offline_response_id;";
            $params = array(
                ':offline_response_type' => $response_type, ':settlement_date' => $date, ':bankTransactionNo' => $bankTransactionNo, ':chequeNo' => $chequeNo,
                ':cashPaidTo' => $cashPaidTo, ':amount' => $amount, ':bank_name' => $bank_name, ':offline_response_id' => $OfflineRespondId, ':update_by' => $update_by
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E144]Error while updating respond Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updatePaymentRequestStatus($payment_request_id, $customer_id, $patron_id, $status)
    {
        try {
            $sql = "update payment_request set payment_request_status=:status,last_update_by=:patron_id where payment_request_id=:request_id and customer_id=:customer_id";
            $params = array(':status' => $status, ':request_id' => $payment_request_id, ':patron_id' => $patron_id, ':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161]Error while updating payment request status Error: for payment request id[' . $payment_request_id . ']' . $e->getMessage());
        }
    }

    public function updatePaymentStatus($customer_id, $status)
    {
        try {
            $sql = "update customer set payment_status=:status where customer_id=:customer_id and payment_status =0;";
            $params = array(':status' => $status, ':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161]Error while updating payment status Error: for Customer id[' . $customer_id . ']' . $e->getMessage());
        }
    }

    public function getmaxfeeID($merchant_id)
    {
        try {
            $sql = "select fee_detail_id from merchant_fee_detail where merchant_id=:merchant_id and is_active=1 order by pg_fee_val desc limit 1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['fee_detail_id'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E110]Error while fetching bank list list Error: ' . $e->getMessage());
        }
    }
}
