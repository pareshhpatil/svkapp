<?php

class DashboardModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get notification list 
     */
    public function getNotification($userid)
    {
        try {
            $sql = "select distinct  count(notification_type) as count ,link, notification_type as type,concat(message1,' ',message2) as message from  notification where user_id=:user_id and is_dismissed=0 and to_date >= CURRENT_TIMESTAMP() GROUP BY notification_type";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();

            // $sql = "update notification set is_shown=1 where user_id=:user_id and is_dismissed=0 and to_date >= CURRENT_TIMESTAMP()";
            // $params = array(':user_id' => $userid);
            // $this->db->exec($sql, $params);
            // $this->db->closeStmt();
            $notification = array();
            $int = 0;
            foreach ($rows as $row) {
                $notification[$int]['type'] = $row['type'];
                $notification[$int]['link'] = $row['link'];
                $message = str_replace("count", '', $row['message']);
                $notification[$int]['message'] = ucfirst(trim($message));
                $int++;
            }
            return $notification;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E097]Error while fetching notification Error: for user id[' . $userid . '] ' . $e->getMessage());
        }
    }

    /**
     * Update seen notification status
     */
    public function seenNotification($user_id, $type)
    {
        try {
            $sql = "update notification set is_dismissed=:status where user_id=:user_id and notification_type=:type and is_shown=1 and is_dismissed=0;";
            $params = array(':status' => 1, ':user_id' => $user_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    /**
     * Update seen notification status
     */
    public function remindMeLater($merchant_id)
    {
        try {
            $sql = "update merchant_setting set reminder_date=now() where merchant_id=:merchant_id ;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getTotalTransaction($merchant_id)
    {
        try {
            $paymentSql = "select count(merchant_id) as count from payment_transaction where merchant_id=:merchant_id and payment_transaction_status=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($paymentSql, $params);
            $paymentTransactions = $this->db->single();
            $this->db->closeStmt();
            $xwaySql = "select count(merchant_id) as count from xway_transaction where merchant_id=:merchant_id and xway_transaction_status=1";
            $this->db->exec($xwaySql, $params);
            $xwayTransactions = $this->db->single();
            $this->db->closeStmt();
            $offlineSql = "select count(merchant_id) as count from offline_response where merchant_id=:merchant_id and transaction_status=1";
            $this->db->exec($offlineSql, $params);
            $offlineTransactions = $this->db->single();
            $this->db->closeStmt();
            $totalTransactions = $paymentTransactions['count'] + $xwayTransactions['count'] + $offlineTransactions['count'];
            return $totalTransactions;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getCustomerCount($merchant_id)
    {
        try {
            $sql = "select  count(customer_id) as count from  customer where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows['count'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getMonthCustomerCount($merchant_id)
    {
        try {
            $sql = "select  count(customer_id) as count from  customer where merchant_id=:merchant_id and is_active=1 and DATE_FORMAT(created_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m')";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows['count'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getInvoiceCount($merchant_id)
    {
        try {
            $sql = "select  count(payment_request_id) as count from  payment_request where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows['count'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getTotalInvoiceSum($merchant_id, $days, $currency)
    {
        try {
            if ($days == 1) {
                $sql = "select  sum(grand_total) as invsum from  payment_request where merchant_id=:merchant_id and is_active=1 and payment_request_status<>3 and payment_request_type<>4 and DATE_FORMAT(created_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m') and currency=:currency";
            } else {
                if ($days == 'today') {
                    $days = 1;
                }
                $sql = "select  sum(grand_total) as invsum from  payment_request where merchant_id=:merchant_id and is_active=1 and payment_request_status<>3 and payment_request_type<>4 and DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL " . $days . " DAY) and currency=:currency";
            }
            $params = array(':merchant_id' => $merchant_id, ':currency' => $currency);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            if ($rows['invsum'] > 0) {
                return $rows['invsum'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getPaidInvoiceSum($merchant_id, $days, $currency)
    {
        try {
            if ($days == 1) {
                $sql = "select  sum(grand_total) as invsum from  payment_request where merchant_id=:merchant_id and is_active=1 and payment_request_status in (1) and DATE_FORMAT(last_update_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m') and currency=:currency";
            } else {
                if ($days == 'today') {
                    $days = 1;
                }
                $sql = "select  sum(grand_total) as invsum from  payment_request where merchant_id=:merchant_id and is_active=1 and payment_request_status in (1) and DATE_FORMAT(last_update_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL " . $days . " DAY) and currency=:currency";
            }
            $params = array(':merchant_id' => $merchant_id, ':currency' => $currency);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            $amount = $this->getMonthOfflineTransaction($merchant_id, $days, $currency);
            if ($rows['invsum'] > 0) {
                return $rows['invsum'] + $amount;
            } else {
                return $amount;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getMerchantActiveServices($user_id)
    {
        try {
            $sql = "select  s.*,a.status from  merchant_active_apps a inner join swipez_services s on s.service_id=a.service_id where a.user_id=:user_id and a.is_active=1 and s.is_active=1 order by a.id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getMerchantInActiveServices($ids)
    {
        try {
            if ($ids == '') {
                $sql = "select  * from  swipez_services where is_active=1 and display=1 order by seq";
            } else {
                $sql = "select  * from  swipez_services where  is_active=1 and display=1 and service_id not in(" . $ids . ") order by seq";
            }
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:' . $e->getMessage());
        }
    }

    public function getMonthOnlineTransaction($merchant_id, $days, $currency)
    {
        try {
            if ($days == 1) {
                $sql = "select sum(amount) as sumamount, is_settled from  payment_transaction where merchant_user_id=:merchant_id and payment_transaction_status=1 and DATE_FORMAT(created_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m') and currency=:currency GROUP BY is_settled";
            } else {
                if ($days == 'today') {
                    $days = 1;
                }
                $sql = "select sum(amount) as sumamount, is_settled from  payment_transaction where merchant_user_id=:merchant_id and payment_transaction_status=1 and DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL " . $days . " DAY) and currency=:currency GROUP BY is_settled";
            }
            $params = array(':merchant_id' => $merchant_id, ':currency' => $currency);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset(); //single

            $onlineTransactions['unsettled'] = 0;
            $onlineTransactions['settled'] = 0;
            foreach ($rows as $row) {
                if ($row['is_settled'] == 0) {
                    $onlineTransactions['unsettled'] = $row['sumamount'];
                }
                if ($row['is_settled'] == 1) {
                    $onlineTransactions['settled'] = $row['sumamount'];
                }
            }
            return $onlineTransactions;
            //return $rows['sumamount'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0100]Error while getMonthOnlineTransaction Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getMonthXwayTransaction($merchant_id, $days, $currency)
    {
        try {
            if ($days == 1) {
                $sql = "select  sum(amount) as sumamount, is_settled from  xway_transaction where merchant_id=:merchant_id and xway_transaction_status=1 and DATE_FORMAT(created_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m') and currency=:currency GROUP BY is_settled";
            } else {
                if ($days == 'today') {
                    $days = 1;
                }
                $sql = "select  sum(amount) as sumamount, is_settled from  xway_transaction where merchant_id=:merchant_id and xway_transaction_status=1 and DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL " . $days . " DAY) and currency=:currency GROUP BY is_settled";
            }

            $params = array(':merchant_id' => $merchant_id, ':currency' => $currency);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();

            $xwayTransactions['unsettled'] = 0;
            $xwayTransactions['settled'] = 0;
            foreach ($rows as $row) {
                if ($row['is_settled'] == 0) {
                    $xwayTransactions['unsettled'] = $row['sumamount'];
                }
                if ($row['is_settled'] == 1) {
                    $xwayTransactions['settled'] = $row['sumamount'];
                }
            }

            return $xwayTransactions;

            //return $rows['sumamount'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0100]Error while getMonthOnlineTransaction Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getMonthOfflineTransaction($merchant_id, $days, $currency)
    {
        try {
            if ($days == 1) {
                $sql = "select sum(amount) as sumamount from offline_response where offline_response_type<>6 and merchant_id=:merchant_id and is_active=1 and  DATE_FORMAT(settlement_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m') and currency=:currency";
            } else {
                if ($days == 'today') {
                    $days = 1;
                }
                $sql = "select sum(amount) as sumamount from offline_response where offline_response_type<>6 and merchant_id=:merchant_id and is_active=1 and  DATE_FORMAT(settlement_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL " . $days . " DAY) and currency=:currency";
            }
            $params = array(':merchant_id' => $merchant_id, ':currency' => $currency);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            if ($rows['sumamount'] > 0) {
                return $rows['sumamount'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0101]Error while getMonthOfflineTransaction Error:  for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getMonthSettlement($merchant_id, $days, $currency)
    {

        try {
            if ($days == 1) {
                $sql = "select sum(captured)+sum(tdr)+sum(service_tax) as total from payment_transaction_settlement where merchant_id=:merchant_id and DATE_FORMAT(transaction_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m') and currency=:currency";
                //$sql = "SELECT sum(requested_settlement_amount) as sumamount,sum(total_tdr) as totaltdr,sum(total_service_tax) as totaltax FROM settlement_detail where merchant_id=:merchant_id and DATE_FORMAT(settlement_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE(),'%Y-%m')";
            } else {
                if ($days == 'today') {
                    $days = 1;
                }
                $sql = "select sum(captured)+sum(tdr)+sum(service_tax) as total from payment_transaction_settlement where merchant_id=:merchant_id and DATE_FORMAT(transaction_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL " . $days . " DAY) and currency=:currency ";
                //$sql = "SELECT sum(requested_settlement_amount) as sumamount,sum(total_tdr) as totaltdr,sum(total_service_tax) as totaltax FROM settlement_detail where merchant_id=:merchant_id and  DATE_FORMAT(settlement_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL " . $days . " DAY)";
            }
            $params = array(':merchant_id' => $merchant_id,':currency'=>$currency);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['total'] > 0) {
                return $row;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0102]Error while getMonthSettlement Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getSMScount($merchant_id)
    {
        try {
            $sql = "SELECT sum(license_bought) as total,sum(license_available) as available FROM merchant_addon where merchant_id=:merchant_id and is_active=1 and package_id=7 and end_date>CURRENT_DATE()";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E0102]Error while getMonthSettlement Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    /**
     * Update request demo 
     */
    public function updateRequestDemo($merchant_id)
    {
        try {
            $sql = "update merchant_setting set request_demo=1 where merchant_id=:merchant_id ;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating request demo :  for merchant  id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getRequestDemo($merchant_id)
    {
        try {
            $sql = "select request_demo from merchant_setting where merchant_id=:merchant_id";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows['request_demo'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }
}
