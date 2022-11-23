<?php

class PromotionsModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function isExistTemplate($templatename, $user_id) {
        try {
            $sql = "select id from merchant_sms_template WHERE template_name=:templatename and merchant_id=:user_id and is_active=1";
            $params = array(':templatename' => $templatename, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138]Error while checking promo_sms_template exist Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function isExistCalendar($name, $merchant_id) {
        try {
            $sql = 'select promotion_id from merchant_outgoing_sms WHERE promotion_name=:name and merchant_id=:merchant_id and is_active=1';
            $params = array(':name' => $name, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E139]Error while checking category exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function isValidSMSCOUNT($count, $merchant_id) {
        try {
            $sql = "SELECT id from merchant_addon where is_active=1 and package_id=7 and license_available>=:count and start_date<=NOW() and end_date>=NOW() and merchant_id=:merchant_id limit 1";
            $params = array(':count' => $count, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return FALSE;
            } else {
                return $row['id'];
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E140]Error while checking category exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getSMSReport($promotion_id) {
        try {
            $sql = "select mobile,customer_code,customer_name,delivery_date,gateway_status,sms_text from merchant_outgoing_sms_detail d inner join merchant_outgoing_sms s on d.promotion_id=s.promotion_id where s.promotion_id=:id";
            $params = array(':id' => $promotion_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E140]Error while checking category exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function createPromotion($promotion_name, $template_id, $template_name, $sms, $total_records, $merchant_id, $user_id) {
        try {
            $sql = "INSERT INTO `merchant_outgoing_sms`(`merchant_id`,`template_id`,`template_name`,`sms_text`,`promotion_name`,`total_records`,`created_by`,`created_date`,`last_update_by`)VALUES(:merchant_id,:template_id,:template_name,:sms,:promotion_name,:total_records,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':merchant_id' => $merchant_id, ':template_id' => $template_id, ':template_name' => $template_name, ':sms' => $sms, ':promotion_name' => $promotion_name, ':total_records' => $total_records, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
            return $id;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E141]Error while creating group Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveOutgoingSMSDetail($promotion_id, $customer_ids, $mobile_numbers, $user_id) {
        try {
            $customer_ids = implode('~', $customer_ids);
            $mobile_numbers = implode('~', $mobile_numbers);
            $sql = "call save_outgoing_sms_detail(:promotion_id,:customer_ids,:mobile_numbers,:user_id)";
            $params = array(':promotion_id' => $promotion_id, ':customer_ids' => $customer_ids, ':mobile_numbers' => $mobile_numbers, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E142]Error while creating group Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createSMSTemplate($template_name, $sms, $merchant_id, $user_id) {
        try {
            $sql = "INSERT INTO `merchant_sms_template`(`merchant_id`,`template_name`,`sms`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(:merchant_id,:name,:sms,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP())";
            $params = array(':merchant_id' => $merchant_id, ':name' => $template_name, ':sms' => $sms, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
            return $id;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E143]Error while creating group Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deleteTemplate($id, $user_id) {
        try {
            $sql = "UPDATE `merchant_sms_template` SET `is_active` = 0 , last_update_by=:user_id WHERE id=:id";
            $params = array(':id' => $id, ':user_id' => $user_id);

            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E144]Error while delete sms Template Error: for bulk id [' . $id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deletePromotion($id, $user_id) {
        try {
            $sql = "UPDATE `merchant_outgoing_sms` SET `is_active` = 0 , last_update_by=:user_id WHERE promotion_id=:id";
            $params = array(':id' => $id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E145]Error while delete sms Template Error: for bulk id [' . $id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}

?>
