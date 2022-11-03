<?php

/**
 * This class calls necessary db objects to handle buk payment requests 
 *
 * @author Paresh
 */
class BulkuploadModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch list of billing cycle with a user id
     * 
     * @return type
     */
    public function getCycleList($bulk_id, $userid)
    {
        try {
            $sql = "select distinct billing_cycle_id as id,cycle_name as name from billing_cycle_detail where user_id=:user_id";
            $params = array(':user_id' => $userid);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E248]Error while fetching cycle list Error: for user id [' . $userid . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
        }
    }

    /**
     * Fetch number of bulkupload for current date
     */
    public function gettotalrows($merchant_id, $type = 1)
    {
        try {
            $sql = "SELECT sum(total_rows) as total_rows FROM bulk_upload where merchant_id=:merchant_id and type=:type and DATE_FORMAT(created_date, '%Y-%m-%d') = CURDATE();";
            $params = array(':merchant_id' => $merchant_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['total_rows'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E288]Error while fetching cycle list Error: for merchant id [' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    /**
     * Fetch number of todays bulkuploads for merchant
     */
    public function saveBulkUpload($merchant_filename, $system_filename, $merchant_id, $status, $total_rows, $type, $user_id = '', $sub_type = '')
    {
        try {
            $sql = "INSERT INTO `bulk_upload`(`merchant_id`,`merchant_filename`,`system_filename`,`type`,`sub_type`,
`status`,`total_rows`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)VALUES(:merchant_id,:merchant_filename,:system_filename,:type,:sub_type,:status,:total_rows,:created_by,CURRENT_TIMESTAMP(),:created_by,CURRENT_TIMESTAMP());";
            $params = array(':merchant_id' => $merchant_id, ':merchant_filename' => $merchant_filename, ':system_filename' => $system_filename, ':type' => $type, ':sub_type' => $sub_type, ':status' => $status, ':total_rows' => $total_rows, ':created_by' => $this->system_user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E250]Error while fetching cycle list Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Move bulk upload excel file into directories
     */
    public function moveExcelFile($merchant_id, $folder, $filename)
    {
        try {

            $filename2 = 'uploads/Excel/' . $merchant_id . '/' . $folder . '/' . $filename . '';
            $movepath = 'uploads/Excel/' . $merchant_id . '/deleted/' . $filename . '';

            if (file_exists($filename2)) {
                if ((rename($filename2, $movepath))) {
                    return True;
                } else {

                    SwipezLogger::error(__CLASS__, '[E274-2]Error while move excel Error: file 1' . $filename2 . '/ file 2' . $movepath);
                }
            } else {

                SwipezLogger::error(__CLASS__, '[E274-1]Error file not found ' . $filename2);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E274]Error while move excel Error: for merchant id [' . $merchant_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Upload bulk upload excel file
     */
    public function uploadExcel($image_file, $merchant_id, $iserror, $total_rows, $type = 1, $sub_type = '')
    {
        try {
            $folder = 'staging';
            $status = 2;
            $filename = 'uploads/Excel/' . $merchant_id . '';
            if (file_exists($filename)) {
            } else {
                mkdir($filename, 0755);
                mkdir($filename . '/error', 0755);
                mkdir($filename . '/staging', 0755);
                mkdir($filename . '/deleted', 0755);
            }
            if ($iserror == TRUE) {
                $folder = 'error';
                $status = 1;
            }
            $merchant_filename = basename($image_file['name']);
            $merchant_filename = preg_replace('/[^\p{L}\p{N}\s]/u', '', $merchant_filename);
            $merchant_filename = str_replace(' ', '_', $merchant_filename);
            $filename = $image_file['name'];
            $ext = substr($filename, strrpos($filename, '.') + 1);
            $system_filename = str_replace($ext, '', $merchant_filename) . '_' . date('Y_m_d_G_ia') . '.' . $ext;
            $newname = 'uploads/Excel/' . $merchant_id . '/' . $folder . '/' . $system_filename;
            if ((move_uploaded_file($image_file['tmp_name'], $newname))) {
                return $this->saveBulkUpload($merchant_filename, $system_filename, $merchant_id, $status, $total_rows, $type, '', $sub_type);
            } else {
                SwipezLogger::error(__CLASS__, '[E251]Error while uploading excel sheet Error: for merchant id [' . $merchant_id . '] ');
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E252]Error while uploading excel Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    /**
     * Fetch bulk upload status list
     */
    public function getStatusList($type = 'payment_request_status')
    {
        try {
            $sql = "select config_key, config_value,description from config where config_type=:type";
            $params = array(':type' => $type);
            $this->db->exec($sql, $params);
            return $this->db->resultset();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E253]Error while fetching status list Error: ' . $e->getMessage());
        }
    }

    public function getPaymentRequestList($f_fromdate, $f_todate, $name, $bulk_upload, $user_id, $column_name, $franchise_id = 0)
    {
        try {
            $converter = new Encryption;
            $column_name = implode('~', $column_name);
            $bulk_upload = ($bulk_upload > 0) ? $bulk_upload : 0;
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $sql = "call get_merchant_viewrequest(:user_id,:from_date,:to_date,:name,:bulk_upload,:payment_request_type,:column_name,:franchise_id);";
            $params = array(':user_id' => $user_id, ':from_date' => $f_fromdate, ':to_date' => $f_todate, ':name' => $name, ':bulk_upload' => $bulk_upload, ':payment_request_type' => 3, ':column_name' => $column_name, ':franchise_id' => $franchise_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            $int = 0;
            foreach ($list as $item) {
                $list[$int]['paylink'] = $converter->encode($item['payment_request_id']);
                $int++;
            }
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E109]Error while getting payment request list Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getBulkuploadList($merchant_id, $from_date, $to_date)
    {
        try {
            $sql = "select bulk_upload_id,total_rows,merchant_id,merchant_filename,system_filename,status,bulk_upload.created_date,config_value from bulk_upload inner join config on bulk_upload.status = config.config_key where merchant_id=:merchant_id and DATE_FORMAT(bulk_upload.created_date,'%Y-%m-%d')>=:from_date and DATE_FORMAT(bulk_upload.created_date,'%Y-%m-%d')<=:to_date and config.config_type='bulk_upload_status' and status not in (6,7)  and type=1 order by bulk_upload_id desc";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    /**
     * Fetch bulk upload details with bulk id
     */
    public function getBulkuploaddetail($merchant_id, $bulk_id)
    {
        try {
            $sql = "select bulk_upload_id,merchant_id,error_json,merchant_filename,system_filename,status,bulk_upload.created_date,config_value from bulk_upload inner join config on bulk_upload.status = config.config_key where bulk_upload_id=:bulk_id and merchant_id=:merchant_id and config.config_type='bulk_upload_status'";
            $params = array(':merchant_id' => $merchant_id, ':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E255]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  and for bulk id [' . $bulk_id . ']' . $e->getMessage());
        }
    }

    /**
     * Update bulk upload status
     */
    public function updateBulkUploadStatus($bulk_id, $status)
    {
        try {
            $sql = "update bulk_upload set status=:status where bulk_upload_id=:bulk_id";
            $params = array(':status' => $status, ':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E276]Error while pupdate bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Fetch staging invoice list with bulk id
     */
    public function getImportInvoiceList($bulk_id, $user_id)
    {
        try {
            $sql = "call get_staging_viewrequest(:bulk_id,:user_id);";
            $params = array(':bulk_id' => $bulk_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E256]Error while getting payment request list Error: for user id[' . $user_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Get staging payment request details
     */
    public function getPaymentRequestDetails($payment_req_id, $user_id)
    {
        try {
            $sql = "call get_staging_invoice_details(:user_id,:payment_req)";
            $params = array(':user_id' => $user_id, ':payment_req' => $payment_req_id);
            $this->db->exec($sql, $params);
            return $this->db->single();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E258]Error while getting payment request details Error: for user id[' . $user_id . '] and for payment request id [' . $payment_req_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Get staging invoice breckup for view invoice
     */
    public function getInvoiceBreakup($payment_request_id)
    {
        try {
            $sql = "SELECT icv.invoice_id,icv.column_id,icv.value,icm.column_name,icm.position,icm.column_type,icm.column_group_id,icm.column_position,
	icm.template_id from staging_invoice_values as icv inner join invoice_column_metadata as icm on icv.column_id = icm.column_id where icv.payment_request_id=:id and icv.is_active=1   order by column_position , invoice_id";
            $params = array(':id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error:  for payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getTemplateInvoiceBreakup($payment_request_id)
    {
        try {
            $sql = "call get_staging_invoice_breckup(:id)";
            $params = array(':id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Update staging invoice
     */
    public function updateInvoice(
        $payment_request_id,
        $user_id,
        $customer_id,
        $existid,
        $existvalue,
        $billdate,
        $duedate,
        $cyclename,
        $value,
        $id,
        $narrative,
        $amount,
        $tax,
        $particular_total,
        $tax_total,
        $previous_dues,
        $last_payment,
        $adjustment,
        $supplier_id,
        $supplier,
        $late_fee,
        $advance,
        $expiry_date,
        $notify_patron,
        $coupon_id,
        $franchise_id,
        $franchise_name_invoice,
        $vendor_id = 0,
        $is_partial,
        $partial_min_amount
    ) {
        try {
            $coupon_id = ($coupon_id > 0) ? $coupon_id : 0;
            $notify_patron = ($notify_patron > 0) ? $notify_patron : 0;
            $expiry_date = (strlen($expiry_date) > 5) ? $expiry_date : NULL;
            $previous_dues = ($previous_dues > 0) ? $previous_dues : 0;
            $last_payment = ($last_payment > 0) ? $last_payment : 0;
            $adjustment = ($adjustment > 0) ? $adjustment : 0;

            $is_partial = ($is_partial > 0) ? $is_partial : 0;
            $partial_min_amount = ($partial_min_amount > 0) ? $partial_min_amount : 0;

            $amount = ($amount > 0) ? $amount : 0;
            $supplier_id = ($supplier_id > 0) ? $supplier_id : 0;
            $tax = ($tax > 0) ? $tax : 0;
            $supplier = implode(',', $supplier);
            $ids = implode('~', $id);
            $values = implode('~', $value);
            $existid = implode('~', $existid);
            $existvalue = implode('~', $existvalue);

            $sql = "call `update_staging_invoicevalues`(:payment_request_id,:existid,:existvalue,:values,:ids,:user_id,:customer_id,:billdate,:duedate,:cyclename,
                :narrative,:amount,:tax,:previous_dues,:last_payment,:adjustment,:supplier_id,:supplier,:late_fee,:advance,:expiry_date,:notify_patron,:coupon_id,:franchise_id,:franchise_name_invoice,:vendor_id,:is_partial,:partial_min_amount);";

            $params = array(
                ':payment_request_id' => $payment_request_id, ':existid' => $existid, ':existvalue' => $existvalue, ':values' => $values, ':ids' => $ids,
                ':user_id' => $user_id, ':customer_id' => $customer_id, ':billdate' => $billdate, ':duedate' => $duedate, ':cyclename' => $cyclename, ':narrative' => $narrative,
                ':amount' => $amount, ':tax' => $tax, ':previous_dues' => $previous_dues, ':last_payment' => $last_payment, ':adjustment' => $adjustment, ':supplier_id' => $supplier_id, ':supplier' => $supplier, ':late_fee' => $late_fee, ':advance' => $advance,
                ':expiry_date' => $expiry_date, ':notify_patron' => $notify_patron, ':coupon_id' => $coupon_id, ':franchise_id' => $franchise_id, ':franchise_name_invoice' => $franchise_name_invoice, ':vendor_id' => $vendor_id, ':is_partial' => $is_partial, ':partial_min_amount' => $partial_min_amount
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
                $row['Message'] = 'success';
                return $row;
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $row;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101]Error while saving invoice Error:  for user id[' . $userid . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
