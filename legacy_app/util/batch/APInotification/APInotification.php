<?php

include('../config.php');

class ApiNotification extends Batch
{

    public $logger = NULL;

    function __construct()
    {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sending();
    }

    function getPendingInvoice()
    {
        try {
            $sql = "call get_pending_notification_invoice()";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[NO102]Error while get Pending notification Error: ' . $e->getMessage());
        }
    }

    function update_notificationsent_Status($payment_request_id)
    {
        try {
            $sql = "update payment_request set notification_sent= 1 where payment_request_id=:payment_request_id;";
            $params = array(':payment_request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[NO103]Error while update_notificationsent_Status  Error: ' . $e->getMessage());
        }
    }

    function sending()
    {
        try {
            require_once CONTROLLER . 'Notification.php';
            $invoicelist = $this->getPendingInvoice();
            foreach ($invoicelist as $invoice)
                try {
                    if ($invoice['payment_request_id'] != '') {
                        $notification = new Notification('cron');
                        $this->logger->debug(__CLASS__, 'Sending Notification initiate Payment_request_id is : ' . $invoice['payment_request_id'] . ' Email: ' . $invoice['email'] . ' Mobile: ' . $invoice['mobile']);
                        $notification->app_url = $invoice['merchant_domain_name'];
                        $notification->sendInvoiceNotification($invoice['payment_request_id'], 0, 1);
                        $this->update_notificationsent_Status($invoice['payment_request_id']);
                    }
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    $this->logger->error(__CLASS__, '[NO104]Error while sending notifications Error: ' . $e->getMessage());
                }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[NO105]Error while sending ivoices Error: ' . $e->getMessage());
        }
    }
}

$ab = new ApiNotification();
