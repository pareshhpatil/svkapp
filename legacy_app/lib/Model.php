<?php

class Model {

    protected $session = NULL;
    protected $system_user_id = NULL;

    function __construct() {
        $this->db = new DBWrapper();
        if(BATCH_CONFIG==true) {
            
        } else {
            $this->session = new SessionLegacy();
            $this->system_user_id = $this->session->get('system_user_id');
        }

        $this->SMSHelper = new SMSSender();
        $this->SMSMessage = new SMSMessage();
        $this->ContactMessage = new helpdeskMessage();
        $env = getenv('ENV');
        $this->host = ($env == 'LOCAL') ? 'http' : 'https';
    }

    function closeConnection() {
        $this->db = NULL;
    }

    function getpreferences($user_id = null) {
        try {
            $sql = "SELECT send_sms,send_email,send_push FROM preferences where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E00101]Error while getpreferences User id: ' . $user_id . ' Error: ' . $e->getMessage());
        }
    }

    public function sendSMS($user_id = null, $message, $mobileNo, $merchant_id = '', $sms_gateway_type = 1, $gateway = NULL) {
        $result = $this->getpreferences($user_id);
        if (!empty($result)) {
            if ($result['send_sms'] == 0) {
                return;
            }
        }
        $this->SMSHelper->merchant_id = $merchant_id;
        $this->SMSHelper->sms_gateway_type = $sms_gateway_type;
        if (!empty($gateway)) {
            $this->SMSHelper->url = $gateway['req_url'];
            $this->SMSHelper->val1 = $gateway['sg_val1'];
            $this->SMSHelper->val2 = $gateway['sg_val2'];
            $this->SMSHelper->val3 = $gateway['sg_val3'];
            $this->SMSHelper->val4 = $gateway['sg_val4'];
            $this->SMSHelper->val5 = $gateway['sg_val5'];
        }
        $responseArr = $this->SMSHelper->send($message, $mobileNo);
        return $responseArr;
    }

    public function fetchContactMessage($type) {
        return $this->ContactMessage->fetch($type);
    }

    public function fetchMessage($type) {
        return $this->SMSMessage->fetch($type);
    }

    public function setError($title, $message) {
        $this->session->set('errorTitle', $title);
        $this->session->set('errorMessage', $message);
    }

    public function setGenericError() {
        $title = "Error occurred during your last operation";
        $message = "There seems to have been an error while processing your last operation. Please try again in sometime.";
        $this->session->set('errorTitle', $title);
        $this->session->set('errorMessage', $message);
        header('Location:/error');
    }

    function _isvalidLandingUrl($url) {
        $sql = "SELECT merchant_id FROM merchant where display_url=:url";
        $params = array(':url' => $url);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        if (isset($row['merchant_id'])) {
            $this->session->set('landing_merchant_id', $row['merchant_id']);
            return TRUE;
        } else {
        $sql = "SELECT merchant_id FROM display_url_backup where old_url=:url";
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        if(!empty($row))
        {
            $this->session->set('landing_merchant_id', $row['merchant_id']);
            return TRUE;
        }else
        {
            return FALSE;
        }
        
        }
    }

    public function isUnsubscribe($type, $value, $merchant_id) {
        try {
            $sql = "SELECT email,mobile from unsubscribe where is_active=1 and " . $type . "='" . $value . "' and (reason_type in (6,2) or (merchant_id='" . $merchant_id . "' and reason_type in(2,3)));";
            $params = array();
            $this->db->exec($sql, $params);
            $list = $this->db->single();
            if (empty($list)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E00101]Error while unsubscribe list Error: ' . $e->getMessage());
            return FALSE;
        }
    }

    public function isValidatePackage($merchant_id, $check = 0) {
        try {
            $sql = "SELECT id from merchant_addon where is_active=1 and package_id=7 and license_available>0 and start_date<=NOW() and end_date>=NOW() and merchant_id=:merchant_id limit 1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return FALSE;
            } else {
                if ($check == 0) {
                    $sql = "update merchant_addon set license_available=license_available-1 where id=:id";
                    $params = array(':id' => $row['id']);
                    $this->db->exec($sql, $params);
                }
                return TRUE;
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E00101]Error while isValidatePackage Error: ' . $e->getMessage());
            return FALSE;
        }
    }

    public function addSMSCount($merchant_id) {
        try {
            $sql = "update notification_count set total_sms_count=total_sms_count+1,package_sms_count=package_sms_count+1 where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return TRUE;
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E00101]Error while addSMSCount Error: ' . $e->getMessage());
            return FALSE;
        }
    }

}
