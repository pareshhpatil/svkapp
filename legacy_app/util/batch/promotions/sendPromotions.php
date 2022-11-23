<?php

include('../config.php');

class Promotions extends Batch {

    public $logger = NULL;

    function __construct() {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sending();
    }

    function gePromotionlist() {
        try {
            $sql = "SELECT * FROM merchant_outgoing_sms where swipez_status=:status and is_active=1";
            $params = array(':status' => 0);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PE102]Error while get gePromotionlist Error: ' . $e->getMessage());
        }
    }

    function updateSMScount($count, $id) {
        try {
            $sql = "update merchant_addon set license_available=license_available-:count where id=:id";
            $params = array(':count' => $count, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PE103]Error while updateSMScount ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    function getSMSDetails($merchant_id) {
        try {
            $sql = "SELECT g.*,s.sms_gateway_type FROM sms_gateway g inner join merchant_setting s on s.sms_gateway=g.sg_id where s.merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PE104]Error while getSMSDetails ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    function getPromotionDetails($promotion_id) {
        try {
            $sql = "SELECT * FROM merchant_outgoing_sms_detail where promotion_id=:promotion_id";
            $params = array(':promotion_id' => $promotion_id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PE105]Error while getSMSDetails ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    function updatePromotionStatus($status, $error, $id) {
        try {
            $sql = "update merchant_outgoing_sms set swipez_status=:status,error_json=:error where promotion_id=:id";
            $params = array(':status' => $status, ':error' => $error, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PE106]Error while updatePromotionStatus ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    function updatePromotionDetailsStatus($status, $id) {
        try {
            $sql = "update merchant_outgoing_sms_detail set swipez_status=:status,delivery_date=CURRENT_TIMESTAMP(),gateway_status='Sent' where promotion_id=:id";
            $params = array(':status' => $status, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PE107]Error while updatePromotionDetailsStatus ' . json_encode($params) . ' Error: ' . $e->getMessage());
        }
    }

    function sending() {
        try {
            $promotionlist = $this->gePromotionlist();
            require_once MODEL . 'merchant/PromotionsModel.php';
            $promo_model = new PromotionsModel();

            foreach ($promotionlist as $promotion) {
                try {
                    $this->logger->info(__CLASS__, 'sending promo sms initiate promotion_id is : ' . $promotion['promotion_id']);
                    $package_id = $promo_model->isValidSMSCOUNT($promotion['total_records'], $promotion['merchant_id']);
                    if ($package_id == FALSE) {
                        $this->logger->warn(__CLASS__, 'SMS package exceeded Merchant_id' . $promotion['merchant_id']);
                        $this->updatePromotionStatus(2, 'SMS package exceeded please purchase more SMS', $promotion['promotion_id']);
                    } else {
                        $details = $this->getSMSDetails($promotion['merchant_id']);
                        if (empty($details)) {
                            $this->logger->warn(__CLASS__, 'SMS gateway details does not exist Merchant_id' . $promotion['merchant_id']);
                            $this->updatePromotionStatus(2, 'SMS gateway details does not exist please contact to support@swipez.in', $promotion['promotion_id']);
                        } else {
                            $mobile_list = $this->getPromotionDetails($promotion['promotion_id']);
                            $this->updatePromotionStatus(8, '', $promotion['promotion_id']);
                            foreach ($mobile_list as $row) {
                                $this->sendSMS(null, $promotion['sms_text'], $row['mobile'], $promotion['merchant_id'], $details['sms_gateway_type'], $details);
                            }
                            $this->logger->info(__CLASS__, 'Sent promo sms records ' . $promotion['total_records']);
                            //$this->updateSMScount($promotion['total_records'], $package_id);
                            $this->updatePromotionStatus(5, '', $promotion['promotion_id']);
                            $this->updatePromotionDetailsStatus(1, $promotion['promotion_id']);
                        }
                    }
                } catch (Exception $e) {
Sentry\captureException($e);
                    
$this->logger->error(__CLASS__, '[PE108]Error while sending promo sms Error: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[PE109]Error while sending promo sms Error: ' . $e->getMessage());
        }
    }

}

$ab = new Promotions();
