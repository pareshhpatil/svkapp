<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class ApiModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function validatAPIkey($access_key_id, $secret_access_key) {
        try {
            if ($access_key_id != '' && $secret_access_key != '') {
                $sql = "select user_id,merchant_id from merchant_security_key where access_key_id=:access_key_id and secret_access_key=:secret_access_key";
                $params = array(':access_key_id' => $access_key_id, ':secret_access_key' => $secret_access_key);
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $row;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E105]Error while package list Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

}
