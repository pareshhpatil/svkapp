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

    /**
     * Fetch list of merchant associated with a user id
     * 
     * @return type
     */
    public function getPackage() {
        try {
            require MODEL . 'CommonModel.php';
            $commonmodel = new CommonModel();
            $package = $commonmodel->getPackage();
            return $package;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E181]Error while package list Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPackageDetail($package_id) {
        try {
            $sql = "select package_name,package_description,package_id, package_cost from package where package_id=:package_id";
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

    public function getUserDetail($merchant_id) {
        try {
            $sql = "select u.email_id,u.first_name,u.last_name,u.mobile_no,u.user_id,b.address as address1,b.city,b.zipcode,b.state from merchant m inner join merchant_billing_profile b on m.merchant_id = b.merchant_id and is_default=1 inner join user u on u.user_id=m.user_id where m.merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            dd($merchant_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E183]Error while getting user details Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
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

}
