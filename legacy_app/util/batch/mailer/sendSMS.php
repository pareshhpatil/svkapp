<?php

include('../config.php');
$ab = new SendBULKSMS();

class SendBULKSMS {

    public $db = NULL;
    public $logger = NULL;

    function __construct() {
        $this->db = new DBWrapper();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sendSMS();
    }

    function sendSMS() {
        try {
            $SMSHelper = new SMSSender('PROD');
            $file = fopen("renew.csv", "r");
            while (!feof($file)) {
                $mem_ = fgetcsv($file);
                if (strlen($mem_[3]) > 9) {
                    if ($mem_[5] == 'Basic Plan Renewal') {
                        $message = "Your Swipez Basic Plan will expire in 4 days. To continue collecting payments online renew your plan now - " . $mem_[6];
                    } else {
                        $message = "Your business website ".$mem_[7]." will expire in 4 days. To continue gaining new customers via your website renew your website - " . $mem_[6];
                    }
                    echo $mobile;
                    echo $message;
                    $this->logger->debug(__CLASS__, 'Sending SMS initiate Mobile : ' . $mem_[3]);
                    $response = $SMSHelper->send($message, rtrim($mem_[3]));
                    $this->logger->info(__CLASS__, $response);
                }
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[BE103]Error while send sms Error: ' . $e->getMessage());
        }
    }

}
