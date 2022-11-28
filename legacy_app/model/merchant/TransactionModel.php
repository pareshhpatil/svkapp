<?php

/**
 * Model to retrieve transaction specific information for a patron
 *
 * @author Paresh
 */
class TransactionModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getMerchantSpecificcycleList($user_id)
    {
        try {
            $sql = "select distinct billing_cycle_id as id,cycle_name as name from billing_cycle_detail where user_id=:user_id;";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E139]Error while fetching cycle list Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function getMerchantEventList($user_id)
    {
        try {
            $sql = "select event_request_id id,event_name as name from event_request where user_id=:user_id order by created_date desc;";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E139]Error while fetching cycle list Error:for user id[' . $user_id . '] ' . $e->getMessage());
        }
    }

    public function getPendingSettlementInvoice($merchant_id)
    {
        try {
            $sql = "select customer_code,first_name,last_name,grand_total,DATE_FORMAT(bill_date, '%d-%m-%Y') as bill_date,payment_request_id from payment_request p inner join customer c on c.customer_id=p.customer_id where p.merchant_id=:merchant_id and payment_request_status in (0,5,4) and p.is_active=1 and payment_request_type<>4 and p.created_date> DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 3 MONTH order by p.created_date desc;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E139]Error while fetching cycle list Error:for user id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function getPaymentTransactionStatus()
    {
        try {
            $sql = "select config_key, config_value from config where config_type=:type";
            $params = array(':type' => 'payment_transaction_status');
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E140]Error while fetching transaction status Error: ' . $e->getMessage());
        }
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

            SwipezLogger::error(__CLASS__, '[E141]Error while fetching bank list Error: ' . $e->getMessage());
        }
    }

    public function getofflinerespond_detail($response_id)
    {
        try {
            $sql = "select payment_request_id,offline_response_id,offline_response_type,settlement_date,bank_transaction_no,bank_name,amount,cheque_no,cheque_status,cash_paid_to,cod_status "
                . "from offline_response where offline_response_id=:response_id";
            $params = array(':response_id' => $response_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['offline_response_type'] == 1) {
                $row['variable'] = $row['bank_transaction_no'];
                $row['placeholder'] = 'bank transaction no';
            } else if ($row['offline_response_type'] == 2) {
                $row['variable'] = $row['cheque_no'];
                $row['placeholder'] = 'cheque number';
            } else {
                $row['variable'] = $row['cash_paid_to'];
                $row['placeholder'] = 'cash paid to';
            }
            $row['pay_transaction_id'] = $response_id;
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E142]Error while getting offline respond details Error: for response id[' . $response_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMerchantSpecificPaymentTransactionList($f_fromdate, $f_todate, $name, $status, $user_id, $page = null, $franchise_id = 0, $bulk_id = 0)
    {
        try {
            $type = 'get_merchant_bill_transaction';
            switch ($page) {
                case 'event':
                    $payment_request_type = 2;
                    $type = 'get_merchant_viewtransaction';
                    break;
                case 'bulk':
                    $payment_request_type = 3;
                    break;
                case 'subscription':
                    $payment_request_type = 4;
                    break;
                case 'booking':
                    $payment_request_type = 5;
                    $type = 'get_merchant_viewtransaction';
                    break;
                case 'membership':
                    $payment_request_type = 6;
                    $type = 'get_merchant_viewtransaction';
                    break;
                default:
                    $payment_request_type = 1;
                    break;
            }
            $status = ($bulk_id > 0) ? 2 : $status;
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $converter = new Encryption;
            $sql = "call " . $type . "(:user_id,:from_date,:to_date,:name,:status,:payment_request_type,:franchise_id,:bulk_id); ";
            $params = array(
                ':user_id' => $user_id, ':from_date' => $f_fromdate, ':to_date' => $f_todate,
                ':name' => $name, ':status' => $status, ':payment_request_type' => $payment_request_type, ':franchise_id' => $franchise_id, ':bulk_id' => $bulk_id
            );
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            $int = 0;
            foreach ($list as $item) {
                $list[0]['paylink'] = $converter->encode($item['payment_request_id']);
                $list[$int]['transaction_id'] = $converter->encode($item['pay_transaction_id']);
                $list[$int]['paymentrequest_id'] = $converter->encode($item['payment_request_id']);
                if ($item['estimate_request_id'] != '') {
                    $list[$int]['estimate_request_id'] = $converter->encode($item['estimate_request_id']);
                }
                $int++;
            }
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E143]Error while getting payment request list Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getMerchantXwayPaymentTransactionList($f_fromdate, $f_todate, $status, $merchant_id, $franchise_id = 0, $type = 1)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $converter = new Encryption;
            $sql = "call get_merchant_xwaytransaction(:merchant_id,:from_date,:to_date,:status,:franchise_id,:type,'','',''); ";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $f_fromdate, ':to_date' => $f_todate, ':status' => $status, ':franchise_id' => $franchise_id, ':type' => $type);

            $this->db->exec($sql, $params);

            $list = $this->db->resultset();
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['transaction_id'] = $converter->encode($item['xway_transaction_id']);
                if ($item['payment_request_id'] != '') {
                    $list[$int]['payment_request_id'] = $converter->encode($item['payment_request_id']);
                }
                if ($item['form_transaction_id'] != '') {
                    $list[$int]['form_transaction_id'] = $converter->encode($item['form_transaction_id']);
                }
                $int++;
            }
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E143]Error while getting payment request list Error: for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getPaidInvoiceDetails($user_id, $from_date, $to_date)
    {
        try {
            $sql = "call get_paid_invoice(:user_id,:from_date,:to_date);";
            $params = array(':user_id' => $user_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E112-36]Error while getting Paid Invoice Details Error: for user id[' . $user_id . '] and for payment request id [' . $payment_req_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getTransactionStatus($merchant_id, $user_id, $transaction_id, $type)
    {
        try {
            $sql = "call get_transaction_status(:merchant_id,:user_id,:transaction_id,:type);";
            $params = array(':merchant_id' => $merchant_id, ':user_id' => $user_id, ':transaction_id' => $transaction_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['transaction_id'] != '') {
                return $row;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E112-37]Error while getting transaction Details Error: for merchant id[' . $merchant_id . '] and for transaction id [' . $transaction_id . ']' . $e->getMessage());
            return NULL;
        }
    }

    public function deletetransaction($transaction_id)
    {
        try {
            $sql = "update offline_response set is_active=0 where offline_response_id=:transaction_id";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);

            $sql = "select payment_request_id,payment_request_type from offline_response where offline_response_id=:transaction_id";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            $sql = "select delete_ledger('" . $transaction_id . "',2);";
            $this->db->exec($sql, $params);

            if ($row['payment_request_type'] == 5) {
                $this->slotsopen($transaction_id);
            } else {
                $sql = "update payment_request set payment_request_status=0 where payment_request_id=:payment_request_id";
                $params = array(':payment_request_id' => $row['payment_request_id']);
                $this->db->exec($sql, $params);
            }
            return $row['payment_request_type'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error: for payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getTransactionDetail($transaction_id, $merchant_id)
    {
        try {
            if (substr($transaction_id, 0, 1) == 'T') {
                $sql = "select * from payment_transaction where pay_transaction_id=:transaction_id and merchant_id=:merchant_id";
            } else {
                $sql = "select * from xway_transaction where xway_transaction_id=:transaction_id and merchant_id=:merchant_id";
            }
            $params = array(':transaction_id' => $transaction_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (!empty($row)) {
                if (isset($row['xway_transaction_status'])) {
                    $row['status'] = $row['xway_transaction_status'];
                }
                if (isset($row['payment_transaction_status'])) {
                    $row['status'] = $row['payment_transaction_status'];
                }
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113+1]Error while slotsopen Error: for transaction_id[' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function slotsopen($transaction_id)
    {
        try {
            $sql = "select count(slot_id) as total_count,slot_id from booking_transaction_detail where transaction_id=:transaction_id and is_cancelled = 0";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            foreach ($rows as $row) {
                $sql = "update booking_slots set available_seat=available_seat + :total_seat, slot_available=1 where slot_id=:slot_id";
                $params = array(':total_seat' => $row['total_count'], ':slot_id' => $row['slot_id']);
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113+1]Error while slotsopen Error: for transaction_id[' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function changeAvailedStatus($transaction_id, $status)
    {
        try {
            $sql = "update offline_response set is_availed=:status where offline_response_id=:transaction_id";
            $params = array(':transaction_id' => $transaction_id, ':status' => $status);
            $this->db->exec($sql, $params);

            $sql = "update payment_transaction set is_availed=:status where pay_transaction_id=:transaction_id";
            $params = array(':transaction_id' => $transaction_id, ':status' => $status);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error: for payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function refundSave($transaction_id, $merchant_id, $transaction_amount, $refund_amount, $pg_id, $settlement_type, $swipez_settled, $reason, $user_id)
    {
        try {
            if (strlen($this->system_user_id) > 9) {
                $user_id = $this->system_user_id;
            }
            $sql = "INSERT INTO `refund_request`(`merchant_id`,`transaction_id`,`transaction_amount`,`refund_amount`,`pg_id`,
                `settlement_type`,`swipez_settled`,`reason`,`created_date`,`created_by`,`last_update_by`)
                VALUES(:merchant_id,:transaction_id,:amount,:refund_amount,:pg_id,:settlement_type,:swipez_settled,:reason,CURRENT_TIMESTAMP(),:user_id,:user_id);";
            $params = array(
                ':merchant_id' => $merchant_id, ':transaction_id' => $transaction_id, ':amount' => $transaction_amount, ':refund_amount' => $refund_amount, ':pg_id' => $pg_id, ':settlement_type' => $settlement_type, ':swipez_settled' => $swipez_settled, ':reason' => $reason, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while refund request save Error: for transaction id[' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getCalendarTransactionDetail($calender_id)
    {
        try {
            $sql = "SELECT *
            FROM booking_transaction_detail a
            JOIN booking_slots b on a.slot_id  = b.slot_id
            WHERE a.calendar_id = :calendar_id
            and a.is_cancelled = 0";
            $params = array(':calendar_id' => $calender_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return  $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while fetaching trans detail booking calendar: for transaction id[' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getCalendarSlot($calendar_id)
    {
        try {
            $sql = "SELECT distinct slot_title
            from booking_slots 
            where calendar_id  = :calendar_id";
            $params = array(':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return  $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while fetaching trans detail booking calendar: for transaction id[' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getAllCalendars($merchant_id)
    {
        try {
            $sql = "SELECT distinct calendar_title, calendar_id
            from booking_calendars
            where merchant_id  = :merchant_id
            and is_active = '1'";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return  $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while fetaching trans detail booking calendar: for transaction id[' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMerchantSlotTransactionsbyCalendarID($fromdate, $todate, $calendar_id, $status, $merchant_id,  $date_range_type)
    {
        try {
            $status_cond1  = '';
            $status_cond2  = '';
            if ($status != '') {
                $status_cond1 = 'AND a.payment_transaction_status = :status';
                $status_cond2 = 'AND a.transaction_status = :status';
            }

            if ($date_range_type == 'booking_for') {
                $date_cond = "AND (DATE_FORMAT(c.slot_date,'%Y-%m-%d') >= DATE_FORMAT(:from_date,'%Y-%m-%d') and DATE_FORMAT(c.slot_date,'%Y-%m-%d')<= DATE_FORMAT(:to_date,'%Y-%m-%d'))";
            } else {
                $date_cond = "AND (DATE_FORMAT(a.created_date,'%Y-%m-%d') >= DATE_FORMAT(:from_date,'%Y-%m-%d') and DATE_FORMAT(a.created_date,'%Y-%m-%d')<= DATE_FORMAT(:to_date,'%Y-%m-%d'))";
            }

            $sql = "SELECT * FROM (SELECT  a.pay_transaction_id booking_id, a.payment_mode, config_value status_name, c.slot_date ,concat(bp.package_name,' - ',c.slot_time_from,' - ',c.slot_time_to) slot_time ,
            concat(cus.first_name,' ' ,cus.last_name) name, cus.mobile , 
            a.pay_transaction_id ,  a.payment_request_type, b.calendar_title, b.calendar_date, a.quantity, a.created_date,a.amount absolute_amount
            FROM payment_transaction a
            JOIN booking_transaction_detail b on b.transaction_id = a.pay_transaction_id
            JOIN booking_slots c on b.slot_id  = c.slot_id
            JOIN booking_packages bp on bp.package_id  = c.package_id
            JOIN customer cus on cus.customer_id = a.customer_id
            JOIN config cf on cf.config_type = 'payment_transaction_status' and cf.config_key  = a.payment_transaction_status
            WHERE a.merchant_id = :merchant_id
            AND c.is_active=1
            AND c.calendar_id = :calendar_id
            and b.is_cancelled = 0
            $status_cond1
            $date_cond 
            UNION
            SELECT  a.offline_response_id booking_id, 'Paid offline' payment_mode, config_value status_name,c.slot_date ,concat(bp.package_name,' - ',c.slot_time_from,' - ',c.slot_time_to) slot_time, concat(cus.first_name,' ' ,cus.last_name) name, cus.mobile , 
            a.offline_response_id pay_transaction_id , a.payment_request_type
            , b.calendar_title, b.calendar_date, a.quantity, a.created_date,a.amount absolute_amount
            FROM offline_response a
            JOIN booking_transaction_detail b on b.transaction_id = a.offline_response_id
            JOIN booking_slots c on b.slot_id  = c.slot_id
            JOIN booking_packages bp on bp.package_id  = c.package_id
            JOIN customer cus on cus.customer_id = a.customer_id
            JOIN config cf on cf.config_type = 'payment_transaction_status' and cf.config_key  = 'Paid offline'
            WHERE a.merchant_id = :merchant_id
            AND c.calendar_id = :calendar_id
            and b.is_cancelled = 0
            AND c.is_active=1
            $status_cond2
            $date_cond 
            ) b
            GROUP BY b.pay_transaction_id";
            if ($status != '') {
                $params = array(':merchant_id' => $merchant_id, ':calendar_id' => $calendar_id, ':status' => $status, ':from_date' => $fromdate, ':to_date' => $todate);
            } else {
                $params = array(':merchant_id' => $merchant_id, ':calendar_id' => $calendar_id, ':from_date' => $fromdate, ':to_date' => $todate);
            }
            
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return  $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while fetaching trans detail booking calendar: for merchant  id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
