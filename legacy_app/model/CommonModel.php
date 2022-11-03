<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class CommonModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getSingleValue($table, $column, $id, $is_active = 0, $extra = '')
    {
        try {
            $sql = "select * from " . $table . " where " . $column . "=:id ";
            if ($is_active == 1) {
                $sql .= " and is_active=1 ";
            }
            $sql .= $extra;
            $sql .= " limit 1";
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-12]Error while single value ' . $sql . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getRowValue($value, $table, $column = '', $id = '', $is_active = 0, $extra = '')
    {
        try {
            $sql = "select " . $value . " from " . $table;
            if ($column != '') {
                $sql .= " where " . $column . "=:id";
                $params = array(':id' => $id);
            } else {
                $params = array();
            }

            if ($is_active == 1) {
                $sql .= " and is_active=1 ";
            }
            $sql .= $extra;

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $value = str_replace('`', '', $value);
            if (!empty($row)) {
                return $row[$value];
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-13]Error while get Row Value ' . $sql . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function queryexecute($sql, $params = null, $id = null)
    {
        try {
            if ($params == null) {
                $params = array();
            }
            $this->db->exec($sql, $params);
            if ($id != null) {
                return $this->db->lastInsertId();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-1298]Error while query single value ' . $sql . json_encode($params) . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function querysingle($sql, $params = null)
    {
        try {
            if ($params == null) {
                $params = array();
            }
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-1298]Error while query single value ' . $sql . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function querylist($sql)
    {
        try {
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-1299]Error while query single value ' . $sql . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function genericupdate($table, $column_update, $update_value, $column, $id, $user_id = null, $extra = '')
    {
        try {
            $sql = "update " . $table . " set " . $column_update . "=:value";
            if ($user_id != null) {
                $sql .= ", last_update_by='" . $user_id . "'";
            }
            $sql .= " where " . $column . "=:id" . $extra;
            $params = array(':value' => $update_value, ':id' => $id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC12-897]Error while genericupdate Error:  for query[' . $sql . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getListValue($table, $column, $id, $is_active = 0, $extra = '')
    {
        try {
            $sql = "select * from " . $table . " where " . $column . "=:value";
            if ($is_active == 1) {
                $sql .= " and is_active=1 ";
            }
            $sql .= $extra;
            $params = array(':value' => $id);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-1295]Error while List value ' . $sql . 'Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getColumn_functions()
    {
        try {
            $sql = "select * from column_function where is_active=1";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            $row['functions'] = $rows;
            foreach ($rows as $f) {
                $json[$f['datatype']][] = array('id' => $f['function_id'], 'value' => $f['function_name']);
                $column_mapping[$f['function_id']] = explode(',', $f['mapping']);
            }
            $row['json'] = json_encode($json);
            $row['mapping'] = json_encode($column_mapping);
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E10778]Error while functions list Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getfunctionMappingDetails($column_id, $function_id)
    {
        try {
            $sql = "select * from column_function_mapping where column_id=:column_id and function_id=:function_id and is_active=1";
            $params = array(':column_id' => $column_id, ':function_id' => $function_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E10778]Error while functions list Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPackageBookCount($package_id, $occurence_id, $event_id)
    {
        try {
            $sql = "select count(event_transaction_detail_id) as count from event_transaction_detail where event_request_id=:event_id and occurence_id=:occ_id and package_id=:package_id and is_paid=1";
            $params = array(':event_id' => $event_id, ':occ_id' => $occurence_id, ':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $count = ($row['count'] > 0) ? $row['count'] : 0;
            return $count;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-78]Error while package list Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPaymentGatewayDetails($merchant_id, $fee_id = NULL, $franchise_id = 0, $currency = 'INR')
    {
        try {
            $currency = (strlen($currency) == 3) ? $currency : 'INR';
            if ($fee_id == NULL) {
                $sql = "select fee_detail_id,pg.pg_id,pg.pg_type,pg.pg_val1,pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,pg.pg_val5,pg.pg_val6,pg.pg_val7,pg.pg_val8,pg.pg_val9,status_url,pg.type from payment_gateway as pg inner join merchant_fee_detail as fd on pg.pg_id=fd.pg_id where fd.merchant_id=:merchant_id and franchise_id=:franchise_id and fd.is_active=1 and fd.currency like '%" . $currency . "%' order by fd.pg_fee_val desc limit 1";
                $params = array(':merchant_id' => $merchant_id, ':franchise_id' => $franchise_id);
            } else {
                $sql = "select fee_detail_id,pg.pg_id,pg.pg_type,pg.pg_val1,pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,pg.pg_val5,pg.pg_val6,pg.pg_val7,pg.pg_val8,pg.pg_val9,status_url,pg.type from payment_gateway as pg inner join merchant_fee_detail as fd on pg.pg_id=fd.pg_id where fd.fee_detail_id=:fee_id";
                $params = array(':fee_id' => $fee_id);
            }
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E157]Error while getting payment gateway details list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPgDetailsbyID($pg_id, $merchant_id, $currency = 'INR')
    {
        $currency = (strlen($currency) == 3) ? $currency : 'INR';

        $sql = "SELECT fee_detail_id,pg.pg_id,pg.pg_type,pg.pg_val1,
        pg.pg_val2,pg.pg_val4,pg.pg_val3,pg.req_url,
        pg.pg_val5,pg.pg_val6,pg.pg_val7,pg.pg_val8,
        pg.pg_val9,status_url,pg.type 
        from payment_gateway as pg 
        inner join merchant_fee_detail as fd on pg.pg_id=fd.pg_id 
        where fd.merchant_id=:merchant_id 
        and fd.pg_id=:pg_id 
        and fd.is_active=1 
        and fd.currency like '%" . $currency . "%' 
        order by fd.pg_fee_val desc 
        limit 1";
        $params = array(':merchant_id' => $merchant_id, ':pg_id' => $pg_id);
        $this->db->exec($sql, $params);
        return $this->db->single();
    }

    public function getPGDetails($user_id)
    {
        try {
            $sql = "select swipez_fee_type,swipez_fee_val,pg_fee_type,pg_fee_val,pg_tax_type,pg_tax_val,surcharge_enabled from merchant_fee_detail inner join merchant on merchant.merchant_id=merchant_fee_detail.merchant_id where merchant_fee_detail.is_active=1 and merchant.user_id=:user_id order by pg_fee_val desc limit 1";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            if ($rows['surcharge_enabled'] == 1) {
                return $rows;
            } else {
                array();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E106]Error while fetching pg fee details. user id ' . $user_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getReceipt($payment_transaction_id, $type)
    {
        try {
            $sql = "call getPayment_receipt(:transaction_id,:type)";
            $params = array(':transaction_id' => $payment_transaction_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['success'] == 'success') {
                return $row;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E150]Error while fetching bank list Error:  for payment transaction id[' . $payment_transaction_id . ']' . $e->getMessage());
        }
    }

    public function getSupplierlist($userid)
    {
        try {
            $sql = "select supplier_id,supplier_company_name,config_value,contact_person_name,mobile1,email_id1 from supplier inner join config on config.config_key=supplier.industry_type where supplier.user_id=:user_id and is_active=:active and config.config_type='industry_type' order by supplier_id desc;";
            $params = array(':user_id' => $userid, ':active' => 1);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E300]Error while fetching supplier list in template Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getInvoiceBreakup($payment_request_id, $type = 'Invoice')
    {
        try {
            if ($type == 'Invoice') {
                $sql = "SELECT icv.invoice_id,icv.column_id,icv.value,md.sort_order,md.default_column_value,md.position,md.is_mandatory,md.column_group_id,md.is_active,md.is_delete_allow,md.save_table_name,md.column_datatype,md.column_position,md.column_name,md.column_type,md.column_group_id,md.customer_column_id,md.template_id,md.function_id from invoice_column_values as icv "
                    . " inner join invoice_column_metadata md on md.column_id=icv.column_id where icv.payment_request_id=:id and icv.is_active=1";
            } elseif ($type == 'Event') {
                $sql = "SELECT m.column_name,m.column_position,icv.invoice_id,icv.column_id,icv.value from invoice_column_values icv,invoice_column_metadata m where icv.payment_request_id=:id and icv.column_id=m.column_id and icv.is_active=1";
            }
            $params = array(':id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            if ($type == 'Event') {
                return $rows;
            }
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMainHeaderBreckup($template_id, $payment_request_id, $type = 'Invoice')
    {
        try {
            if ($type == 'Bulkupload') {
                $sql = "SELECT icv.invoice_id,icm.column_id,icv.value,icm.column_name,icm.column_datatype,icm.default_column_value,icm.column_position from staging_invoice_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id and icv.payment_request_id=:payment_request_id and icv.is_active=1 where icm.template_id=:id  and icm.column_type='M'";
            } else {
                $sql = "SELECT icv.invoice_id,icm.column_id,icv.value,icm.column_name,icm.column_datatype,icm.default_column_value,icm.column_position from invoice_column_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id and icv.payment_request_id=:payment_request_id and icv.is_active=1 where icm.template_id=:id  and icm.column_type='M'";
            }

            $params = array(':id' => $template_id, ':payment_request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getpatron_id($first_name, $last_name, $email, $mobile, $city, $address, $state, $zipcode)
    {
        try {
            $sql = "call get_EventPatronId(:first_name,:last_name,:email,:mobile,:city,:address,:state,:zipcode)";
            $params = array(':first_name' => $first_name, ':last_name' => $last_name, ':email' => $email, ':mobile' => $mobile, ':city' => $city, ':address' => $address, ':state' => $state, ':zipcode' => $zipcode);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E157-a]Error while getting patron id list Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPaymentRequestDetails($payment_req_id, $user_id = NULL, $payment_request_type = NULL)
    {
        try {
            if (strlen($payment_req_id) == 10) {
                if ($payment_request_type == 2) {
                    $sql = "call geteventinvoice_details(:payment_req);";
                    $params = array(':payment_req' => $payment_req_id);
                } elseif ($payment_request_type == 3) {
                    $sql = "call get_staging_invoice_details(:user_id,:payment_req);";
                    $params = array(':user_id' => $user_id, ':payment_req' => $payment_req_id);
                } else {
                    $sql = "call getinvoice_details(:user_id,:payment_req);";
                    $params = array(':user_id' => $user_id, ':payment_req' => $payment_req_id);
                }
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $row;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E112]Error while getting payment request details Error: for user id[' . $user_id . ']' . ' Payment request id:' . $payment_req_id . $e->getMessage());
        }
    }

    public function getInvoiceSupplierlist($supplier_ids)
    {
        try {
            $sql = "select supplier_id,supplier_company_name,config_value,contact_person_name,mobile1,mobile2,email_id1,email_id2 from supplier inner join config on config.config_key=supplier.industry_type where supplier.supplier_id in ('" . implode("','", $supplier_ids) . "')  and config.config_type='industry_type' order by supplier_id desc;";
            $params = array();
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E300]Error while fetching supplier list in template Error: for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function updateUserStatus($status, $user_id)
    {
        try {
            $sql = 'UPDATE `user` SET prev_status=`user_status`,`user_status`=:status,last_updated_by=:user_id,'
                . 'last_updated_date=CURRENT_TIMESTAMP() WHERE user_id=:user_id';
            $params = array(':status' => $status, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E127]Error while updating merchant user status Error: ' . $e->getMessage());
        }
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

            SwipezLogger::error(__CLASS__, '[SE101]Error while get preferences Error: ' . $e->getMessage());
        }
    }

    function sendMail($payment_request_id, $toEmail_, $merchant_domain = NULL, $merchantName_ = NULL, $image, $merchant_id = NULL, $is_update = '', $add_subject = '', $custom_covering = '', $custom_subject = '', $sms_enable = 1)
    {
        try {
            $converter = new Encryption;
            $server_path = $merchant_domain;
            $encoded = $converter->encode($payment_request_id);
            $invoiceviewurl = $server_path . '/patron/paymentrequest/view/' . $encoded;
            $info = $this->getPaymentRequestDetails($payment_request_id, $merchant_id);
            require_once CONTROLLER . 'Notification.php';
            $notification = new Notification();
            if ($sms_enable == 1 && $info['customer_mobile'] != '') {
                $notification->sendInvoiceSMS($info, $invoiceviewurl);
            }
            if ($toEmail_ == '') {
                return $invoiceviewurl;
            }
            $smarty = new Smarty();
            $smarty->setCompileDir(SMARTY_FOLDER);
            if ($info['covering_id'] > 0) {
                if ($custom_covering != '') {
                    $covering_detail = json_decode($custom_covering, true);
                } else {
                    $covering_detail = $this->getSingleValue('covering_note', 'covering_id', $info['covering_id']);
                }
                $covering_detail['email_id'] = $info['customer_email'];
                $link = $notification->sendCoveringNote($covering_detail, $info, $server_path, $add_subject, $custom_subject);
                return $link;
            }
            require_once CONTROLLER . 'InvoiceWrapper.php';
            $invoice = new InvoiceWrapper($this);

            $emailWrapper = new EmailWrapper();
            $subject = "Payment request from __COMPANY_NAME__";

            $smarty_data = $invoice->asignSmarty($info, array(), $payment_request_id);
            foreach ($smarty_data as $key => $value) {
                $smarty->assign($key, $value);
            }

            $unsuburl = $server_path . '/unsubscribe/select/' . $encoded;
            $pdf_url = $server_path . '/patron/paymentrequest/download/' . $encoded;
            $smarty->assign('info', $info);
            $smarty->assign('url', $invoiceviewurl);
            $smarty->assign('pdf_url', $pdf_url);
            $smarty->assign('unsub_link', $unsuburl);
            $merchantName_ = $info['company_name'];
            if ($info['merchant_id'] == 'M000000200') {
                $info['template_type'] = 'global';
                $merchantName_ = 'The New India Assurance Co. Ltd.';
            }

            if ($info['main_company_name'] != '') {
                $merchantName_ = $info['main_company_name'] . ' (' . $info['company_name'] . ')';
            }

            $subject = str_replace('__COMPANY_NAME__', $merchantName_, $subject);
            if ($info['template_type'] == 'travel_car_booking') {
                $info['template_type'] = 'society';
            }
            $message = $smarty->fetch(VIEW . 'mailer/' . $info['template_type'] . '_invoice.tpl');

            if ($image != '') {
                $image_url = $server_path . '/uploads/images/logos/' . $image;
                $message = str_replace('https://www.swipez.in/images/nologo.gif', $image_url, $message);
            }
            if ($is_update != NULL) {
                $subject = $is_update . ' ' . lcfirst($subject);
            }


            if ($merchantName_ != NULL) {
                $emailWrapper->from_name_ = $merchantName_;
            }
            if ($info['from_email'] != '') {
                $emailWrapper->from_email_ = $info['from_email'];
            }

            if ($info['business_email'] != '') {
                $emailWrapper->merchant_email_ = $info['business_email'];
            }

            $emailWrapper->merchant_id = $info['merchant_id'];

            if (isset($smarty_data['cc'])) {
                $emailWrapper->cc = $smarty_data['cc'];
            }

            if (substr(req_type, 0, 1) == 'R') {
                $emailWrapper->is_log = 'off';
            }

            if ($custom_subject != '') {
                $info['custom_subject'] = $custom_subject;
            }

            $subject = $subject . $add_subject;
            if ($info['custom_subject'] != '') {
                $subject = $notification->getDynamicString($info, $info['custom_subject']);
            }
            if ($toEmail_ != '') {
                $emailWrapper->sendMail($toEmail_, "", $subject, $message);
            }
            return $invoiceviewurl;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[SE106]Error while sending mail Error: ' . $e->getMessage());
        }
    }

    public function getAttendeeDetails($transaction_id)
    {
        try {
            $this->closeConnection();
            $this->db = new DBWrapper();
            $sql = "select e.event_transaction_detail_id as detail_id,e.package_id,e.is_availed,name,e.customer_id,package_name,age_capture,capture_attendee_details as capture_details,"
                . "mobile_capture,p.package_type,o.start_date,r.event_name,e.coupon_code,venue,package_description"
                . " from event_transaction_detail e inner join event_package p on e.package_id=p.package_id "
                . "inner join event_occurence o on e.occurence_id=o.occurence_id inner join event_request r on "
                . "r.event_request_id=e.event_request_id where transaction_id=:transaction_id";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();

            $sql = "select package_id,count(package_id) as count from event_transaction_detail where transaction_id=:transaction_id group by package_id";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $counts = $this->db->resultset();

            foreach ($counts as $c) {
                $cnt[$c['package_id']] = $c['count'];
            }
            $int = 0;
            foreach ($rows as $row) {
                if ($row['customer_id'] > 0) {
                    $res = $this->getCustomerValueDetail($row['customer_id']);
                    $rows[$int]['cust_det'] = $res['cust_det'];
                    $rows[$int]['cust_value_det'] = $res['cust_value_det'];
                }
                $rows[$int]['name'] = $res['cust_det']['first_name'] . ' ' . $res['cust_det']['last_name'];
                $rows[$int]['total_qty'] = $cnt[$row['package_id']];
                $int++;
            }
            return $rows;
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161]Error while getAttendeeDetails Error: for transaction id[' . $transaction_id . ']' . $e->getMessage());
        }
    }

    public function getCustomerValueDetail($customer_id)
    {
        $array['cust_det'] = $this->getSingleValue('customer', 'customer_id', $customer_id);
        $array['cust_det']['name'] = $array['cust_det']['first_name'] . ' ' . $array['cust_det']['last_name'];
        $comrows = $this->getListValue('customer_column_values', 'customer_id', $customer_id, 1);
        $valuearr = array();
        foreach ($comrows as $cr) {
            $valuearr[$cr['column_id']] = $cr['value'];
        }
        $array['cust_value_det'] = $valuearr;
        return $array;
    }

    public function getBookingDetails($transaction_id)
    {
        try {
            $sql = "select booking_transaction_detail_id,b.calendar_id,b.calendar_date,is_availed,count(b.slot_id) as qty,
            c.booking_unit,b.category_name,b.calendar_title,b.slot,b.amount,c.calendar_email, c.confirmation_message ,c.tandc ,
            c.cancellation_policy,
            bs.slot_title,
            bp.package_name
            from booking_transaction_detail b 
            inner join booking_calendars c on b.calendar_id=c.calendar_id
            join booking_slots bs on bs.slot_id = b.slot_id
            join booking_packages bp on bs.package_id = bp.package_id
            where b.transaction_id=:transaction_id 
            and b.is_paid=1 
            and b.is_cancelled = 0
            group by b.slot_id";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161+98]Error while getBookingDetails Error: for transaction id[' . $transaction_id . ']' . $e->getMessage());
        }
    }

    public function getEventCaptureDetails($transaction_id)
    {
        try {
            $sql = "select v.value,m.column_name from event_capture_values v inner join event_capture_metadata m on m.column_id=v.column_id where v.transaction_id=:transaction_id";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161+98]Error while getBookingDetails Error: for transaction id[' . $transaction_id . ']' . $e->getMessage());
        }
    }

    public function getofflineTransaction_id($payment_request_id)
    {
        try {
            $sql = "select o.offline_response_id,c.customer_code as user_code from offline_response o inner join payment_request p on p.payment_request_id=o.payment_request_id inner join customer c on c.customer_id=p.customer_id where o.payment_request_id=:payment_request_id and o.is_active=1;";
            $params = array(':payment_request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E110]Error while fetching bank list list Error: ' . $e->getMessage());
        }
    }

    public function getMerchantCouponDetails($merchant_id, $coupon_code)
    {
        try {
            $sql = "select coupon_id,coupon_code,descreption,type,percent,fixed_amount,start_date,end_date,`limit`,available from coupon where coupon_code=:coupon_code and is_active=1 and merchant_id=:merchant_id and DATE_FORMAT(start_date,'%Y-%m-%d') <= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and DATE_FORMAT(end_date,'%Y-%m-%d') >= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and (available > 0 or `limit`=0) limit 1;";
            $params = array(':coupon_code' => $coupon_code, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E110]Error while fetching bank list list Error: ' . $e->getMessage());
        }
    }

    public function getCouponDetails($coupon_id)
    {
        try {
            $sql = "  select coupon_id,coupon_code,descreption,type,percent,fixed_amount,start_date,end_date,`limit`,available from coupon where coupon_id=:coupon_id and is_active=1 and DATE_FORMAT(start_date,'%Y-%m-%d') <= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and DATE_FORMAT(end_date,'%Y-%m-%d') >= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and (available > 0 or `limit`=0);";
            $params = array(':coupon_id' => $coupon_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E110]Error while fetching bank list list Error: ' . $e->getMessage());
        }
    }

    /**
     * Method is used to sending email
     * 
     * @param type $str
     */
    public function updateNotificationtStatus($payment_request_id, $status)
    {
        try {
            $sql = "update payment_request set notification_sent=:status where payment_request_id=:request_id";
            $params = array(':status' => $status, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update payment request status Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getSurcharge($merchant_id, $amount, $fee_id = 0)
    {
        try {
            $sql = "select get_surcharge_amount(:merchant_id,:amount,:fee_id) as amount";
            $params = array(':merchant_id' => $merchant_id, ':amount' => $amount, ':fee_id' => $fee_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result['amount'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E30089]Error while getSurcharge Error: for Json[' . json_encode($params) . ']' . $e->getMessage());
        }
    }

    public function updateShortURL($payment_request_id, $url, $type = 1)
    {
        try {
            if ($type == 1) {
                $sql = "update payment_request set short_url=:url where payment_request_id=:request_id";
            } else {
                $sql = "update payment_transaction set short_url=:url where pay_transaction_id=:request_id";
            }
            $params = array(':url' => $url, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103-14]Error while update short url request Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getCustomerbreckup($customer_id, $staging = 0)
    {
        try {
            if ($staging == 1) {
                $table = 'staging_customer_column_values';
            } else {
                $table = 'customer_column_values';
            }
            $sql = "select  column_id,value from " . $table . " where customer_id=:customer_id";
            $params = array(':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            foreach ($rows as $row) {
                $values[$row['column_id']] = $row['value'];
            }
            return $values;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update payment request status Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    /**
     * Fetch template list of merchant associated with a user id
     * 
     * @return type
     */
    public function getTemplateList($userid, $sub_franchise_id = 0)
    {
        try {
            if ($sub_franchise_id > 0) {
                $sql = "select t.*,s.template_name as 'type' from invoice_template t inner join system_template s on t.template_type=s.template_type where t.user_id=:user_id and t.is_franchise=1 and t.is_active=:active and t.template_type <> 'event' order by t.template_id desc";
            } else {
                $sql = "select t.*,s.template_name as 'type' from invoice_template t inner join system_template s on t.template_type=s.template_type where t.user_id=:user_id and t.is_active=:active and t.template_type <> 'event' order by t.template_id desc";
            }
            $params = array(':user_id' => $userid, ':active' => 1);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E099]Error while fetching template list Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getTemplateMetadata($template_id)
    {
        try {
            $sql = "SELECT sort_order,column_id,default_column_value,position,is_mandatory,column_group_id,is_active,is_delete_allow,save_table_name,column_datatype,column_position,column_name,column_type,column_group_id,customer_column_id,template_id,function_id from invoice_column_metadata  where template_id=:template_id and is_active=1 and is_template_column=1  order by sort_order,column_id";
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E135]Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
        }
    }

    public function getTemplateInvoiceBreakup($payment_request_id, $staging = 0)
    {
        try {
            if ($staging == 1) {
                $type = 'Bulkupload';
            } else {
                $type = 'Invoice';
            }
            $sql = "call get_invoice_breckup(:id,:type)";
            $params = array(':id' => $payment_request_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error:  for payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function savePaytmSubscription($transaction_id, $customer_id, $patron_user_id, $merchant_id, $amount, $frequency_unit, $frequency, $expiry_date, $max_amount, $pg_id, $fee_id, $start_date, $grace_days)
    {
        try {
            $sql = "INSERT INTO `paytm_subscription`(`transaction_id`,`customer_id`,`patron_user_id`,`merchant_id`,`amount`,`frequency_unit`,`frequency`,`expiry_date`,`max_amount`,`start_date`,`grace_days`,`pg_id`,`fee_id`,`created_by`,`created_date`,`last_update_by`)
VALUES(:transaction_id,:customer_id,:patron_user_id,:merchant_id,:amount,:frequency_unit,:frequency,:expiry_date,:max_amount,:start_date,:grace_days,:pg_id,:fee_id,:patron_user_id,CURRENT_TIMESTAMP(),:patron_user_id);";
            $params = array(':transaction_id' => $transaction_id, ':customer_id' => $customer_id, ':patron_user_id' => $patron_user_id, ':merchant_id' => $merchant_id, ':amount' => $amount, ':frequency_unit' => $frequency_unit, ':frequency' => $frequency, ':expiry_date' => $expiry_date, ':max_amount' => $max_amount, ':pg_id' => $pg_id, ':fee_id' => $fee_id, ':start_date' => $start_date, ':grace_days' => $grace_days);
            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
            return $id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E115298]Error while savePaytmSubscription Error:  for transaction id[' . $transaction_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function SaveDatachange($user_id, $source_type, $source_id, $status, $customer_id, $fname, $lname, $email, $mobile, $address, $city, $state, $zip)
    {
        try {
            $sql = "call `save_change_details`(:user_id,:source_type,:source_id,:status,:customer_id,:fname,:lname,:email,:mobile,:address,:city,:state,:zip)";
            $params = array(
                ':user_id' => $user_id, ':source_type' => $source_type, ':source_id' => $source_id, ':status' => $status, ':customer_id' => $customer_id,
                ':fname' => trim($fname), ':lname' => trim($lname), ':email' => trim($email), ':mobile' => trim($mobile), ':address' => trim($address), ':city' => trim($city), ':state' => trim($state), ':zip' => trim($zip)
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E161-1]Error while SaveDatachange Error: for user_id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function getPaytmSubscriptionList()
    {
        try {
            $sql = "select * from paytm_subscription where subscription_status=1 and DATE_FORMAT(last_paid_date,'%Y-%m')<>DATE_FORMAT(now(),'%Y-%m') and expiry_date>now()";
            $params = array();
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-1225]Error while List value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getCustomerPendingBill($customer_id, $start_date)
    {
        try {
            $sql = "call get_customer_pending_invoices(:customer_id,:start_date);";
            $params = array(':customer_id' => $customer_id, ':start_date' => $start_date);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105812]Error while getCustomerPendingBill value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getShortURL($id)
    {
        try {
            $sql = "select display_url from prepaid_plan p inner join merchant m on p.merchant_id=m.merchant_id where p.plan_id=:id";
            $params = array(':id' => $id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['display_url'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-1234]Error while List value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getMerchantPG($merchant_id, $franchise_id = 0, $currency = 'INR')
    {
        try {
            $sql = "select pg_type,pg_val4,f.pg_id,f.fee_detail_id,f.pg_surcharge_enabled,enable_tnc from merchant_fee_detail f inner join payment_gateway g on f.pg_id=g.pg_id where f.is_active=1 
                and merchant_id=:merchant_id and franchise_id=:franchise_id and f.currency like '%" . $currency . "%'";
            $params = array(':merchant_id' => $merchant_id, ':franchise_id' => $franchise_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E106+98]Error while fetching pg fee details. merchant id ' . $merchant_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getCustomerDetails($customer_id, $merchant_id)
    {
        try {
            $sql = "call get_customer_details(:customer_id,:merchant_id)";
            $params = array(':customer_id' => $customer_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E106+989]Error while fetching customer details. customer id ' . $customer_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getInvoiceNumber($user_id, $auto_invoice_id)
    {
        try {
            $is_exist = TRUE;
            while ($is_exist == TRUE) {
                $sql = 'select generate_invoice_number(:id) as $auto_invoice_number';
                $params = array(':id' => $auto_invoice_id);
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                $auto_invoice_number = $row['$auto_invoice_number'];
                $is_exist = $this->isExistInvoiceNumber($user_id, $auto_invoice_number);
            }
            return $auto_invoice_number;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-78]Error while getInvoiceNumber auto id ' . $auto_invoice_id . ' user id:' . $user_id . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function isMaxAmount($user_id, $amount)
    {
        try {
            if (substr($user_id, 0, 1) == 'U') {
                $merchant_det = $this->getSingleValue('merchant', 'user_id', $user_id);
            } else {
                $merchant_det = $this->getSingleValue('merchant', 'merchant_id', $user_id);
            }
            if ($merchant_det['merchant_plan'] == 2) {
                $merchant_set = $this->getSingleValue('merchant_setting', 'merchant_id', $merchant_det['merchant_id']);
                if ($amount > $merchant_set['max_transaction']) {
                    return $merchant_set['max_transaction'];
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138+11]Error while checking invoice max amount Error: for amount [' . $amount . ']' . ' User id:' . $user_id . $e->getMessage());
        }
    }

    public function isExistInvoiceNumber($user_id, $invoice_number, $payment_request_id = NULL)
    {
        try {
            if ($invoice_number != '' && substr($invoice_number, 0, 16) != 'System generated') {
                $sql = 'select payment_request_id from payment_request where user_id=:user_id and invoice_number=:invoice_number and payment_request_status<>3 and is_active=1';
                if ($payment_request_id != NULL) {
                    $sql .= " and payment_request_id<>'" . $payment_request_id . "' ;";
                }
                $params = array(':user_id' => $user_id, ':invoice_number' => $invoice_number);
                $this->db->exec($sql, $params);
                $data = $this->db->single();
                if (!empty($data)) {
                    return $data['payment_request_id'];
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E138+9]Error while checking invoice number exist Error: for invoice number [' . $invoice_number . ']' . ' User id:' . $user_id . $e->getMessage());
        }
    }

    function excelexport($name, $rows, $hide_col = array(), $subject = 'NA', $title = 'Swipez')
    {
        if (count($rows) > 50000) {
            $this->setError("Data limit exceed", 'You can export maximum 50000 Rows');
            header('Location:/error');
        }
        $hide = array('@count', '@fcount', '@totalSum', 'customer_id', 'payment_request_type', 'billing_cycle_id', 'display_invoice_no', 'created_by', 'patron_id', 'late_payment', 'customer_status', 'payment_status', 'address2', 'merchant_id', 'customer_group', '@count');
        if (!empty($hide_col)) {
            $hide = array_merge($hide, $hide_col);
        }
        $cnt = 0;
        foreach ($rows as $val) {
            foreach ($val as $key => $val2) {
                if (!in_array($key, $hide)) {
                    if ($cnt == 0) {
                        $column_name[] = ucfirst(str_replace('_', ' ', $key));
                    }
                    $values[$cnt][] = $val2;
                }
            }
            $cnt++;
        }

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("swipez")
            ->setLastModifiedBy("swipez")
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription("swipez ");
        #create array of excel column
        $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $column = array();
        foreach ($first as $s) {
            $column[] = $s;
        }
        foreach ($first as $f) {
            foreach ($first as $s) {
                $column[] = $f . $s;
            }
        }
        $int = 0;
        foreach ($column_name as $val) {
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int] . '1', $val);
            $int = $int + 1;
        }
        $rint = 2;
        foreach ($values as $val) {
            $vint = 0;
            foreach ($val as $vall) {
                if (strlen($vall) > 10 && is_numeric($vall)) {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($column[$vint] . $rint, $vall, PHPExcel_Cell_DataType::TYPE_STRING);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$vint] . $rint, $vall);
                }
                $vint = $vint + 1;
            }
            $rint++;
        }


        $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
            ->setSize(10);
        $objPHPExcel->getActiveSheet()->setTitle($title);
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $column[$int] . '1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'AAAADD')
                )
            )
        );
        $autosize = 0;
        while ($autosize < $int) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize] . '1', 0, -1))->setAutoSize(true);
            $autosize++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        exit;
    }

    public function isValidPackageInvoice($merchant_id, $invcount, $type, $merchant_plan = 0)
    {
        try {
            $merchant = $this->getSingleValue('merchant', 'merchant_id', $merchant_id);
            $paid_package = array(9, 3, 12, 13, 14, 15);
            if (in_array($merchant['merchant_plan'], $paid_package)) {
                if ($merchant['package_expiry_date'] < date('Y-m-d')) {
                    return false;
                }
            }
            return true;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E505]Error while is Valid Package Invoice Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function isMerchantCoupon($merchant_id)
    {
        try {
            $sql = "SELECT count(*) as mcount FROM coupon where user_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['mcount'] > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E505]Error while fetching merchant overview Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    function isActiveService($merchant_id, $service)
    {
        try {
            $sql = "SELECT " . $service . " FROM account where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return TRUE;
            }
            if ($row[$service] == 1) {
                return TRUE;
            } else {
                header('Location:/merchant/profile/featuredenied');
                exit();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E00101]Error while getpreferences User id: ' . $user_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getShortName($value, $number)
    {
        if (strlen($value) > $number) {
            return substr($value, 0, $number - 4) . '...';
        } else {
            return $value;
        }
    }

    public function getinvoiceTax($payment_request_id, $staging = 0)
    {
        try {
            if ($staging == 1) {
                $table = 'staging_invoice_tax';
            } else {
                $table = 'invoice_tax';
            }
            $sql = "select  tax_name,t.tax_type,t.tax_id,i.id,i.tax_percent,applicable,i.tax_amount "
                . "from " . $table . " i inner join merchant_tax t on t.tax_id=i.tax_id where i.payment_request_id=:payment_request_id and applicable>0 and i.is_active=1";
            $params = array(':payment_request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update payment request status Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function saveLoginToken($user_id, $email)
    {
        try {
            $token = rand(1000, 99999) . time();
            $token = md5($token);
            $sql = "insert into login_token (user_id,email,token,created_by,created_date) values(:user_id,:email,:token,:user_id,CURRENT_TIMESTAMP())";
            $params = array(':user_id' => $user_id, ':email' => $email, ':token' => $token);
            $this->db->exec($sql, $params);
            return $token;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update payment request status Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getMerchantProfile($merchant_id, $profile_id = 0, $column = null)
    {
        try {
            if ($profile_id > 0) {
                $sql = "select * from merchant_billing_profile where id=:id";
                $params = array(':id' => $profile_id);
            } else {
                $sql = "select * from merchant_billing_profile where merchant_id=:merchant_id and is_default=1";
                $params = array(':merchant_id' => $merchant_id);
            }
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($column == null) {
                return $row;
            } else {
                return $row[$column];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get custom configure receipt fileds
     */
    public function getCustomReceiptFields($payment_request_id, $customer_id, $column_id, $defaultReceiptFieldsLabels = null, $patron_name = null)
    {
        if ($column_id != '') {
            $find_invoice_metadata_details = $this->getSingleValue('invoice_column_metadata', 'column_id', $column_id);

            if ($find_invoice_metadata_details['save_table_name'] == 'customer') {
                if ($find_invoice_metadata_details['column_name'] == $defaultReceiptFieldsLabels['Customer code'] || $find_invoice_metadata_details['column_name'] == 'Customer code') {
                    $column_name = "customer_code";
                    //$getValue = $this->common->getRowValue('customer_code', 'customer', 'customer_id', $customer_id);
                    //$receipt_info['custom_fields'][$find_invoice_metadata_details['column_name']]= $getValue;
                } else if ($find_invoice_metadata_details['column_name'] == $defaultReceiptFieldsLabels['Patron Email ID']) {
                    $column_name = "email";
                } else if ($find_invoice_metadata_details['column_name'] == $defaultReceiptFieldsLabels['Mobile no'] || $find_invoice_metadata_details['column_name'] == 'Mobile no') {
                    $column_name = "mobile";
                } else if ($find_invoice_metadata_details['column_name'] == 'Address') {
                    $column_name = "address";
                } else if ($find_invoice_metadata_details['column_name'] == 'City') {
                    $column_name = "city";
                } else if ($find_invoice_metadata_details['column_name'] == 'State') {
                    $column_name = "state";
                } else if ($find_invoice_metadata_details['column_name'] == 'Zipcode') {
                    $column_name = "zipcode";
                } else if ($find_invoice_metadata_details['column_name'] == $defaultReceiptFieldsLabels['Patron Name']) {
                    $column_name = $patron_name;
                    return $column_name;
                }

                if ($column_name != '') {
                    $getValue = $this->getRowValue($column_name, 'customer', 'customer_id', $customer_id);
                    return $getValue;
                    //$receipt_info[$find_invoice_metadata_details['column_name']]= $getValue;
                }
            } else if ($find_invoice_metadata_details['save_table_name'] == 'customer_metadata') {
                //take values customer_column_values table
                //find customer metadata values from customer_column_values
                $getValue = $this->getRowValue('value', 'customer_column_values', 'column_id', $find_invoice_metadata_details['customer_column_id'], 0, " and customer_id='" . $customer_id . "'");
                //$receipt_info[$find_invoice_metadata_details['column_name']]= $getValue;
                return $getValue;
            } else if ($find_invoice_metadata_details['save_table_name'] == 'request') {
                if ($find_invoice_metadata_details['column_name'] == 'Due date') {
                    $column_name = 'due_date';
                } else if ($find_invoice_metadata_details['column_name'] == 'Billing cycle name') {
                    $column_name = 'billing_cycle_id';
                } else if ($find_invoice_metadata_details['column_name'] == 'Bill date') {
                    $column_name = 'bill_date';
                }
                if ($column_name != '') {
                    $getValue = $this->getRowValue($column_name, 'payment_request', 'payment_request_id', $payment_request_id);
                    //$receipt_info[$find_invoice_metadata_details['column_name']]= $getValue;
                    return $getValue;
                }
            } else if ($find_invoice_metadata_details['save_table_name'] == 'metadata') {
                //find value for column_id
                $getValue = $this->getRowValue('value', 'invoice_column_values', 'column_id', $find_invoice_metadata_details['column_id'], 0, " and payment_request_id='" . $payment_request_id . "'");
                //$receipt_info[$find_invoice_metadata_details['column_name']]= $getValue;
                return $getValue;
            }
        }
        return true;
    }

    public function setReceiptFields($receipt_info = null, $plugin_value = null, $type, $request_type = "web")
    {
        $custom_fields = array();
        $default_column = null;
        if ($request_type == 'web') {
            $default_column = $this->session->get('customer_default_column');
        }
        if ($default_column != null) {
            if (isset($default_column['customer_code'])) {
                $defaultReceiptFieldsLabels['Customer code'] = $default_column['customer_code'];
            }
            if (isset($default_column['customer_name'])) {
                $defaultReceiptFieldsLabels['Patron Name'] = $default_column['customer_name'];
            }
            if (isset($default_column['email'])) {
                $defaultReceiptFieldsLabels['Patron Email ID'] = $default_column['email'];
            }
            if (isset($default_column['mobile'])) {
                $defaultReceiptFieldsLabels['Mobile no'] = $default_column['mobile'];
            }
        } else {
            $defaultReceiptFieldsLabels['Customer code'] = 'Customer code';
            $defaultReceiptFieldsLabels['Patron Name'] = 'Patron Name';
            $defaultReceiptFieldsLabels['Patron Email ID'] = 'Patron Email ID';
            $defaultReceiptFieldsLabels['Mobile no'] = 'Mobile no';
        }

        foreach ($plugin_value as $k => $val) {
            if ($val['column_id'] == 0) {
                if ($val['label'] == 'Patron Name' || $val['label'] == $defaultReceiptFieldsLabels['Patron Name']) {
                    $custom_fields[$val['label']] = $receipt_info['patron_name'];
                } elseif ($val['label'] == 'Patron Email ID' || $val['label'] == $defaultReceiptFieldsLabels['Patron Email ID']) {
                    $custom_fields[$val['label']] = $receipt_info['patron_email'];
                } elseif ($val['label'] == 'Payment Towards') {
                    $custom_fields[$val['label']] = $receipt_info['company_name'];
                } elseif ($val['label'] == 'Payment Ref Number') {
                    if ($type == 'Online') {
                        $custom_fields[$val['label']] = $receipt_info['ref_no'];
                    } else if ($type == 'Offline') {
                        $custom_fields[$val['label']] = $receipt_info['transaction_id'];
                    }
                } elseif ($val['label'] == 'Transaction Ref Number') {
                    if ($type == 'Online') {
                        $custom_fields[$val['label']] = $receipt_info['transaction_id'];
                    }
                } elseif ($val['label'] == 'Payment Date & Time') {
                    $custom_fields[$val['label']] = $receipt_info['date'];
                } elseif ($val['label'] == 'Payment Amount') {
                    $custom_fields[$val['label']] = $receipt_info['amount'] . '/-';
                } elseif ($val['label'] == 'Mode of Payment') {
                    $custom_fields[$val['label']] = $receipt_info['payment_mode'];
                }
                //$receipt_info['custom_fields'][$val['label']] = '-';
            } else {
                $getValue = $this->getCustomReceiptFields($receipt_info['payment_request_id'], $receipt_info['customer_id'], $val['column_id'], $defaultReceiptFieldsLabels, $receipt_info['patron_name']);
                $custom_fields[$val['label']] = $getValue;
            }
        }
        return $custom_fields;
    }
}
