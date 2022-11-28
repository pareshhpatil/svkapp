<?php

/**
 * This class calls necessary db objects to handle Merchant reports
 *
 * @author Paresh
 */
class ReportModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getMerchantCustomer($merchant_id)
    {
        try {
            $sql = "SELECT customer_id,concat(first_name,' ',last_name) as name, company_name FROM  customer  where merchant_id=:merchant_id and is_active=1;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E601]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportCustomerBalance($user_id, $fromdate, $todate, $customer_name)
    {
        try {
            $sql = "call report_customer_balance(:user_id,:from_date,:to_date,:customer_name);";
            $params = array(':user_id' => $user_id, ':from_date' => $fromdate, ':to_date' => $todate, ':customer_name' => $customer_name);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E602]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportAgingSummary($user_id, $fromdate, $todate, $aging, $interval, $aging_by)
    {
        try {
            $sql = "call report_aging_summary(:user_id,:from_date,:to_date,:aging,:interval,:aging_by);";
            $params = array(':user_id' => $user_id, ':from_date' => $fromdate, ':to_date' => $todate, ':aging' => $aging, ':interval' => $interval, ':aging_by' => $aging_by);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E603]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportAgingDetail($user_id, $fromdate, $todate, $customer_name, $aging_by)
    {
        try {
            $customer_name = ($customer_name > 0) ? $customer_name : 0;
            $sql = "call report_aging_detail(:user_id,:from_date,:to_date,:customer_name,:aging_by);";
            $params = array(':user_id' => $user_id, ':from_date' => $fromdate, ':to_date' => $todate, ':customer_name' => $customer_name, ':aging_by' => $aging_by);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E604]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getReportTaxDetail($merchant_id, $template_id, $fromdate, $todate, $status, $column_name, $customer_column, $billing_profile_id = 0)
    {
        try {
            if (!empty($column_name)) {
                $column_name = implode('~', $column_name);
            }
            if (!empty($customer_column)) {
                $customer_column = implode('~', $customer_column);
            }
            $column_name = ($column_name == NULL) ? '' : $column_name;
            $customer_column = ($customer_column == NULL) ? '' : $customer_column;
            $sql = "call report_tax_details(:merchant_id,:template_id,:from_date,:to_date,:status,:column_name,:customer_column,:billing_profile_id);";
            $params = array(
                ':merchant_id' => $merchant_id, ':template_id' => $template_id, ':from_date' => $fromdate, ':to_date' => $todate,
                ':status' => $status, ':column_name' => $column_name, ':customer_column' => $customer_column, ':billing_profile_id' => $billing_profile_id
            );

            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E605]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getColId($column_name, $type, $req_id)
    {
        try {
            $sql = "select m.column_id from invoice_column_metadata m inner join invoice_column_values v on m.column_id=v.column_id where "
                . "m.column_name=:column_name and m.column_type='" . $type . "' and v.payment_request_id=:payment_request_id";
            $params = array(':column_name' => $column_name, ':payment_request_id' => $req_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return false;
            } else {
                return $row['column_id'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E605+989]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getParticularRows($req_id)
    {
        try {
            $sql = "select * from invoice_particular where payment_request_id=:payment_request_id and is_active=1";
            $params = array(':payment_request_id' => $req_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E605+698]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getTaxRows($req_id)
    {
        try {
            $sql = "select tax_name,tax_percent,applicable,tax_amount from invoice_tax t inner join merchant_tax m on m.tax_id=t.tax_id where t.payment_request_id=:payment_request_id and t.is_active=1";
            $params = array(':payment_request_id' => $req_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E605+698]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getReportInvoiceDetail($merchant_id, $fromdate, $todate, $invoice_type, $cycle_selected, $customer_id, $status, $aging_by, $column_name, $is_settle, $franchise_id, $vendor_id, $group)
    {
        try {
            $customer_id = ($customer_id > 0) ? $customer_id : 0;
            if (!empty($column_name)) {
                $column_name = implode('~', $column_name);
            }
            $column_name = ($column_name == NULL) ? '' : $column_name;
            $sql = "call report_invoice_details(:merchant_id,:from_date,:to_date,:invoice_type,:cycle_selected,:customer_id,:status,:aging_by,:column_name,:is_setteled,:franchise_id,:vendor_id,:group,'ORDER BY `invoice_id` DESC','');";
            $params = array(
                ':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate, ':invoice_type' => $invoice_type,
                ':cycle_selected' => $cycle_selected, ':customer_id' => $customer_id, ':status' => $status,
                ':aging_by' => $aging_by, ':column_name' => $column_name, ':is_setteled' => $is_settle,
                ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id, ':group' => $group
            );
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E605]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportPaymentReceived($user_id, $fromdate, $todate, $customer_name, $column_name, $group)
    {
        try {
            $customer_name = ($customer_name > 0) ? $customer_name : 0;
            $column_name = implode('~', $column_name);
            $sql = "call report_payment_received(:user_id,:from_date,:to_date,:customer_name,:column_name,'',:group,'','');";
            $params = array(':user_id' => $user_id, ':from_date' => $fromdate, ':to_date' => $todate, ':customer_name' => $customer_name, ':column_name' => $column_name, ':group' => $group);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E606]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getReportCollections($merchant_id, $fromdate, $todate, $where)
    {
        try {
            $sql = "call report_collections(:merchant_id,:from_date,:to_date,:where,'','','');";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate, ':where' => $where);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            dd($e);
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E606]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportLedger($merchant_id, $fromdate, $todate, $customer_name, $franchise_id)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $customer_name = ($customer_name > 0) ? $customer_name : 0;
            $sql = "call report_ledger(:merchant_id,:from_date,:to_date,:customer_name,:franchise_id);";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate, ':customer_name' => $customer_name, ':franchise_id' => $franchise_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E606]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportSwipezLedger($merchant_id, $fromdate, $todate, $customer_name, $franchise_id = 0)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $customer_name = ($customer_name > 0) ? $customer_name : 0;
            $sql = "call report_swipez_ledger(:merchant_id,:from_date,:to_date,:customer_name,:franchise_id);";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate, ':customer_name' => $customer_name, ':franchise_id' => $franchise_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E606]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportPaymenttdr($user_id, $fromdate, $todate, $column_name, $franchise_id)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $column_name = implode('~', $column_name);
            $sql = "call report_payment_transaction_tdr(:user_id,:from_date,:to_date,:column_name,:franchise_id,'','','');";
            $params = array(':user_id' => $user_id, ':from_date' => $fromdate, ':to_date' => $todate, ':column_name' => $column_name, ':franchise_id' => $franchise_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E607]Error while fetching payment_transaction_tdrt Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function get_ReportPaymentsettlement($user_id, $fromdate, $todate, $franchise_id, $settlement_id)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $sql = "call report_payment_settlement_detail(:user_id,:from_date,:to_date,:franchise_id,:settlement_id);";
            $params = array(':user_id' => $user_id, ':from_date' => $fromdate, ':to_date' => $todate, ':franchise_id' => $franchise_id, ':settlement_id' => $settlement_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E608]Error while fetching payment_transaction_tdrt Error:  for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    function get_ReportPaymentsettlementSummary($merchant_id, $fromdate, $todate, $franchise_id = 0)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            if ($franchise_id > 0) {
                $sql = "select d.id,settlement_date,bank_reference,settlement_amount,requested_settlement_amount,total_capture,total_tdr,total_service_tax,franchise_name,narrative from settlement_detail d left outer join franchise f on d.franchise_id=f.franchise_id where d.merchant_id=:merchant_id and d.status=1 and "
                    . "DATE_FORMAT(settlement_date,'%Y-%m-%d')>=:from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<=:to_date and d.franchise_id=" . $franchise_id;
            } else {
                $sql = "select d.id,settlement_date,bank_reference,settlement_amount,requested_settlement_amount,total_capture,total_tdr,total_service_tax,franchise_name,narrative from settlement_detail d left outer join franchise f on d.franchise_id=f.franchise_id where d.merchant_id=:merchant_id and d.status=1 and "
                    . "DATE_FORMAT(settlement_date,'%Y-%m-%d')>=:from_date and DATE_FORMAT(settlement_date,'%Y-%m-%d')<=:to_date ;";
            }
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E609]Error while fetching payment_transaction_tdrt Error:  for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    function getReportVendorCommission($merchant_id, $fromdate, $todate, $status)
    {
        try {
            $status = ($status > 0) ? $status : 0;

            $sql = "select c.id,c.amount,p.grand_total,v.vendor_code,v.vendor_name,v.email_id,v.mobile,p.invoice_number,p.bill_date,p.payment_request_status from invoice_vendor_commission c inner join vendor v on v.vendor_id=c.vendor_id inner join payment_request p on p.payment_request_id=c.payment_request_id
             where c.merchant_id=:merchant_id and p.payment_request_status<>3 and c.is_active=1 and payment_request_type<>4 and invoice_type=1 and "
                . "DATE_FORMAT(c.created_date,'%Y-%m-%d')>=:from_date and DATE_FORMAT(c.created_date,'%Y-%m-%d')<=:to_date ";
            if ($status == 1) {
                $sql .= ' and payment_request_status in (1,2)';
            } elseif ($status == 2) {
                $sql .= ' and payment_request_status in (0,5,4)';
            }
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate);

            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E609]Error while fetching payment_transaction_tdrt Error:  for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getCustomerColumnList($template_id)
    {
        try {
            $sql = "select distinct m.column_name,m.customer_column_id from invoice_column_metadata m where m.template_id=:template_id and m.is_active=1 and m.save_table_name='customer_metadata' and m.column_type='C'";
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();

            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6010]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    public function getColumnList($template_id)
    {
        try {
            $sql = "select distinct column_id as column_name,column_name as column_value from invoice_column_metadata  where template_id=:template_id and is_active=1 and save_table_name='metadata' and column_type='H'";
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6010]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    public function getStatusList()
    {
        try {
            $sql = "select config_key, config_value from config where config_type=:type";
            $params = array(':type' => 'payment_request_status');
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6011]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    function get_ReportExpectedPayment($user_id, $fromdate, $todate, $aging, $interval, $aging_by)
    {
        try {
            $sql = "call report_expected_payment(:user_id,:from_date,:to_date,:aging,:interval,:aging_by);";
            $params = array(':user_id' => $user_id, ':from_date' => $fromdate, ':to_date' => $todate, ':aging' => $aging, ':interval' => $interval, ':aging_by' => $aging_by);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6012]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getReportTaxSummary($merchant_id, $fromdate, $todate, $billing_profile_id = 0)
    {
        try {
            if ($billing_profile_id > 0) {
                $where = ' and billing_profile_id=' . $billing_profile_id;
            } else {
                $where = '';
            }
            $sql = "select m.tax_name,tax_percent,sum(t.applicable) as total_applicable,sum(t.tax_amount) as total_amount from invoice_tax t inner join payment_request p on p.payment_request_id=t.payment_request_id and p.is_active=1 and payment_request_type<>4 and payment_request_status<>3 
            inner join merchant_tax m on m.tax_id=t.tax_id where t.is_active=1 and p.merchant_id=:merchant_id and p.bill_date>=:from_date and p.bill_date<=:to_date " . $where . " group by t.tax_id;";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6013]Error while fetching customer list Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    function get_ReportRefundDetails($merchant_id, $fromdate, $todate)
    {
        try {
            $sql = "call report_refund_details(:merchant_id,:from_date,:to_date);";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6013]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    function getReportDisputeDetails($merchant_id, $fromdate, $todate)
    {
        try {
            $sql = "call report_dispute_details(:merchant_id,:from_date,:to_date);";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6013]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
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

    public function saveFormDownloadRequest($merchant_id, $transaction_ids, $zip_size, $user_id)
    {
        try {
            $sql = "INSERT INTO `form_builder_download_request`(`merchant_id`,`form_transaction_id`,`zip_size`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:form_transaction_id,:zip_size,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':merchant_id' => $merchant_id, ':form_transaction_id' => $transaction_ids, ':zip_size' => $zip_size, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6011]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    function getCouponAnalytics($merchant_id, $fromdate, $todate)
    {
        try {
            $sql = "select * from coupon_analytics where merchant_id=:merchant_id and DATE_FORMAT(created_date,'%Y-%m-%d')>=:from_date and DATE_FORMAT(created_date,'%Y-%m-%d')<=:to_date";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E608]Error while fetching payment_transaction_tdrt Error:  for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getTemplateMetadata($merchant_id, $template_id, $fromdate, $todate, $status)
    {
        try {
            $sql = "call report_taxdetails_metadata(:merchant_id,:template_id,:from_date,:to_date,:status);";
            $params = array(
                ':merchant_id' => $merchant_id, ':template_id' => $template_id, ':from_date' => $fromdate, ':to_date' => $todate,
                ':status' => $status
            );
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E135]Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getExpenseReport($merchant_id, $from_date, $to_date, $expense_type, $where)
    {
        try {
            $sql = "select e.*,c.name as category,d.name as department,v.vendor_name,v.state,v.gst_number,v.email_id,v.mobile,v.address"
                . " from expense e inner join expense_category c on e.category_id=c.id inner join expense_department d "
                . " on e.department_id=d.id inner join vendor v on v.vendor_id=e.vendor_id where DATE_FORMAT(e.bill_date,'%Y-%m-%d')>=:from_date and DATE_FORMAT(e.bill_date,'%Y-%m-%d')<=:to_date and e.merchant_id=:merchant_id "
                . " and e.is_active=1 and e.type=:type " . $where;
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $from_date, ':to_date' => $to_date, ':type' => $expense_type);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E135]Error while getting expense report Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    function getProductWiseSalesReport($merchant_id, $fromdate, $todate)
    {
        try {
            $sql = "call report_product_wise_sales(:merchant_id,:from_date,:to_date);";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $fromdate, ':to_date' => $todate);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E6013]Error while fetching customer list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }
}
