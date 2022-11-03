<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class ApproveModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getApproval($from_date, $to_date, $merchant_id, $type) {
        try {
            $sql = "call get_approval(:merchant_id,:from_date,:to_date,:type);";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $from_date, ':to_date' => $to_date, ':type' => $type);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2114]Error while getPendingAproval merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getChangeDetails($change_id, $merchant_id) {
        try {
            $sql = "select d.status,change_detail_id,column_type,column_value_id,c.customer_id,changed_value,current_value from customer_data_change_detail d inner join customer_data_change c on c.change_id=d.change_id where c.merchant_id=:merchant_id and c.change_id=:change_id;";
            $params = array(':merchant_id' => $merchant_id, ':change_id' => $change_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2115-1]Error while getChange details merchant id: ' . $merchant_id . ' change id: ' . $change_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getDetails($detail_id, $merchant_id) {
        try {
            $sql = "select d.status,change_detail_id,column_type,column_value_id,c.customer_id,changed_value,current_value from customer_data_change_detail d inner join customer_data_change c on c.change_id=d.change_id where c.merchant_id=:merchant_id and d.change_detail_id=:change_detail_id;";
            $params = array(':merchant_id' => $merchant_id, ':change_detail_id' => $detail_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2115]Error while getChange details merchant id: ' . $merchant_id . ' change id: ' . $change_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function update_change_status($detail_id, $status, $user_id) {
        try {
            $sql = "update customer_data_change_detail set status=:status,last_update_by=:user_id where change_detail_id=:detail_id";
            $params = array(':status' => $status, ':user_id' => $user_id, ':detail_id' => $detail_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2116]Error while update change details user id: ' . $user_id . ' detail id: ' . $detail_id . ' Error: ' . $e->getMessage());
        }
    }

    public function update_customer_data($column_name, $customer_id, $value, $user_id) {
        try {
            $sql = "update customer set " . $column_name . "=:value,last_update_by=:user_id where customer_id=:customer_id";
            $params = array(':value' => $value, ':user_id' => $user_id, ':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2117]Error while update change customer details user id: ' . $user_id . ' customer id: ' . $customer_id . ' Error: ' . $e->getMessage());
        }
    }

    public function update_customer_comm_detail($value_id, $value, $user_id) {
        try {
            $sql = "update customer_comm_detail set value=:value,last_update_by=:user_id where comm_detail_id=:value_id";
            $params = array(':value' => $value, ':user_id' => $user_id, ':value_id' => $value_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2118]Error while update change customer comm details user id: ' . $user_id . ' value id: ' . $value_id . ' Error: ' . $e->getMessage());
        }
    }

    public function updateUserid_customer($email, $customer_id) {
        try {
            $sql = "select user_id from user where email_id=:email and user_status=2 limit 1";
            $params = array(':email' => $email);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (!empty($row)) {
                $sql = "update customer set user_id=:user_id,customer_status=2 where customer_id=:customer_id";
                $params = array(':user_id' => $row['user_id'], ':customer_id' => $customer_id);
                $this->db->exec($sql, $params);
                $this->db->closeStmt();
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E169-29]Error while updating customer user id Error: ' . $e->getMessage());
            // $this->setGenericError();
        }
    }

}
