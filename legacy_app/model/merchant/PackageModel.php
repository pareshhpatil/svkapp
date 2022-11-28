<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class PackageModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getPackageDetail($package_id) {
        try {
            $sql = "select * from package where package_id=:package_id";
            $params = array(':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E182]Error while getting package details Error:  for package id[' . $package_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getUserDetail($user_id) {
        try {
            $sql = "select u.email_id,u.first_name,u.last_name,concat(u.first_name,' ',u.last_name) as name,u.mobile_no,u.user_id,b.address as address1,b.address,b.city,
	b.zipcode,b.state from merchant m inner join merchant_billing_profile b on m.merchant_id = b.merchant_id and is_default=1 inner join user u on u.user_id=m.user_id where m.user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row= $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E183]Error while getting user details Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function intiateFeeTransaction($package_id, $custom_package_id, $merchant_id, $base_amount, $absolute_cost, $tax, $coupon_id, $discount, $PG_id, $PG_type, $narrative, $user_id) {
        try {
            $sql = "SELECT generate_sequence('Fee_transaction_id') as uid";
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $fee_id = isset($row['uid']) ? $row['uid'] : '';
            $coupon_id = ($coupon_id > 0) ? $coupon_id : 0;
            $discount = ($discount > 0) ? $discount : 0;
            $gst = array('SGST @9%', 'CGST @9%');
            $tax_text = json_encode($gst);
            $status = 0;
            $empty = NULL;
            $sql = "INSERT INTO `package_transaction`(`package_transaction_id`, `user_id`, `merchant_id`, `payment_transaction_status`,`package_id`,`custom_package_id`,`base_amount`, `amount`,`tax_amount`,`tax_text`,`coupon_id`,`discount`,  `narrative`,`pg_id`, `pg_type`, `pg_ref_no`, `pg_ref_1`, `pg_ref_2`, `created_by`, `created_date`, `last_update_by`, `last_update_date`) VALUES 
	(:fee_transaction_id,:user_id,:merchant_id,:payment_transaction_status,:package_id,:custom_package_id,:base_amount,:amount,:tax,:tax_text,:coupon_id,:discount,:narrative,:pg_id,:pg_type,:pg_ref_no,:pg_ref_1,:pg_ref_2,:user_id,CURRENT_TIMESTAMP(),:user_id2,CURRENT_TIMESTAMP())";

            $params = array(':fee_transaction_id' => $fee_id, ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':payment_transaction_status' => $status, ':package_id' => $package_id, ':custom_package_id' => $custom_package_id, ':base_amount' => $base_amount, ':amount' => $absolute_cost, ':tax' => $tax, ':tax_text' => $tax_text, ':coupon_id' => $coupon_id, ':discount' => $discount, ':narrative' => $narrative, ':pg_id' => $PG_id, ':pg_type' => $PG_type, ':pg_ref_no' => $empty, ':pg_ref_1' => $empty, ':pg_ref_2' => $empty, ':user_id' => $user_id, ':user_id2' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return $fee_id;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E187]Error while initiate free transaction Error:  for user id[' . $user_id . '] and for package id [' . $package_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function savePackageTransactionDetails($id, $name, $email, $mobile, $address, $city, $state, $zipcode) {
        try {
            $sql = "INSERT INTO `package_transaction_details`(`package_transaction_id`,`name`,`email`,`mobile`,`address`,`city`,`state`,`zipcode`,`created_date`)
                VALUES(:id,:name,:email,:mobile,:address,:city,:state,:zipcode,now());";
            $params = array(':id' => $id, ':name' => $name, ':email' => $email, ':mobile' => $mobile, ':address' => $address, ':city' => $city, ':state' => $state, ':zipcode' => $zipcode);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E208-98]Error while saving package details Error:  for Json[' . json_encode($params) . ']' . $e->getMessage());
            //$this->setGenericError();
        }
    }

    public function saveCustomPackage($description, $package_cost, $invoice_cost, $booking_cost, $invoice, $event_booking, $free_sms, $user_id) {
        try {
            $sql = "INSERT INTO `custom_package`(`package_description`,`package_cost`,`invoice_cost`,`booking_cost`,`invoice`,`event_booking`,
                `free_sms`,`start_date`,`created_by`,`created_date`,`last_update_by`)
                VALUES(:description,:package_cost,:invoice_cost,:booking_cost,:invoice,:event_booking,:free_sms,curdate(),:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':description' => $description, ':package_cost' => $package_cost, ':invoice_cost' => $invoice_cost,
                ':booking_cost' => $booking_cost, ':invoice' => $invoice, ':event_booking' => $event_booking, ':free_sms' => $free_sms,
                ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E208-98]Error while saving package details Error:  for Json[' . json_encode($params) . ']' . $e->getMessage());
            //$this->setGenericError();
        }
    }

    public function changeOnboardingStatus($user_id, $status) {
        try {
            $sql = "update merchant set merchant_status=:status where user_id=:user_id";
            $params = array(':user_id' => $user_id, ':status' => $status);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E208]Error while getting package details Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateCustomPackage($transaction_id, $package_id, $user_id) {
        try {
            $sql = "update custom_package set transaction_id=:transaction_id,created_by=:user_id,last_update_by=:user_id where package_id=:package_id";
            $params = array(':transaction_id' => $transaction_id, ':user_id' => $user_id, ':package_id' => $package_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E208]Error while getting package details Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPaymentGatewayDetails() {
        try {
            $sql = "select * from payment_gateway where pg_id=0";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E184]Error while getting payment gateway details Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
