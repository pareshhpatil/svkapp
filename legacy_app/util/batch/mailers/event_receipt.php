<?php

include('../config.php');

class EventReceipt extends Batch {

    public $logger = NULL;
    public $encrypt = NULL;

    function __construct() {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sendReceiptMailer();
    }

    public function fetchDataFromDB() {
        try {
            $sql = "select pay_transaction_id as pay_transaction_id from payment_transaction where payment_transaction_status=1 and payment_request_id=:event_id";
            $params = array(':event_id' => 'R000149692');
            $this->db->exec($sql, $params);
            $resultSet = $this->db->resultset();
            return $resultSet;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, "transaction data fetch failed " . $ex->getMessage());
        }
    }

    function sendReceiptMailer() {
        try {
            require_once CONTROLLER . 'Notification.php';
            $notification = new Notification('cron');
            $notification->subject='Pune Comedy Fest 3.0 ticket with QR code';
            $rows = $this->fetchDataFromDB();
            $this->logger->info(__CLASS__, "Total records " . count($rows));
            foreach ($rows as $row) {
                $this->logger->debug(__CLASS__, "Sending transaction receipt for " . $row['pay_transaction_id']);
                $notification->sendMailReceipt($row['pay_transaction_id'], 0);
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, "sending receipt failed " . $ex->getMessage());
        }
    }

}

new EventReceipt();
