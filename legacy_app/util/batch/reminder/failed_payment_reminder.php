<?php

include('../config.php');

class FailedReminder extends Batch {

    public $logger = NULL;

    function __construct() {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sending(1);
    }

    function getFailedTransactions($day) {
        try {
            $sql = "call get_failedTransaction(:day);";
            $params = array(':day' => $day);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[BE102]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function sending($day) {
        try {
            require_once MODEL . 'CommonModel.php';
            $common = new CommonModel();
            $messagelist = new SMSMessage();
            require_once CONTROLLER . 'Notification.php';
            $this->mailer = 'failed_payment';
            $invoicelist = $this->getFailedTransactions($day);
            $sg_details = NULL;
            foreach ($invoicelist as $invoice)
                try {
                    $sg_details = NULL;
                    $logo = ($invoice['logo'] != '') ? 'logos/' . $invoice['logo'] : 'landing/' . $invoice['merchant_logo'];
                    $this->logger->debug(__CLASS__, 'Sending failed payment reminder Payment_request_id is : ' . $invoice['payment_request_id'] . ' Email: ' . $invoice['email']);
                    if ($invoice['email'] != '') {
                        $notification = new Notification('cron');
                        $notification->app_url = $invoice['merchant_domain_name'];
                        $notification->sendInvoiceNotification($invoice['payment_request_id'], 0, 0);
                    }
                    if (strlen($invoice['mobile']) > 9) {
                        if ($invoice['sms_gateway'] > 1) {
                            $sg_details = $common->getSingleValue('sms_gateway', 'sg_id', $invoice['sms_gateway']);
                        }

                        if ($invoice['short_url'] != '') {
                            $shortUrl = $invoice['short_url'];
                        } else {
                            $shortUrl = $this->getPaymentLink($invoice['payment_request_id'], $invoice['merchant_domain_name']);
                        }

                        $message = $messagelist->fetch('p7');
                        $message = str_replace('<Merchant name>', $invoice['sms_name'], $message);
                        $message = str_replace('<URL>.', $shortUrl, $message);
                        $this->sendSMS(NULL, $message, $invoice['mobile'], $invoice['merchant_id'], $invoice['sms_gateway_type'], $sg_details);
                    }
                } catch (Exception $e) {
Sentry\captureException($e);
                    
$this->logger->error(__CLASS__, '[RE104]Error while sending ivoices Error: ' . $e->getMessage());
                }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[BE105]Error while sending ivoices Error: ' . $e->getMessage());
        }
    }

}

$ab = new FailedReminder();

