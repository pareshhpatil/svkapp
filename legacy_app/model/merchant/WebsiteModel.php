<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class WebsiteModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getStagingJSON($merchant_id) {
        try {
            $sql = "select json from website_staging where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $sql = "INSERT INTO `website_staging`(`merchant_id`,`json`,`created_by`,`created_date`,`last_update_by`)
                    select '" . $merchant_id . "',`json`,'" . $merchant_id . "',now(),'" . $merchant_id . "' from website_staging where merchant_id='SYSTEM';";
                $params = array();
                $this->db->exec($sql, $params);
                return $this->getStagingJSON($merchant_id);
            } else {
                return $row['json'];
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E30001]Error while get staging json merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMerchantWebsite($merchant_id) {
        try {
            $sql = "select * from website_live where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E30009]Error while get getMerchantSetting merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }
    public function getMerchantDisplayUrl($merchant_id) {
        try {
            $sql = "select display_url from merchant where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['display_url'];
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E30009]Error while get getMerchantSetting merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveMerchantWebsite($merchant_id, $domain, $txt_text, $user_id) {
        try {
            $row = $this->getMerchantWebsite($merchant_id);
            if (!empty($row)) {
                $sql = "update website_live set merchant_domain=:domain,txt_text=:txt_text,last_update_by=:user_id where merchant_id=:merchant_id";
            } else {
                $sql = "INSERT INTO `website_live`(`merchant_id`,`merchant_domain`,`txt_text`,`domain_cname`,`json`,`status`,`is_active`,`created_by`,`created_date`,`last_update_by`)VALUES(:merchant_id,:domain,:txt_text,'','',0,1,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            }
            $params = array(':domain' => $domain, ':txt_text' => $txt_text, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E30002]Error while update staging json user id: ' . $user_id . ' merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
        }
    }

    public function updateCname($merchant_id, $cname,$status, $user_id) {
        try {
            $sql = "update website_live set domain_cname=:cname,status=:status,last_update_by=:user_id where merchant_id=:merchant_id";
            $params = array(':cname' => $cname, ':status' => $status, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E30002]Error while update staging json user id: ' . $user_id . ' merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
        }
    }

    public function saveLiveJSON($merchant_id, $json, $user_id) {
        try {
            $sql = "select id from website_live where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $sql = "INSERT INTO `website_live`(`merchant_id`,`json`,`created_by`,`created_date`,`last_update_by`)
                    Values( '" . $merchant_id . "','" . $json . "','" . $user_id . "',now(),'" . $user_id . "')";
                $params = array();
            } else {
                $sql = "update website_live set json=:json,last_update_by=:user_id where merchant_id=:merchant_id";
                $params = array(':json' => $json, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E30002]Error while update staging json user id: ' . $user_id . ' merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
        }
    }

    public function updateStagingJSON($merchant_id, $json, $user_id) {
        try {

            $sql = "update website_staging set json=:json,last_update_by=:user_id where merchant_id=:merchant_id";
            $params = array(':json' => $json, ':user_id' => $user_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E30002]Error while update staging json user id: ' . $user_id . ' merchant id: ' . $merchant_id . ' Error: ' . $e->getMessage());
        }
    }

    

}
