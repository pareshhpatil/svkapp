<?php

include_once SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';

class Batch
{

    public $db = NULL;
    public $model = NULL;
    public $mailer = 'paymentrequest';

    function __construct()
    {
        $this->db = new DBWrapper();
        $this->model = new Model();
    }

    function getpreferences($user_id = null)
    {
        try {
            $sql = "SELECT send_sms,send_email FROM preferences where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE101]Error while get preferences Error: ' . $e->getMessage());
        }
    }

    function getAppUrl($merchant_id)
    {
        $sql = "SELECT merchant_domain FROM merchant where merchant_id=:merchant_id";
        $params = array(':merchant_id' => $merchant_id);
        $this->db->exec($sql, $params);
        $row = $this->db->single();

        return $this->getConfigAppUrl($row['merchant_domain']);
    }

    function getConfigAppUrl($domain)
    {
        $sql = "SELECT config_value FROM config where config_type='merchant_domain' and config_key=:config_key";
        $params = array(':config_key' => $domain);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        return $row['config_value'];
    }

    function sendSMS($user_id = null, $message, $mobileNo, $merchant_id, $sms_gateway_type, $gateway = NULL)
    {
        try {
            if ($mobileNo > 0) {
                $SMSHelper = new SMSSender();
                $SMSHelper->merchant_id = $merchant_id;
                $SMSHelper->sms_gateway_type = $sms_gateway_type;
                if (!empty($gateway)) {
                    $SMSHelper->url = $gateway['req_url'];
                    $SMSHelper->val1 = $gateway['sg_val1'];
                    $SMSHelper->val2 = $gateway['sg_val2'];
                    $SMSHelper->val3 = $gateway['sg_val3'];
                    $SMSHelper->val4 = $gateway['sg_val4'];
                    $SMSHelper->val5 = $gateway['sg_val5'];
                }
                if ($user_id == null) {
                    $result = array();
                } else {
                    $result = $this->getpreferences($user_id);
                }
                if (!empty($result)) {
                    if ($result['send_sms'] == 1) {
                        $this->logger->debug(__CLASS__, 'Sending SMS initiateMobile number is : ' . $mobileNo);
                        $responseArr = $SMSHelper->send($message, $mobileNo);
                        if (req_type == 'cron')
                            $this->logger->info(__CLASS__, 'Sending SMS Response : ' . $responseArr);
                    }
                } else {
                    if (req_type == 'cron')
                        $this->logger->debug(__CLASS__, 'Sending SMS initiateMobile number is : ' . $mobileNo);
                    $responseArr = $SMSHelper->send($message, $mobileNo);
                    if (req_type == 'cron')
                        $this->logger->info(__CLASS__, 'Sending SMS Response : ' . $responseArr);
                }
                return $responseArr;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE103]Error while send sms Error: ' . $e->getMessage());
        }
    }

    function getCustomerInvoice($payment_request_id, $merchant_id, $customer_id)
    {
        $sql = "SELECT * FROM payment_request where payment_request_id<>:payment_request_id and merchant_id=:merchant_id and customer_id=:customer_id and payment_request_status <>3 order by bill_date desc";
        $params = array(':payment_request_id' => $payment_request_id, ':merchant_id' => $merchant_id, ':customer_id' => $customer_id);
        $this->db->exec($sql, $params);
        $rows = $this->db->resultset();
        return $rows;
    }

    function getPendingBalance($payment_request_id)
    {
        $sql = "SELECT sum(total_amount) as total FROM invoice_particular where payment_request_id =:payment_request_id and product_id<>0 and is_active=1";
        $params = array(':payment_request_id' => $payment_request_id);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        return $row['total'];
    }

    function saveParticular($name, $time_period, $amount, $payment_request_id, $bulk_id, $table)
    {
        $sql = "INSERT INTO " . $table . "invoice_particular (`payment_request_id`, `item`, `description`, `qty`, `rate`, `gst`, `tax_amount`, `discount`, `total_amount`, `narrative`, `is_active`, `bulk_id`, `created_by`, `created_date`, `last_update_by`)
         VALUES (:payment_request_id, :item, :description, 1,:amount, '0', '0', '0.00', :amount, '', '1', :bulk_id, 'system', now(), 'system');";
        $params = array(':payment_request_id' => $payment_request_id, ':item' => $name, ':description' => $time_period, ':amount' => $amount, ':bulk_id' => $bulk_id);
        $this->db->exec($sql, $params);
    }

    function updateInvoice($base, $total, $due, $payment_request_id, $table)
    {
        $sql = "UPDATE " . $table . "payment_request SET `absolute_cost` = :total, `basic_amount` = :base, `invoice_total` = :total, `swipez_total` = :total, `grand_total` = :total, `previous_due` = :due WHERE `payment_request_id` = :payment_request_id";
        $params = array(':payment_request_id' => $payment_request_id, ':base' => $base, ':total' => $total, ':due' => $due);
        $this->db->exec($sql, $params);
    }


    function saveLateFee($payment_request_id, $customer_id, $merchant_id, $bill_date, $type = 'staging_')
    {
        require_once MODEL . 'CommonModel.php';
        $common = new CommonModel();
        $request = $common->getSingleValue($type . 'payment_request', 'payment_request_id', $payment_request_id);
        $penalty_array = array();
        $paid_request_id = '';
        $paid_cycle_id = '';
        $previous_due = 0;
        $paid_request_bill_date = new DateTime(date('Y-m-d'));
        $paid_request_due_date = new DateTime(date('Y-m-d'));
        $paid_date = new DateTime(date('Y-m-d'));
        $bill_date = new DateTime($bill_date);
        $late_payment = 0;
        $invoices = $this->getCustomerInvoice($payment_request_id, $merchant_id, $customer_id);
        if (!empty($invoices)) {
            foreach ($invoices as $v) {
                $due_date = new DateTime($v['due_date']);
                if (in_array($v['payment_request_status'], array(1, 2, 7)) && $paid_request_id == '') {
                    $paid_request_id = $v['payment_request_id'];
                    $paid_cycle_id = $v['billing_cycle_id'];
                    $paid_request_bill_date = new DateTime($v['bill_date']);
                    $paid_request_due_date = new DateTime($v['due_date']);
                    if ($v['payment_request_status'] == 1) {
                        $transaction = $common->getSingleValue('payment_transaction', 'payment_request_id', $paid_request_id, 0, " and payment_transaction_status=1");
                        $paid_date = new DateTime($transaction['paid_on']);
                        if ($transaction['paid_on'] > $v['due_date']) {
                            $late_payment = 1;
                        }
                    } else if ($v['payment_request_status'] == 2) {
                        $transaction = $common->getSingleValue('offline_response', 'payment_request_id', $paid_request_id, 1);
                        $paid_date = new DateTime($transaction['settlement_date']);
                        if ($transaction['settlement_date'] > $v['due_date']) {
                            $late_payment = 1;
                            if (count($invoices) == 1) {
                                $penalty_array[$v['payment_request_id']]['fixed_penalty'] = FIXED_PENALTY;
                                $diff = date_diff($paid_request_bill_date, $paid_date);
                                $days = $diff->format("%a") - 1;
                                $penalty_array[$v['payment_request_id']]['percent_penalty'] = ($amount * $days * PERCENT_PENALTY) / (100 * 365);
                                $cycle = $common->getRowValue('cycle_name', 'billing_cycle_detail', 'billing_cycle_id', $v['billing_cycle_id']);
                                $penalty_array[$v['payment_request_id']]['cycle'] = $cycle;

                                $amount = $this->getPendingBalance($paid_request_id);
                                $penalty_array[$paid_request_id]['fixed_penalty'] = FIXED_PENALTY;
                                $diff = date_diff($paid_request_due_date, $paid_date);
                                $days = $diff->format("%a") - 1;
                                $penalty_array[$paid_request_id]['percent_penalty'] = ($amount * $days * PERCENT_PENALTY) / (100 * 365);
                                $cycle = $common->getRowValue('cycle_name', 'billing_cycle_detail', 'billing_cycle_id', $paid_cycle_id);
                                $penalty_array[$paid_request_id]['cycle'] = $cycle;
                            }
                        }
                    }
                } else {
                    $amount = $this->getPendingBalance($v['payment_request_id']);
                    if ($paid_request_id != '') {
                        if ($late_payment == 1) {
                            $penalty_array[$v['payment_request_id']]['fixed_penalty'] = FIXED_PENALTY;
                            $diff = date_diff($paid_request_bill_date, $paid_date);
                            $days = $diff->format("%a") - 1;
                            $penalty_array[$v['payment_request_id']]['percent_penalty'] = ($amount * $days * PERCENT_PENALTY) / (100 * 365);
                            $cycle = $common->getRowValue('cycle_name', 'billing_cycle_detail', 'billing_cycle_id', $v['billing_cycle_id']);
                            $penalty_array[$v['payment_request_id']]['cycle'] = $cycle;

                            $amount = $this->getPendingBalance($paid_request_id);
                            $penalty_array[$paid_request_id]['fixed_penalty'] = FIXED_PENALTY;
                            $diff = date_diff($paid_request_due_date, $paid_date);
                            $days = $diff->format("%a") - 1;
                            $penalty_array[$paid_request_id]['percent_penalty'] = ($amount * $days * PERCENT_PENALTY) / (100 * 365);
                            $cycle = $common->getRowValue('cycle_name', 'billing_cycle_detail', 'billing_cycle_id', $paid_cycle_id);
                            $penalty_array[$paid_request_id]['cycle'] = $cycle;
                        }
                        break;
                    } else {
                        $previous_due = $previous_due + $amount;
                        $penalty_array[$v['payment_request_id']]['fixed_penalty'] = FIXED_PENALTY;
                        $diff = date_diff($due_date, $bill_date);
                        $days = $diff->format("%a") - 1;
                        $penalty_array[$v['payment_request_id']]['percent_penalty'] = ($amount * $days * PERCENT_PENALTY) / (100 * 365);
                        $cycle = $common->getRowValue('cycle_name', 'billing_cycle_detail', 'billing_cycle_id', $v['billing_cycle_id']);
                        $penalty_array[$v['payment_request_id']]['cycle'] = $cycle;
                    }
                }
            }

            $base_amount = $request['basic_amount'];
            foreach ($penalty_array as $request_id => $row) {
                $this->saveParticular('Late fee on past delayed payment - ₹' . FIXED_PENALTY . '/invoice', $row['cycle'], $row['fixed_penalty'], $payment_request_id, $request['bulk_id'], $type);
                $this->saveParticular('Interest on past delayed payment	- ' . PERCENT_PENALTY . '% simple interest	', $row['cycle'], $row['percent_penalty'], $payment_request_id, $request['bulk_id'], $type);
                $base_amount = round($base_amount + $row['fixed_penalty'] + $row['percent_penalty'], 2);
            }

            $column_id = $common->getRowValue('column_id', 'invoice_column_metadata', 'template_id', $request['template_id'], 1, " and function_id=4");

            if ($column_id > 0) {
                $common->genericupdate($type . 'invoice_values', 'value', $previous_due, 'payment_request_id', $payment_request_id, $null, ' and column_id=' . $column_id);
            }
            $invoice_amount = $base_amount + $previous_due;
            $this->updateInvoice($base_amount, $invoice_amount, $previous_due, $payment_request_id, $type);
        }
    }



    function sendMail($concatStr_, $toEmail_, $merchant_domain = NULL, $merchantName_ = NULL, $image, $day_diff = NULL, $from_email = '')
    {
        try {
            $converter = new Encryption;
            $encoded = $converter->encode($concatStr_);
            $baseurl = getenv('APP_URL');
            $invoiceviewurl = $baseurl . '/patron/paymentrequest/view/' . $encoded;

            $emailWrapper = new EmailWrapper();
            $mailcontents = $emailWrapper->fetchMailBody("patron." . $this->mailer);
            if (isset($mailcontents[0]) && isset($mailcontents[1])) {

                $message = $mailcontents[0];
                $subject = $mailcontents[1];

                $subject = str_replace('__COMPANY_NAME__', $merchantName_, $subject);
                $message = str_replace('__EMAILID__', $toEmail_, $message);
                $message = str_replace('__LINK__', $invoiceviewurl, $message);
                $message = str_replace('__BASEURL__', $baseurl, $message);
                $message = str_replace('__COMPANY_NAME__', $merchantName_, $message);

                if ($image != '') {
                    $image_url = $host . '://' . $merchant_domain . '.' . $base_url . '/uploads/images/' . $image;
                    $message = str_replace('https://www.swipez.in/images/logo.png', $image_url, $message);
                }
                if ($merchantName_ != NULL) {
                    $emailWrapper->from_name_ = $merchantName_;
                }
                if ($from_email != '') {
                    $emailWrapper->from_email_ = $from_email;
                }
                $emailWrapper->sendMail($toEmail_, "", $subject, $message);
            } else {
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[BE106]Error while sending mail Error: ' . $e->getMessage());
        }
    }

    public function getPaymentLink($request_id, $domain_name)
    {
        $converter = new Encryption;
        $encoded = $converter->encode($request_id);
        $baseurl = $domain_name;
        $link = $baseurl . '/patron/paymentrequest/view/' . $encoded;
        $payment_link = $this->getShortLink($link);
        require_once MODEL . 'CommonModel.php';
        $common = new CommonModel();
        $common->updateShortURL($request_id, $payment_link);
        return $payment_link;
    }

    function getShortLink($link)
    {
        $payment_link[] = $link;
        $shortUrlWrap = new SwipezShortURLWrapper();
        $shortUrls = $shortUrlWrap->SaveUrl($payment_link);
        return $shortUrls[0];
    }
}
