<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class SubscriptionModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getBankList()
    {
        try {
            $sql = "select config_key, config_value from config where config_type='Bank_name'";
            $params = array();
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E110]Error while fetching bank list list Error: ' . $e->getMessage());
        }
    }

    public function getCycleList($userid)
    {
        try {
            $sql = "select distinct billing_cycle_id as id,cycle_name as name from billing_cycle_detail where user_id=:user_id";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E107]Error while fetching cycle list Error:for user id[' . $userid . '] ' . $e->getMessage());
        }
    }

    public function getStatusList()
    {
        try {
            $sql = "select config_key, config_value from config where config_type=:type";
            $params = array(':type' => 'payment_request_status');
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E108]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    public function getSubscriptionList($merchant_id, $franchise_id = 0)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            if ($franchise_id > 0) {
                $sql = "select  s.created_date,`s`.`subscription_id`,`s`.`payment_request_id`,`s`.`mode`,`s`.`repeat_every`,`s`.`repeat_on`,`s`.`start_date`,`s`.`due_date`,`s`.`due_diff`,
    `s`.`last_sent_date`,`s`.`end_mode`,`s`.`occurrences`,`s`.`end_date`,`s`.`display_text`,`s`.`next_bill_date`,concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,c.company_name,p.absolute_cost,p.invoice_type from subscription s inner join payment_request p on s.payment_request_id=p.payment_request_id inner join customer c on c.customer_id=p.customer_id  where s.merchant_id=:merchant_id and s.is_active=1 and p.franchise_id=" . $franchise_id;
            } else {
                $sql = "select  s.created_date,`s`.`subscription_id`,`s`.`payment_request_id`,`s`.`mode`,`s`.`repeat_every`,`s`.`repeat_on`,`s`.`start_date`,`s`.`due_date`,`s`.`due_diff`,
    `s`.`last_sent_date`,`s`.`end_mode`,`s`.`occurrences`,`s`.`end_date`,`s`.`display_text`,`s`.`next_bill_date`,concat(c.first_name,' ',c.last_name) as customer_name,c.customer_code,c.company_name,p.absolute_cost,p.invoice_type,p.payment_request_status from subscription s inner join payment_request p on s.payment_request_id=p.payment_request_id inner join customer c on c.customer_id=p.customer_id  where s.merchant_id=:merchant_id and s.is_active=1";
            }
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E109]Error while getting payment request list Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Fetch staging invoice list with bulk id
     */
    public function getSubscriptionrequestList($request_id, $merchant_id)
    {
        try {
            $sql = "call get_subscription_viewrequest(:request_id,:merchant_id);";
            $params = array(':request_id' => $request_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E256]Error while getting payment request list Error: for user id[' . $user_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function save_Subscription($payment_request_id, $merchant_id, $user_id, $mode = NULL, $repeat_every = NULL, $repeat_on = NULL, $start_date = NULL, $end_mode = NULL, $end_date = NULL, $occurrence = NULL, $display_text = NULL, $due_date = NULL, $date_diff, $carry_due = 0, $billdate, $billing_start_date, $billing_period, $period_type)
    {
        try {
            $sql = "INSERT INTO `subscription`(`payment_request_id`,`merchant_id`,`mode`,`repeat_every`,`repeat_on`,`start_date`,`next_bill_date`,`due_date`,`due_diff`,`end_mode`,`occurrences`,`end_date`,
`display_text`,`carry_due`,`billing_period_start_date`,`billing_period_duration`,`billing_period_type`,`created_by`,`created_date`,`last_updated_by`,`last_updated_date`)VALUES(:payment_request_id,:merchant_id,:mode,:repeat_every,:repeat_on,:start_date,:next_bill_date,:due_date,:due_diff,:end_mode,:occurrences,:end_date,:display_text,:carry_due,:billing_start_date,:billing_period,:period_type,:created_by,CURRENT_TIMESTAMP(),:last_updated_by,CURRENT_TIMESTAMP());";
            $params = array(':payment_request_id' => $payment_request_id, ':merchant_id' => $merchant_id, ':mode' => $mode, ':repeat_every' => $repeat_every, ':repeat_on' => $repeat_on, ':start_date' => $start_date, ':next_bill_date' => $start_date, ':due_date' => $due_date, ':due_diff' => $date_diff, ':end_mode' => $end_mode, ':occurrences' => $occurrence, ':end_date' => $end_date, ':display_text' => $display_text, ':carry_due' => $carry_due, ':billing_start_date' => $billing_start_date, ':billing_period' => $billing_period, ':period_type' => $period_type, ':created_by' => $user_id, ':last_updated_by' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-s]Error while saving subscription Error:  for user id[' . $user_id . '] and payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
        return;
    }

    public function update_Subscription($subscription_id, $user_id, $mode = NULL, $repeat_every = NULL, $repeat_on = NULL, $start_date = NULL, $end_mode = NULL, $end_date = NULL, $occurrence = NULL, $display_text = NULL, $due_date = NULL, $date_diff, $carry_due = 0, $billdate, $billing_start_date, $billing_period, $period_type)
    {
        try {
            $sql = "update subscription set mode=:mode,repeat_every=:repeat_every,repeat_on=:repeat_on,start_date=:start_date,due_date=:due_date,due_diff=:due_diff,end_mode=:end_mode,occurrences=:occurrences,end_date=:end_date,display_text=:display_text,`billing_period_start_date`=:billing_start_date,`billing_period_duration`=:billing_period,`billing_period_type`=:period_type,carry_due=:carry_due ,last_updated_by=:user_id where subscription_id=:subscription_id";
            $params = array(':mode' => $mode, ':repeat_every' => $repeat_every, ':repeat_on' => $repeat_on, ':start_date' => $start_date, ':due_date' => $due_date, ':due_diff' => $date_diff, ':end_mode' => $end_mode, ':occurrences' => $occurrence, ':end_date' => $end_date, ':display_text' => $display_text, ':carry_due' => $carry_due,  ':billing_start_date' => $billing_start_date, ':billing_period' => $billing_period, ':period_type' => $period_type, ':user_id' => $user_id, ':subscription_id' => $subscription_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-su]Error while update subscription Error:  for user id[' . $user_id . '] and payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
        return;
    }

    public function updatePaymentRequestStatus($payment_request_id, $status)
    {
        try {
            $sql = "update payment_request set payment_request_status=:status where payment_request_id=:request_id";
            $params = array(':status' => $status, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update payment request status Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function deleteSubscription($id, $user_id)
    {
        try {
            $sql = "update subscription set is_active=0 ,last_updated_by=:user_id where payment_request_id=:id";
            $params = array(':user_id' => $user_id, ':id' => $id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update payment request status Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getUserMobile($user_id)
    {
        try {
            $sql = "select mobile_no from user where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result['mobile_no'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E104]Error while fetching user mobile Error:  for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getSubscription($payment_request_id)
    {
        try {
            $sql = "select * from subscription where payment_request_id=:payment_request_id and is_active=1";
            $params = array(':payment_request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E112-s]Error while getting payment request subscription Error: for user id[' . $user_id . '] and for payment request id [' . $payment_req_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getInvoiceBreakup($payment_request_id)
    {
        try {
            $template = array();
            $int = 0;
            $headerinc = 0;
            $sql = "call get_invoice_breckup(:id,'Invoice')";
            $params = array(':id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            foreach ($rows as $row) {
                if ($row['column_type'] == 'H') {
                    $type = 'header';
                    $template[$type][$headerinc]['column_id'] = $row['column_id'];
                    $template[$type][$headerinc]['invoice_id'] = $row['invoice_id'];
                    $template[$type][$headerinc]['column_name'] = $row['column_name'];
                    $template[$type][$headerinc]['is_mandatory'] = $row['is_mandatory'];
                    $template[$type][$headerinc]['column_datatype'] = $row['column_datatype'];
                    $template[$type][$headerinc]['is_delete_allow'] = $row['is_delete_allow'];
                    $template[$type][$headerinc]['column_position'] = $row['column_position'];
                    $template[$type][$headerinc]['position'] = $row['position'];
                    $template[$type][$headerinc]['value'] = $row['value'];
                    $headerinc++;
                }

                if ($row['column_type'] == 'R') {
                    $type = 'request';
                    $template[$type][$int]['column_id'] = $row['column_id'];
                    $template[$type][$int]['column_name'] = $row['column_name'];
                    $template[$type][$int]['column_datatype'] = $row['column_datatype'];
                    $template[$type][$int]['column_position'] = $row['column_position'];
                    $template[$type][$int]['position'] = $row['position'];
                    $int++;
                }

                if ($row['column_type'] == 'PT') {
                    $template['particular_total'] = array('invoice_id' => $row['invoice_id'], 'value' => $row['value']);
                }
                if ($row['column_type'] == 'TT') {
                    $template['tax_total'] = array('invoice_id' => $row['invoice_id'], 'value' => $row['value']);
                }

                if ($row['column_type'] == 'SP') {
                    $template['supplier'] = $row['value'];
                    $template['supplier_id'] = $row['invoice_id'];
                }

                if ($row['column_type'] == 'PF' || $row['column_type'] == 'PS') {
                    $template['particular'][$row['column_group_id']][] = array('column_name' => $row['column_name'], 'value' => $row['value'], 'column_id' => $row['column_id'], 'invoice_id' => $row['invoice_id']);
                }

                if ($row['column_type'] == 'TF' || $row['column_type'] == 'TS') {
                    $template['tax'][$row['column_group_id']][] = array('column_name' => $row['column_name'], 'value' => $row['value'], 'column_id' => $row['column_id'], 'invoice_id' => $row['invoice_id']);
                }
            }

            return $template;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error:  for payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function getPendingSubscriptionlist($payreq_id = 0)
    {
        try {
            $sql = "select * from subscription where (end_mode=1 or DATE_FORMAT(end_date,'%Y-%m-%d')>=CURDATE()) and start_date<=CURDATE() and  DATE_FORMAT(last_sent_date,'%Y-%m-%d') <> CURDATE() and is_active=1";
            if ($payreq_id != 0) {
                $sql .= " and payment_request_id='" . $payreq_id . "'";
            }
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[SE102]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    public function getSubscriptionDetails($requested_id)
    {
        try {
            $sql = "select `s`.`subscription_id`,`s`.`payment_request_id`,`s`.`mode`,`s`.`repeat_every`,`s`.`start_date`,`s`.`due_date`,
            `s`.`end_mode`,`s`.`occurrences`,`s`.`end_date`,concat(c.first_name,' ',c.last_name) as customer_name,c.company_name,c.customer_code,p.absolute_cost,p.invoice_type,p.currency from subscription s inner join payment_request p on s.payment_request_id=p.payment_request_id inner join customer c on c.customer_id=p.customer_id  where s.is_active=1 and s.payment_request_id=:payment_request_id";
            $params = array(':payment_request_id' => $requested_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E109]Error while getting payment request list Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getSummaryPageDetails($requested_id, $merchant_id)
    {
        try {
            $converter = new Encryption;
            $subscription_details = $this->getSubscriptionDetails($requested_id);

            $occurrence = 0;
            if (!empty($subscription_details)) {
                //calculate occurences runtime if end_mode is 3 
                if ($subscription_details['end_mode'] == 3) {
                    if (isset($subscription_details['end_mode']) && !empty($subscription_details['end_mode'])) {
                        $date1 = strtotime($subscription_details['start_date']);
                        $date2 = strtotime($subscription_details['end_date']);
                        $occurenceCnt = 1;
                        if ($subscription_details['mode'] == 3) { //monthly
                            $time_period = '+1 MONTH';
                        } else if ($subscription_details['mode'] == 2) { //weeekly
                            $time_period = '+1 WEEK';
                        } else if ($subscription_details['mode'] == 1) { //daily
                            $time_period = '+1 DAY';
                        } else if ($subscription_details['mode'] == 4) { //yearly
                            $time_period = '+1 YEAR';
                        }
                        while (($date1 = strtotime($time_period, $date1)) <= $date2) {
                            $occurenceCnt++;
                        }
                        $repeat_every = ($subscription_details['repeat_every'] != 0) ? $subscription_details['repeat_every'] : 1;
                        $occurrence = (int)($occurenceCnt / $repeat_every);
                    }
                } else if ($subscription_details['end_mode'] == 2) {
                    $occurrence = $subscription_details['occurrences'];
                }
            }

            $subscriptionList = $this->getSubscriptionrequestList($requested_id, $merchant_id);
            $collectedAmount = 0;
            $dueAmount = 0;
            $sent = 0;
            $int = 0;
            if (!empty($subscriptionList)) {
                $sent = count($subscriptionList);
                foreach ($subscriptionList as $row) {

                    if ($row['payment_request_status'] == 1) {
                        $result = $this->calculateAmount('payment_transaction', $row['payment_request_id']);
                        $collectedAmount = $collectedAmount + $result['amount'];
                    } else if ($row['payment_request_status'] == 2) {
                        $result = $this->calculateAmount('offline_response', $row['payment_request_id']);
                        $collectedAmount = $collectedAmount + $result['amount'];
                    } else if ($row['payment_request_status'] == 6) {
                        $subscriptionList[$int]['converted_id_link'] = $converter->encode($row['converted_request_id']);
                    } else if ($row['payment_request_status'] == 7) {
                        $collectedAmount = $collectedAmount + $row['paid_amount'];
                        $dueAmount = $dueAmount + ($row['absolute_cost'] - $row['paid_amount']);
                    } else {
                        $dueAmount = $dueAmount + $row['absolute_cost'];
                    }
                    $subscriptionList[$int]['paylink'] = $converter->encode($row['payment_request_id']);
                    $int++;
                }
            }

            $subscription_summary['details'] = $subscription_details;
            $subscription_summary['list'] = $subscriptionList;
            $subscription_summary['summary']['collectedAmt'] = $collectedAmount;
            $subscription_summary['summary']['dueAmt'] = $dueAmount;
            $subscription_summary['summary']['sent'] = $sent;
            $subscription_summary['summary']['occurrences'] = $occurrence;
            $subscription_summary['summary']['pending'] = $occurrence - $sent;

            return $subscription_summary;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E109]Error while getting payment request list Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function calculateAmount($table, $payment_request_id)
    {
        $sql = 'select amount from ' . $table . ' where payment_request_id=:payment_request_id';
        $params = array(':payment_request_id' => $payment_request_id);
        $this->db->exec($sql, $params);
        return $this->db->single();
    }
}
