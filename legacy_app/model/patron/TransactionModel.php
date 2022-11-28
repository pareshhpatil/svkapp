<?php

/**
 * Model to retrieve transaction specific information for a patron
 *
 * @author Paresh
 */
class TransactionModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getPatronSpecificMerchantList($user_id) {
        try {
            $sql = "call get_merchant_cycledetail(:user_id,:user_type);";
            $params = array(':user_id' => $user_id, ':user_type' => 'Transa');
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E171]Error while fetching merchant list Error:  for user id[' . $user_id.']' . $e->getMessage());
        }
    }

    public function getPaymentTransactionStatus() {
        try {
            $sql = "select config_key, config_value from config where config_type=:type";
            $params = array(':type' => 'payment_transaction_status');
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E172]Error while fetching transaction status Error: ' . $e->getMessage());
        }
    }

    public function getPatronSpecificPaymentTransactionList($f_fromdate, $f_todate, $name, $status,$user_id) {
        try {
            $converter = new Encryption;
            $sql = "call get_patron_viewtransaction(:user_id,:from_date,:to_date,:name,:status);";
            $params = array(':user_id' => $user_id, ':from_date' => $f_fromdate, ':to_date' => $f_todate,
                ':name' => $name, ':status' => $status);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            $int=0;
            
            foreach ($list as $item) {
                $list[$int]['paylink'] = $converter->encode($item['payment_request_id']);
                $list[$int]['transaction_id']=$converter->encode($item['pay_transaction_id']);
                $list[$int]['paymentrequest_id']=$converter->encode($item['payment_request_id']);
                $int++;
            }
            
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E173]Error while getting payment transaction list Error:  for user id[' . $user_id.']' . $e->getMessage());
            $this->setGenericError();
        }
    }
    
   
   

   
}
