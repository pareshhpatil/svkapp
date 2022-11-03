<?php

class CouponModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function isExistCoupon($name, $merchant_id) {
        try {
            $sql = "select coupon_id from coupon WHERE coupon_code=:name and merchant_id=:merchant_id and is_active=1  and DATE_FORMAT(start_date,'%Y-%m-%d') <= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and DATE_FORMAT(end_date,'%Y-%m-%d') >= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and (available > 0 or `limit`=0)";
            $params = array(':name' => $name, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138-d]Error while checking Coupon exist Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function isUserCoupon($coupon_id, $merchant_id) {
        try {
            $sql = 'select coupon_id from coupon WHERE coupon_id=:coupon_id and merchant_id=:merchant_id and is_active=1';
            $params = array(':coupon_id' => $coupon_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138-d]Error while checking Coupon exist Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function getCouponList($merchant_id) {
        try {
            $sql = "select * from coupon where merchant_id=:merchant_id and is_active=:active";
            $params = array(':merchant_id' => $merchant_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E296]Error while fetching supplier list Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getActiveCoupon($merchant_id) {
        try {
            $sql = " select * from coupon where merchant_id=:merchant_id and is_active=1 and DATE_FORMAT(start_date,'%Y-%m-%d') <= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and DATE_FORMAT(end_date,'%Y-%m-%d') >= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and (available > 0 or `limit`=0);";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E110]Error while fetching bank list list Error: ' . $e->getMessage());
        }
    }

    public function createCoupon($user_id,$merchant_id, $coupon_code, $descreption, $start_date, $end_date, $limit, $is_fixed, $percent, $fixed_amount) {
        try {
            $sql = "INSERT INTO `coupon`(`user_id`,`merchant_id`,`coupon_code`,`descreption`,`type`,`percent`,`fixed_amount`,`start_date`,`end_date`,`limit`,`available`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(:user_id,:merchant_id,:coupon_code,:descreption,:is_fixed,:percent,:fixed_amount,:start_date,:end_date,:limit,:limit,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP())";
            $params = array(':user_id' => $user_id,':merchant_id' => $merchant_id, ':coupon_code' => $coupon_code, ':descreption' => $descreption, ':is_fixed' => $is_fixed, ':percent' => $percent, ':fixed_amount' => $fixed_amount, ':start_date' => $start_date, ':end_date' => $end_date, ':limit' => $limit);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295-d]Error while creating Coupon Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deleteCoupon($coupon_id, $user_id) {
        try {
            $sql = "UPDATE `coupon` SET `is_active` = 0 , last_update_by=`user_id` , last_update_date=CURRENT_TIMESTAMP() WHERE coupon_id=:coupon_id and user_id=:user_id";
            $params = array(':coupon_id' => $coupon_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E297]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}

?>
