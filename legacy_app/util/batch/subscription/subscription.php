<?php
include_once('../config.php');

class SubscriptionRequest extends Batch
{
    public $logger = NULL;
    public $req_id = NULL;

    function __construct()
    {
        $this->req_id = req_type;
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->send_subscription();
    }

    function send_subscription()
    {
        try {
            $payment_req_id = ($this->req_id == 'cron') ? 0 : $this->req_id;
            require_once MODEL . 'CommonModel.php';
            $common = new CommonModel();
            require_once MODEL . 'merchant/SubscriptionModel.php';
            $subscription_m = new SubscriptionModel();
            if (req_type == 'cron') {
                $this->logger->info(__CLASS__, 'Saving Subscription initiate');
            }
            $payment_request_id = array();
            $due_date = array();
            $subscriptionlist = $subscription_m->getPendingSubscriptionlist($payment_req_id);
            foreach ($subscriptionlist as $subscription) {
                try {
                    //check if subscription is in draft status or not, if not then only create its inner invoices
                    $find_payment_request_status = $common->getRowValue('payment_request_status', 'payment_request', 'payment_request_id', $subscription['payment_request_id']);
                    if ($find_payment_request_status != 11) {
                        $mode = $subscription['mode'];
                        $last_sent = new DateTime($subscription['last_sent_date']);
                        $last_sent = $last_sent->format('Y-m-d');
                        $start_date = new DateTime($subscription['start_date']);
                        $start_date = $start_date->format('Y-m-d');
                        $current_date = date('Y-m-d');
                        $repeat_every = $subscription['repeat_every'];
                        $diff = $subscription['due_diff'];
                        $defaultdate = '2014-01-01';
                        $date = '2014-01-01';
                        $getid = 0;
                        switch ($mode) {
                            case 1:
                                if ($last_sent == '2014-01-01') {
                                    $defaultdate = $start_date;
                                    $date = strtotime("+" . $repeat_every . " days", strtotime($start_date));
                                    $date = date('Y-m-d', $date);
                                } else {
                                    $date = strtotime("+" . $repeat_every . " days", strtotime($last_sent));
                                    $date = date('Y-m-d', $date);
                                }

                                if ($defaultdate == $current_date || $date == $current_date) {
                                    $getid = 1;
                                }

                                break;
                            case 2:
                                if ($start_date == $current_date) {
                                    $getid = 1;
                                } else {
                                    if ($last_sent == '2014-01-01') {
                                        $defaultdate = strtotime("+" . $repeat_every . " weeks", strtotime($start_date));
                                        $defaultdate = date('Y-m-d', $defaultdate);
                                    } else {
                                        $date = strtotime("+" . $repeat_every . " weeks", strtotime($last_sent));
                                        $date = date('Y-m-d', $date);
                                    }

                                    if ($defaultdate == $current_date || $date == $current_date) {
                                        $getid = 1;
                                    }
                                }
                                break;

                            case 3:
                                if ($last_sent == '2014-01-01') {
                                    $defaultdate = $start_date;
                                    $date = strtotime("+" . $repeat_every . " months", strtotime($start_date));
                                } else {
                                    $date = strtotime("+" . $repeat_every . " months", strtotime($last_sent));
                                }
                                $date = date('Y-m-d', $date);
                                if ($defaultdate == $current_date || $date == $current_date) {
                                    $getid = 1;
                                }
                                break;
                            case 4:
                                if ($last_sent == '2014-01-01') {
                                    $defaultdate = $start_date;
                                    $date = strtotime("+" . $repeat_every . " years", strtotime($start_date));
                                    $date = date('Y-m-d', $date);
                                } else {
                                    $date = strtotime("+" . $repeat_every . " years", strtotime($last_sent));
                                    $date = date('Y-m-d', $date);
                                }
                                if ($defaultdate == $current_date || $date == $current_date) {
                                    $getid = 1;
                                }
                                break;
                        }
                        if ($getid == 1) {
                            $res = $common->isValidPackageInvoice($subscription['merchant_id'], 1, 'indi');
                            if ($res == false) {
                                SwipezLogger::warn(__CLASS__, '[PACKEXP] Invoice not available to merchant package for merchant [' . $subscription['merchant_id'] . '] ');
                            } else {
                                $payment_request_id[] = $subscription['payment_request_id'];
                                $duedate = strtotime("+" . $diff . " days", strtotime($current_date));
                                $due_date[] = date('Y-m-d', $duedate);
                            }
                        }
                    }
                } catch (Exception $e) {
                    Sentry\captureException($e);
                    $this->logger->error(__CLASS__, '[SE110]Error while get sending subscription Error: ' . $e->getMessage());
                }
            }
            $this->sending($payment_request_id, $due_date);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[SE102]Error while get sending subscription Error: ' . $e->getMessage());
        }
    }

    function sending($payment_request_id, $due_date)
    {
        try {
            require_once MODEL . 'CommonModel.php';
            $common = new CommonModel();
            require_once CONTROLLER . 'Notification.php';


            $array = array_chunk($payment_request_id, 1);
            $array2 = array_chunk($due_date, 1);
            $einvoice = array();
            foreach ($array as $key => $reqval) {
                $due_value = $array2[$key];
                $payment_request_id = implode('~', $reqval);
                $due_date = implode('~', $due_value);

                $sql = "call saveSubscription_invoice(:payment_request_id,:due_date);";
                $params = array(':payment_request_id' => $payment_request_id, ':due_date' => $due_date);
                $this->db->exec($sql, $params);
                $request_detail = $this->db->resultset();
                foreach ($request_detail as $detail) {
                    if ($detail['request_id'] != '') {
                        $notification = new Notification('cron');
                        $notification->app_url = $detail['merchant_domain_name'];
                        $plugin = json_decode($detail['plugin_value'], 1);
                        if ($plugin['has_e_invoice'] == 1) {
                            $notify = ($plugin['notify_e_invoice'] == 1) ? 1 : 0;
                            $einvoice[] = array('id' => $detail['payment_request_id'], 'notify' => $notify);
                        }
                        $notification->sendInvoiceNotification($detail['request_id'], 0, 1);
                        if ($detail['has_custom_reminder'] == 1) {
                            $subscription_due_date = $common->getRowValue('due_date', 'subscription', 'payment_request_id', $detail['parent_request_id']);
                            $sub_due_date = new DateTime($subscription_due_date);
                            $sub_due_date = $sub_due_date->format('Y-m-d');
                            $reminders = $common->getListValue('invoice_custom_reminder', 'payment_request_id', $detail['parent_request_id'], 1);
                            foreach ($reminders as $rm) {
                                $date1 = new DateTime($sub_due_date);
                                $date2 = new DateTime($rm['date']);
                                $diff = $date2->diff($date1)->format("%a");
                                if ($rm['date'] > $sub_due_date) {
                                    $duedate = strtotime("+" . $diff . " days", strtotime($detail['due_date']));
                                    $rdate = date('Y-m-d', $duedate);
                                } else {
                                    $duedate = strtotime("-" . $diff . " days", strtotime($detail['due_date']));
                                    $rdate = date('Y-m-d', $duedate);
                                }
                                $this->saveReminder($detail['request_id'], $rdate, $rm['subject'], $rm['sms'], $detail['merchant_id'], $detail['user_id']);
                            }
                        }
                    }
                }
            }
            if (!empty($einvoice)) {
                include_once('../Bulkupload/bulkupload.php');
                $bulkUpload = new BulkUpload();
                $array['source'] = 'Bulkupload';
                $array['data'] = $einvoice;
                $bulkUpload->apisrequest(SWIPEZ_APP_URL . 'api/v2/einvoice/queue', json_encode($array), []);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[SE104]Error while sending ivoices Error: ' . $e->getMessage());
        }
    }

    public function saveReminder($payment_request_id, $date, $subject, $sms, $merchant_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `invoice_custom_reminder`(`payment_request_id`,`date`,`subject`,`sms`,`merchant_id`,`created_by`,`last_update_by`,`created_date`)"
                . "VALUES(:request_id,:date,:subject,:sms,:merchant_id,:user_id,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':request_id' => $payment_request_id, ':date' => $date, ':subject' => $subject, ':sms' => $sms, ':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E103]Error while save Reminder ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }
}

new SubscriptionRequest();
