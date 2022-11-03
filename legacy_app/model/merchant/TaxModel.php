<?php

class TaxModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function createTax($tax_name, $percentage, $tax_amount, $description, $tax_type, $tax_calculated_on, $merchant_id, $user_id) {
        try {
            $sql = "INSERT INTO `merchant_tax`(`merchant_id`,`tax_name`,`description`,`percentage`,`fix_amount`,`tax_type`,`tax_calculated_on`,`created_by`,`created_date`,`last_update_by`)"
                    . "VALUES(:merchant_id,:tax_name,:description,:percentage,:tax_amount,:tax_type,:tax_calculated_on,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':tax_name' => $tax_name, ':percentage' => $percentage, ':tax_amount' => $tax_amount, ':description' => $description, ':tax_type' => $tax_type,':tax_calculated_on' => $tax_calculated_on, ':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateTax($tax_id, $tax_name, $percentage, $tax_amount, $description, $tax_type, $tax_calculated_on, $merchant_id, $user_id) {
        try {
            $sql = "update `merchant_tax` set `tax_name`=:tax_name,`description`=:description,fix_amount=:tax_amount,`percentage`=:percentage,tax_type=:tax_type,tax_calculated_on=:tax_calculated_on,`last_update_by`=:user_id where tax_id=:tax_id";
            $params = array(':tax_name' => $tax_name, ':percentage' => $percentage, ':tax_amount' => $tax_amount, ':description' => $description, ':tax_id' => $tax_id, ':tax_type' => $tax_type,':tax_calculated_on'=> $tax_calculated_on, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating supplier Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deleteTax($tax_id, $user_id, $merchant_id) {
        try {
            $sql = "UPDATE `merchant_tax` SET `is_active` = 0 , last_update_by=:user_id WHERE tax_id=:tax_id and merchant_id=:merchant_id";
            $params = array(':tax_id' => $tax_id, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E297]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getTaxid($tax_name, $percentage, $description, $tax_type, $merchant_id, $user_id) {
        try {
            $sql = "select tax_id from merchant_tax WHERE tax_name=:tax_name and percentage=:percentage and merchant_id=:merchant_id and is_active=1";
            $params = array(':tax_name' => $tax_name, ':percentage' => $percentage, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return $result['tax_id'];
            } else {
                return $this->createTax($tax_name, $percentage,0, $description, $tax_type,0, $merchant_id, $user_id);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function isExistTemplate($tax_name, $merchant_id, $id = null) {
        try {
            $sql = "select tax_id from merchant_tax WHERE tax_name=:tax_name and merchant_id=:merchant_id and is_active=1";
            if ($id != null) {
                $sql .= ' and tax_id<>' . $id;
            }
            $params = array(':tax_name' => $tax_name, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E138]Error while checking template exist Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

}
