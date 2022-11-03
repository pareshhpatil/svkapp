<?php

/**
 * Login model works for patron and merchant login
 *
 * @author Paresh
 */
class UnsubscribeModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function saveOtp($mobile, $otp) {
        try {

            $sql = "update otp set is_active=0 where mobile=:mobile;";
            $params = array(':mobile' => $mobile);
            $this->db->exec($sql, $params);

            $sql = "INSERT INTO `otp`(`mobile`,`otp`,`created_date`)VALUES(:mobile,:otp,CURRENT_TIMESTAMP());";
            $params = array(':mobile' => $mobile, ':otp' => $otp);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E103]Error while saveOtp ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    public function verifyOtp($mobile, $otp) {
        try {
            $sql = "select count(id) as countotp from otp where mobile=:mobile and otp=:otp;";
            $params = array(':mobile' => $mobile, ':otp' => $otp);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['countotp'] > 0) {
                return '1';
            } else {
                return '0';
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E103]Error while saveOtp ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    public function existUnsubscribe($peyment_request_id, $merchant_id) {
        try {
            $sql = "select id from unsubscribe where payment_request_id=:payment_request_id and merchant_id=:merchant_id and is_active=1 limit 1;";
            $params = array(':payment_request_id' => $peyment_request_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (!empty($row)) {
                return $row['id'];
            } else {
                return '0';
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E103]Error while saveOtp ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    public function updateUnsubscribe($id, $email, $mobile, $payment_request_id, $merchant_id, $type) {
        try {
            $sql = "update `unsubscribe` set `email`=:email,`mobile`=:mobile,`payment_request_id`=:req_id,`merchant_id`=:merchant_id,`reason_type`=:type where id=:id";
            $params = array(':email' => $email, ':mobile' => $mobile, ':req_id' => $payment_request_id, ':merchant_id' => $merchant_id, ':type' => $type, ':id' => $id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E103]Error while saveOtp ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    public function saveUnsubscribe($email, $mobile, $payment_request_id, $merchant_id, $type) {
        try {
            $sql = "INSERT INTO `unsubscribe`(`email`,`mobile`,`payment_request_id`,`merchant_id`,`reason_type`,`created_date`)
                    VALUES(:email,:mobile,:req_id,:merchant_id,:type,CURRENT_TIMESTAMP());";
            $params = array(':email' => $email, ':mobile' => $mobile, ':req_id' => $payment_request_id, ':merchant_id' => $merchant_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E103]Error while saveOtp ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

}
