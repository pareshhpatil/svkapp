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
    public function getCycleList($userid, $from_date, $to_date)
    {
        try {
            $sql = "select distinct billing_cycle_id as id,cycle_name as name from billing_cycle_detail where user_id=:user_id";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E107]Error while fetching cycle list Error:for user id[' . $userid . '] ' . $e->getMessage());
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

            SwipezLogger::error(__CLASS__, '[E108]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    public function isXwayActive($merchant_id)
    {
        try {
            $sql = "select count(xway_merchant_detail_id) as count from xway_merchant_detail where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $xway_detail = $this->db->single();
            $this->db->closeStmt();
            if ($xway_detail['count'] == 0) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
        }
    }

    public function isPaymentActive($merchant_id)
    {
        try {
            $sql = "select count(fee_detail_id) as count from merchant_fee_detail where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $payment_detail = $this->db->single();
            if ($payment_detail['count'] == 0) {
                return $this->isXwayActive($merchant_id);
            } else {
                return true;
            }
        } catch (Exception $e) {
        }
    }

    public function getPaymentRequestList($f_fromdate, $f_todate, $name, $user_id, $franchise_id = 0)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $sql = "call get_merchant_viewrequest(:user_id,:from_date,:to_date,:name,:bulk_upload,:payment_request_type,'',:franchise_id);";
            $params = array(':user_id' => $user_id, ':from_date' => $f_fromdate, ':to_date' => $f_todate, ':name' => $name, ':bulk_upload' => 0, ':payment_request_type' => 1, ':franchise_id' => $franchise_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E109]Error while getting payment request list Error:  for user id[' . $user_id . ']' . $e->getMessage());
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

            SwipezLogger::error(__CLASS__, '[E110]Error while fetching bank list list Error: ' . $e->getMessage());
        }
    }

    public function respond($amount, $bank_name, $payment_req_id, $date, $response_type, $bankTransactionNo, $chequeNo, $cashPaidTo, $coupon_id, $discount, $user_id, $deduct_amount = 0, $deduct_text = '', $cheque_status = '', $is_partial = 0, $cod_status = null)
    {
        try {

            $payment_request_status = 2;
            $chequeNo = ($chequeNo > 0) ? $chequeNo : 0;
            switch ($response_type) {
                case 1:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    $cheque_status = '';
                    break;
                case 2:
                    $bankTransactionNo = '';
                    $cashPaidTo = '';
                    break;
                case 3:
                    $chequeNo = 0;
                    $cheque_status = '';
                    break;
                case 5:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    $cheque_status = '';
                case 6:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    $cheque_status = '';
                    break;
            }
            if (strlen($this->system_user_id) > 9) {
                $user_id = $this->system_user_id;
            }
            $sql = "call offlinerespond(:amount,:bank_name,:payment_request_id,:date,:bank_transaction_no,:response_type,:cheque_no
                ,:cash_paidto,:user_id,:payment_request_status,:coupon_id,:discount,:deduct_amount,:deduct_text,:cheque_status,:is_partial,:cod_status)";

            $params = array(
                ':amount' => $amount, ':payment_request_id' => $payment_req_id,
                ':response_type' => $response_type, ':date' => $date, ':bank_transaction_no' => $bankTransactionNo,
                ':bank_name' => $bank_name, ':cheque_no' => $chequeNo, ':cash_paidto' => $cashPaidTo, ':user_id' => $user_id,
                ':payment_request_status' => $payment_request_status, ':coupon_id' => $coupon_id, ':discount' => $discount,
                ':deduct_amount' => $deduct_amount, ':deduct_text' => $deduct_text, ':cheque_status' => $cheque_status, ':is_partial' => $is_partial, ':cod_status' => $cod_status
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E111]Error while respond payment request list Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function respondUpdate($amount, $bank_name, $offlineRespondID, $paymentRequestID, $date, $offline_response_type, $bankTransactionNo, $chequeNo, $cheque_status, $cashPaidTo, $coupon_id, $discount, $user_id, $deduct_amount = 0, $deduct_text = '', $is_partial_payment = 0, $cod_status = null)
    {
        try {
            $chequeNo = ($chequeNo > 0) ? $chequeNo : 0;
            $status = ($cheque_status == 'Bounced') ? 0 : 1;
            switch ($offline_response_type) {
                case 5:
                case 1:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    $cheque_status = '';
                    break;
                case 2:
                    $bankTransactionNo = '';
                    $cashPaidTo = '';
                    break;
                case 3:
                    $chequeNo = 0;
                    $cheque_status = '';
                    break;
            }
            if (strlen($this->system_user_id) > 9) {
                $update_by = $this->system_user_id;
            } else {
                $update_by = $user_id;
            }

            //Comment Prev Query
//            $sql = "UPDATE offline_response SET offline_response_type=:offline_response_type,settlement_date=:settlement_date,bank_transaction_no=:bankTransactionNo,"
//                . "cheque_no=:chequeNo,cheque_status=:cheque_status,transaction_status=:status,cash_paid_to=:cashPaidTo,bank_name=:bank_name,amount=:amount,is_active=1,last_update_by=:update_by,cod_status=:cod_status,is_partial_payment=:is_partial_payment WHERE offline_response_id=:offlineRespondID";
//            $params = array(
//                ':offline_response_type' => $offline_response_type, ':settlement_date' => $date, ':bankTransactionNo' => $bankTransactionNo, ':chequeNo' => $chequeNo, ':cheque_status' => $cheque_status, ':status' => $status,
//                ':cashPaidTo' => $cashPaidTo, ':amount' => $amount, ':bank_name' => $bank_name, ':offline_response_id' => $offlineRespondID, ':update_by' => $update_by, ':cod_status' => $cod_status, ':is_partial_payment' => $is_partial_payment
//            );

            $sql = "update offline_response set amount=:amount, offline_response_type=:offline_response_type, settlement_date=:settlement_date, bank_transaction_no=:bankTransactionNo, cheque_no=:chequeNo, cheque_status=:cheque_status, transaction_status=:status, cash_paid_to=:cashPaidTo, bank_name=:bank_name, amount=:amount, is_active=1, last_update_by=:update_by, cod_status=:cod_status, is_partial_payment=:is_partial_payment where offline_response_id=:offline_response_id";
            $params = array(':offline_response_id' => $offlineRespondID, ':amount' => $amount, ':offline_response_type' => $offline_response_type, ':settlement_date' => $date, ':bankTransactionNo' => $bankTransactionNo, ':chequeNo' => $chequeNo, ':cheque_status' => $cheque_status, ':status' => $status, ':cashPaidTo' => $cashPaidTo, ':bank_name' => $bank_name, ':amount' => $amount, ':update_by' => $update_by, ':cod_status' => $cod_status, ':is_partial_payment' => $is_partial_payment);

            $this->db->exec($sql, $params);

            $this->db->closeStmt();

            //Gte Updated Offline Response Row
            $getOfflineResponseSQL = "select * from offline_response where offline_response_id=:offline_response_id";
            $getOfflineResponseParams = array(':offline_response_id' => $offlineRespondID);
            $this->db->exec($getOfflineResponseSQL, $getOfflineResponseParams);
            $offlineRespondRow  = $this->db->resultset();

            //Get Customer Row
            $customerID = $offlineRespondRow[0]['customer_id'] ?? '';
            $getCustomerSQL = "select customer_code as user_code, concat(first_name,' ',last_name) as patron_name, email, mobile from customer where customer_id=:customer_id";
            $getCustomerParams = array(':customer_id' => $customerID);
            $this->db->exec($getCustomerSQL, $getCustomerParams);
            $customerRow = $this->db->resultset();

            return [
              'message' => 'success',
              'offline_response_id' => $offlineRespondID,
              "user_code" => $customerRow[0]['user_code'],
              "patron_name" => $customerRow[0]['patron_name'],
              "email_id" => $customerRow[0]['email'],
              "mobile" => $customerRow[0]['mobile'],
            ];

        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E144]Error while updating respond Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();

            return [];
        }
    }

    public function respondStagingUpdate($amount, $bank_name, $OfflineRespondId, $date, $response_type, $bankTransactionNo, $chequeNo, $cashPaidTo, $user_id)
    {
        try {
            $this->closeConnection();
            $this->db = new DBWrapper();
            $chequeNo = ($chequeNo > 0) ? $chequeNo : 0;
            switch ($response_type) {
                case 1:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    $cheque_status = '';
                    break;
                case 2:
                    $bankTransactionNo = '';
                    $cashPaidTo = '';
                    break;
                case 3:
                    $bankTransactionNo = '';
                    $chequeNo = 0;
                    $cheque_status = '';
                    break;
                case 5:
                    $chequeNo = 0;
                    $cashPaidTo = '';
                    $cheque_status = '';
                    break;
            }
            if (strlen($this->system_user_id) > 9) {
                $update_by = $this->system_user_id;
            } else {
                $update_by = $user_id;
            }
            $sql = "UPDATE `staging_offline_response` SET offline_response_type=:offline_response_type,settlement_date=:settlement_date,bank_transaction_no=:bankTransactionNo,"
                . "cheque_no=:chequeNo,cash_paid_to=:cashPaidTo,bank_name=:bank_name,amount=:amount,last_update_by=:update_by WHERE id=:offline_response_id;";
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

    public function updatePaymentRequestStatus($payment_request_id, $status)
    {
        try {
            $sql = "update payment_request set payment_request_status=:status where payment_request_id=:request_id";
            $params = array(':status' => $status, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E114]Error while updating payment request status payment request id: ' . $payment_request_id . ' status: ' . $status . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPaymentRequestRow($payment_request_id)
    {
        try {
            $sql = "select * from payment_request where payment_request_id=:payment_request_id";
            $params = array(':payment_request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E114]Error while updating payment request status payment request id: ' . $payment_request_id . ' status: ' . $status . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
