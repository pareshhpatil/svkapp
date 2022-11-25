<?php

include('../config.php');

class Reminder extends Batch
{

    public $logger = NULL;
    private $array = null;


    function __construct()
    {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->sending(3);
        $this->sending(1);
        $this->sending(0);
        $this->custom();
        $this->sentSupportMail();
    }

    function getPendingInvoice($days, $type)
    {
        try {
            $sql = "call get_pending_bills(:days,:type)";
            $params = array(':days' => $days, ':type' => $type);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE102]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getMerchantDetail($merchant_id)
    {
        try {
            $sql = "select company_name,merchant_domain from merchant where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE102]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getCustomReminder()
    {
        try {
            $date = date('Y-m-d');
            $sql = "select r.payment_request_id,p.late_payment_fee,p.short_url,p.merchant_id,r.subject,r.sms,p.due_date from invoice_custom_reminder r inner join payment_request p on p.payment_request_id=r.payment_request_id inner join merchant_setting ms on ms.merchant_id=p.merchant_id where r.date=:date and r.is_active=1 and payment_request_status in (0,5,4) and payment_request_type<>5 and ms.is_reminder=1";
            $params = array(':date' => $date);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE102]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function sending($days)
    {
        try {
            require_once CONTROLLER . 'Notification.php';
            $messagelist = new SMSMessage();
            $invoicelist = $this->getPendingInvoice($days, 1);
            $count = count($invoicelist);
            $this->array['fetch']['Day ' . $days] = $count;
            if ($days == 1) {
                $due_text = ' Due tomorrow';
            } else if ($days == 0) {
                $due_text = ' Due today';
            } else {
                $due_text = ' Due in ' . $days . ' days';
            }
            foreach ($invoicelist as $invoice)
                try {
                    $notification = new Notification('cron');
                    $notification->reminder = true;
                    if (strlen($invoice['payment_request_id']) == 10) {
                        $this->logger->debug(__CLASS__, 'Sending reminder initiate Payment_request_id is : ' . $invoice['payment_request_id']);

                        $notification->reminder_subject = "Payment request from " . $invoice['company_name'] . $due_text;
                        if ($invoice['short_url'] != '') {
                            $shortUrl = $invoice['short_url'];
                        } else {
                            $shortUrl = $this->getPaymentLink($invoice['payment_request_id'], $invoice['merchant_domain_name']);
                        }
                        if ($invoice['late_fee'] > 0) {
                            $late_fee = "avoid late fees &";
                        } else {
                            $late_fee = "";
                        }

                        $message = $messagelist->fetch('p8');
                        $message = str_replace('<Merchant name>', $invoice['company_name'], $message);
                        $message = str_replace('<Due text>', $due_text, $message);
                        $message = str_replace('<URL>', $shortUrl, $message);
                        $message = str_replace('<Late fee>', $late_fee, $message);
                        $notification->reminder_sms = $message;
                        $notification->app_url = $invoice['merchant_domain_name'];
                        $notification->sendInvoiceNotification($invoice['payment_request_id'], 0, 1);
                        $sent = 0;
                        if (isset($this->array['sent']['Day ' . $days])) {
                            $sent = $this->array['sent']['Day ' . $days];
                        }
                        $this->array['sent']['Day ' . $days] = $sent + 1;
                    } else {
                        Sentry\captureMessage('Empty payment request ' . json_encode($invoice));
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

    function dateDiff($due_date)
    {
        $now = strtotime(date('Y-m-d'));
        $date = strtotime($due_date);
        $datediff = $date - $now;
        return round($datediff / (60 * 60 * 24));
    }

    function custom()
    {
        try {
            $messagelist = new SMSMessage();
            $invoicelist = $this->getCustomReminder();
            $count = count($invoicelist);
            $this->array['fetch']['Custom'] = $count;
            require_once CONTROLLER . 'Notification.php';
            $notification = new Notification('cron');
            $notification->reminder = true;
            foreach ($invoicelist as $invoice)
                try {
                    $merchant = $this->getMerchantDetail($invoice['merchant_id']);
                    $notification->app_url = $this->getConfigAppUrl($merchant['merchant_domain']);
                    $days = $this->dateDiff($invoice['due_date']);
                    if ($days == 1) {
                        $due_text = ' Due tomorrow';
                    } else if ($days == 0) {
                        $due_text = ' Due today';
                    } else {
                        $due_text = ' Due in ' . $days . ' days';
                    }
                    if ($invoice['subject'] == '') {
                        $notification->reminder_subject = "Payment request from " . $merchant['company_name'] . $due_text;
                    } else {
                        $invoice['subject'] = str_replace('%REMINDER_DAY%', $due_text, $invoice['subject']);
                        $notification->reminder_subject = $invoice['subject'];
                    }

                    $this->logger->debug(__CLASS__, 'Sending reminder initiate Payment_request_id is : ' . $invoice['payment_request_id']);

                    if ($invoice['short_url'] != '') {
                        $shortUrl = $invoice['short_url'];
                    } else {
                        $shortUrl = $this->getPaymentLink($invoice['payment_request_id'], $notification->app_url);
                    }
                    if ($invoice['late_fee'] > 0) {
                        $late_fee = "avoid late fees &";
                    } else {
                        $late_fee = "";
                    }
                    if ($invoice['sms'] == '') {
                        $message = $messagelist->fetch('p8');
                        $message = str_replace('<Merchant name>', $merchant['company_name'], $message);
                        $message = str_replace('<Due text>', $due_text, $message);
                        $message = str_replace('<URL>', $shortUrl, $message);
                        $message = str_replace('<Late fee>', $late_fee, $message);
                    } else {
                        $invoice['sms'] = str_replace('%REMINDER_DAY%', $due_text, $invoice['sms']);
                        $message = $invoice['sms'];
                    }
                    $notification->reminder_sms = $message;
                    $notification->sendInvoiceNotification($invoice['payment_request_id'], 0, 1);
                    $sent = 0;
                    if (isset($this->array['sent']['Custom'])) {
                        $sent = $this->array['sent']['Custom'];
                    }
                    $this->array['sent']['Custom'] = $sent + 1;
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    $this->logger->error(__CLASS__, '[RE104]Error while sending ivoices Error: ' . $e->getMessage());
                }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE105]Error while sending ivoices Error: ' . $e->getMessage());
        }
    }

    function sentSupportMail()
    {
        $emailWrapper = new EmailWrapper();
        $message = '<table border="1"><tr><th>Days</th><th>Fetch reminders</th><th>Sent reminders</th></tr>';
        foreach ($this->array['fetch'] as $key => $value) {
            $sent = $this->array['sent'][$key];
            $message .= "<tr><td>$key</td><td>$value</td><td>$sent</td></tr>";
        }
        $message .= '</table>';

        $emailWrapper->sendMail('paresh.patil@opusnet.in', "", 'Remider for ' . date('Y-m-d'), $message);
    }
}

$ab = new Reminder();
