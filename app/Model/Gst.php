<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Home
 *
 * @author Abhijeet
 */

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Gst extends Model
{
    public function setJobID($merchant_id, $gst, $expense_from_month_year, $expense_to_month_year, $gst_from_month_year, $gst_to_month_year, $user_id)
    {
        $expense_from = explode('-', $expense_from_month_year);
        $expense_to = explode('-', $expense_to_month_year);
        $gst_from = explode('-', $gst_from_month_year);
        $gst_to = explode('-', $gst_to_month_year);

        $id = DB::table('gstr2b_recon_jobs')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'gstin' => $gst,
                'gst_from_month' =>   $gst_from[0],
                'gst_from_year' =>  $gst_from[1],
                'gst_to_month' =>  $gst_to[0],
                'gst_to_year' =>  $gst_to[1],
                'expense_from_month' => $expense_from[0],
                'expense_from_year' =>  $expense_from[1],
                'expense_to_month' =>  $expense_to[0],
                'expense_to_year' =>  $expense_to[1],
                'status' => 'processing',
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );

        return $id;
    }

    public function getJobList($merchant_id)
    {

        $retObj =  DB::select("SELECT a.*, b.company_name,
        CONCAT(UCASE(LEFT(a.status, 1)), LCASE(SUBSTRING(a.status, 2))) statusp, 
        DATE_FORMAT(a.created_date, '%D %b %Y at %I:%i') created_date_formatted
        FROM gstr2b_recon_jobs a
        JOIN merchant_billing_profile b on b.gst_number = a.gstin and a.merchant_id = b.merchant_id
        WHERE a.merchant_id = '$merchant_id' 
        ORDER by 1 DESC");

        return $retObj;
    }

    public function getGSTList($merchant_id)
    {

        $retObj =  DB::select("SELECT *
        FROM merchant_billing_profile
        WHERE merchant_id = '$merchant_id' 
        AND gst_number !=''
        AND is_active = '1'
        ORDER by 1 DESC");

        return $retObj;
    }

    public function getJobDetails($job_id)
    {

        $retObj =  DB::select("SELECT *
        FROM gstr2b_recon_jobs
        WHERE id = '$job_id'");

        return $retObj;
    }

    public function getExpenseData($merchant_id, $from, $to)
    {
        $expense_from = explode('-', $from);
        $expense_to = explode('-', $to);

        if ($from == $to) {
            $retObj =  DB::select("SELECT * FROM expense 
            where  `merchant_id` = '$merchant_id' 
            AND type  = '1' AND is_active = '1'
            AND bill_date > DATE_FORMAT(CURDATE(), LAST_DAY('$expense_from[1]-$expense_from[0]-01')) - INTERVAL 3 MONTH");
        } else {
            $retObj =  DB::select("SELECT * FROM expense 
            where  `merchant_id` = '$merchant_id' 
            AND type  = '1' AND is_active = '1'
            AND bill_date between '$expense_from[1]-$expense_from[0]-01' and '$expense_to[1]-$expense_to[0]-01'");
        }

        return $retObj;
    }

    public function insertInvoice($array)
    {
        $id = DB::table('invoice_comparision')->insertGetId(
            ($array)
        );
        return $id;
    }

    public function insertInvoiceParticular($array)
    {
        $id = DB::table('invoice_comparision_detail')->insertGetId(
            ($array)
        );
        return $id;
    }

    public function insertJobDetails($table_name, $type, $job_id, $gst_data, $vendor_name, $gst_invoice_level_data,  $cgst, $sgst, $igst, $user_id, $taxable_amount)
    {
        if ($table_name == 'invoice_comparision') {
            $array = [
                'job_id' => $job_id,
                'vendor_gstin' => $gst_data["ctin"],
                'vendor_name' => $vendor_name,
                'gst_request_id' =>  '',
                'gst_request_number' =>  $gst_invoice_level_data["inum"],
                'gst_request_date' =>  $gst_invoice_level_data["idt"],
                'gst_request_total_amount' =>   $gst_invoice_level_data["val"],
                'gst_request_taxable_amount' => $taxable_amount,
                'gst_request_type' =>    $gst_invoice_level_data["inv_typ"],
                'gst_request_state' =>  $gst_invoice_level_data["pos"],
                'gst_request_cgst' =>  $cgst,
                'gst_request_sgst' => $sgst,
                'gst_request_igst' =>  $igst,
                'created_by' => $user_id,
                'last_update_by' =>  $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ];
        } else {
            $array = [
                'gstr_2a_table_id' =>  $job_id,
                'gst_item_number' =>   $gst_data["num"],
                'gst_item_taxable_value' =>  $gst_data["itm_det"]["txval"],
                'gst_item_tax_rate' => $gst_data["itm_det"]["rt"],
                'gst_item_cgst' =>  $cgst,
                'gst_item_sgst' => $sgst,
                'gst_item_igst' =>   $igst,
                'created_by' => $this->user_id,
                'last_update_by' => $this->user_id,
                'created_date' => date('Y-m-d H:i:s')
            ];
        }
        $id = DB::table($table_name)->insertGetId(
            ($array)
        );
        return $id;
    }

    public function updateInvoice($job_id, $gstin, $gst_invoice_number, $update_array)
    {
        $affected = DB::table('invoice_comparision')
            ->where('job_id', $job_id)
            ->where('vendor_gstin', $gstin)
            ->where('gst_request_number', $gst_invoice_number)
            ->update($update_array);

        return $affected;
    }

    public function updateInvoiceParticular($id, $update_array)
    {
        $affected = DB::table('invoice_comparision')
            ->where('gstr_2a_table_id', $id)
            ->update($update_array);

        return $affected;
    }

    public function updateJobDetails($table_name, $job_id, $gstin, $gst_invoice_number, $expense_data, $vendor_name)
    {
        if ($table_name == 'invoice_comparision') {

            $array = [
                'purch_request_id' =>  $expense_data["expense_no"],
                'purch_request_number' => $expense_data["invoice_no"],
                'purch_request_date' =>   $expense_data["bill_date"],
                'purch_request_total_amount' =>  $expense_data["total_amount"],
                'purch_request_taxable_amount' => $expense_data["tds"],
                'purch_request_type' =>   '',
                'purch_request_state' =>  '',
                'purch_request_cgst' =>  $expense_data["cgst_amount"],
                'purch_request_sgst' => $expense_data["sgst_amount"],
                'purch_request_igst' =>  $expense_data["igst_amount"],
                'vendor_name' => $vendor_name,
            ];
        }

        $affected = DB::table($table_name)
            ->where('job_id', $job_id)
            ->where('vendor_gstin', $gstin)
            ->where('gst_request_number', $gst_invoice_number)
            ->update($array);

        return $affected;
    }

    public function updateJobDetailsParticular($table_name, $id, $expense_data)
    {
        if ($table_name == 'invoice_comparision_detail') {

            $array = [
                'purch_item_number' =>  $expense_data["id"],
                'purch_item_taxable_value' =>    $expense_data["amount"],
                'purch_item_tax_rate' =>   $expense_data["tax"],
                'purch_item_cgst' =>   $expense_data["cgst_amount"],
                'purch_item_sgst' =>   $expense_data["sgst_amount"],
                'purch_item_igst' =>   $expense_data["igst_amount"],
            ];
        }

        $affected = DB::table($table_name)
            ->where('gstr_2a_table_id', $id)
            ->update($array);

        return $affected;
    }

    public function getExpenseParticularData($expense_id)
    {
        $retObj =  DB::select("SELECT * FROM expense_detail 
        WHERE  `expense_id` = '$expense_id'
        AND is_active = '1'");

        return $retObj;
    }

    public function getFinalComparisionData($job_id)
    {
        $retObj =  DB::select("SELECT * FROM invoice_comparision 
        where  `job_id` = '$job_id'");

        return $retObj;
    }

    public function getColumnValue($table, $where, $value, $column_name)
    {
        $retObj = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value)
            ->first();

        if ($retObj != null) {
            return $retObj->value;
        } else {
            return '';
        }
    }

    public function getColumnValueV2($table, $where, $value, $where1, $value2, $column_name)
    {
        $retObj = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value)
            ->where($where1, $value2)
            ->first();

        if ($retObj != null) {
            return $retObj->value;
        } else {
            return '';
        }
    }

    public function updateTable($table, $where, $whvalue, $col, $val)
    {
        DB::table($table)
            ->where($where, $whvalue)
            ->update([
                $col => $val
            ]);
    }

    public function getSummarybyJobID($job_id,  $gst_in = 'none', $gstin_field = '', $gstin_condition = '', $gstin_condition_value = '', $between_from = '', $between_to = '',  $taxable_difference_sort = '', $ismail = '', $page = '')
    {
        $gst_cond = '';
        $field_cond = '';
        $sort_cond = '';
        $limit_cond = '';
        $where_cond = '';

        if ($gst_in != 'none') {
            $gst_cond = ' AND vendor_gstin = "' . $gst_in . '"';
            $field_cond = ' WHERE vendor_gstin = "' . $gst_in . '"';
        }

        if ($gstin_field != '') {
            if ($gstin_field == 'tax_difference') {
                $if_cond = "diff_total_amount";
            } else if ($gstin_field == 'doc_difference') {
                $if_cond = "diff_of_doc";
            } else if ($gstin_field == 'taxable_difference') {
                $if_cond = "tax_value";
            }

            if ($gstin_condition == '>') {
                $field_cond = ' WHERE ' . $if_cond . ' > "' . $gstin_condition_value . '" ' . $gst_cond;
            } else if ($gstin_condition == '<') {
                $field_cond = ' WHERE ' . $if_cond . ' < "' . $gstin_condition_value . '" ' . $gst_cond;
            } else if ($gstin_condition == '=') {
                $field_cond = ' WHERE ' . $if_cond . ' = "' . $gstin_condition_value . '" ' . $gst_cond;
            } else if ($gstin_condition == 'between') {
                $field_cond = ' WHERE ' . $if_cond . ' BETWEEN "' . $between_from . '" AND "' . $between_to . '" ' . $gst_cond;
            }
        } else {
            if ($gst_in != 'none') {
                $field_cond = ' WHERE vendor_gstin = "' . $gst_in . '"';
            }
        }

        if ($taxable_difference_sort != '') {
            $sort_cond = " ORDER BY tax_value $taxable_difference_sort";
        }

        if ($ismail) {
            $limit_cond = ' LIMIT 10';
        } else if ($page != '') {
            $end = 10;
            $start = (($page - 1) * $end);
            $limit_cond = ' LIMIT ' . $start . ',' . $end;
        }

        $where_cond = $field_cond . $sort_cond . $limit_cond;

        $retObj = DB::select("call `get_gstr2a_summary`('" . $job_id . "', '" . $where_cond . "')");

        return $retObj;
    }

    public function getVendorListbyJobID($job_id)
    {
        $retObj =  DB::select("SELECT distinct concat(vendor_gstin, if(vendor_name = '' ,'',concat(' - ' ,vendor_name))) supplier, vendor_gstin
        FROM invoice_comparision
        WHERE job_id = '$job_id'");

        return $retObj;
    }

    public function getDetailData($job_id = '', $supplier = '', $status = '', $is_excel = false)
    {
        $job_cond = '';
        $gst_cond = '';
        $status_cond = '';
        $excel_cond = '';

        if (!$is_excel) {
            $excel_cond = 'id,job_id,';
        }

        if ($job_id != '') {
            $job_cond = "WHERE job_id = '$job_id'";
        }

        if ($supplier != '') {
            $gst_cond = "AND vendor_gstin = '$supplier'";
        }

        if ($supplier == 'all') {
            $gst_cond = "";
        }

        if ($status != '') {
            $status_cond = "AND status = '$status'";
        }

        if ($status == 'all') {
            $status_cond = "";
        }

        $retObj =  DB::select("SELECT $excel_cond
        concat(vendor_gstin, if(vendor_name = '' ,'',concat(' - ' ,vendor_name))) supplier,
        gst_request_number gst_invoice_number,
        gst_request_date gst_request_date,
        gst_request_taxable_amount gst_taxable_amount,
        ((gst_request_cgst) + (gst_request_sgst) + (gst_request_igst)) gst_tax_value,
        gst_request_total_amount gst_total_amount,
        (((purch_request_cgst) + (purch_request_sgst) + (purch_request_igst)) - ((gst_request_cgst) + (gst_request_sgst) + (gst_request_igst))) diff,
        purch_request_number purch_invoice_number,
        purch_request_date purch_request_date,
        purch_request_taxable_amount purch_taxable_amount,
        ((purch_request_cgst) + (purch_request_sgst) + (purch_request_igst)) purch_tax_value,
        purch_request_total_amount purch_total_amount,
        status
        FROM invoice_comparision
        $job_cond
        $gst_cond
        $status_cond
        ORDER BY 1 DESC");

        return $retObj;
    }

    public function getDetailSummaryData($job_id = '', $supplier = '', $status = '')
    {
        $job_cond = '';
        $gst_cond = '';
        $status_cond = '';

        if ($job_id != '') {
            $job_cond = "job_id = '$job_id'";
        }

        if ($supplier != '') {
            $gst_cond = "AND vendor_gstin = '$supplier'";
            $supplier_name = "if(group_concat(distinct vendor_name) = '' ,'',group_concat(distinct vendor_name)) supplier";
        }

        if ($supplier == 'all') {
            $gst_cond = "";
            $supplier_name = "'All Supplier' supplier";
        }

        if ($status != '') {
            $status_cond = "AND status = '$status'";
        }

        if ($status == 'all') {
            $status_cond = "";
        }

        $retObj =  DB::select("SELECT 
        job_id,
        vendor_gstin,
        $supplier_name,
        COUNT(vendor_gstin) no_of_doc,
        SUM(purch_request_total_amount) taxable_value,
        (SUM(purch_request_cgst) + SUM(purch_request_sgst) + SUM(purch_request_igst)) tax_value,
        ((SUM(purch_request_cgst) + SUM(purch_request_sgst) + SUM(purch_request_igst)) - (SUM(gst_request_cgst) + SUM(gst_request_sgst) + SUM(gst_request_igst))) tax_difference
        FROM
            invoice_comparision
        WHERE
            $job_cond
            $gst_cond
            $status_cond
        GROUP BY vendor_gstin , job_id
        limit 1");

        return $retObj;
    }

    public function updateReconStatus($list, $status)
    {
        $affected = DB::table('invoice_comparision')
            ->whereIn('id', [$list])
            ->update(['status' => $status]);

        return $affected;
    }

    public function getMissingData($list)
    {
        $retObj =  DB::select("SELECT * 
        FROM invoice_comparision
        WHERE ID IN ($list)
        AND status = 'Missing in My Data'");

        return $retObj;
    }

    public function updateInvoiceComparison($id, $update_array)
    {
        $affected = DB::table('invoice_comparision')
            ->where('id', $id)
            ->update($update_array);

        return $affected;
    }

    public function getParticularData($id)
    {
        $retObj =  DB::select("SELECT * 
        FROM invoice_comparision_detail
        WHERE gstr_2a_table_id in ( $id)");

        return $retObj;
    }

    public function checkParticularExists($job_id, $amount)
    {
        $retObj =  DB::select("SELECT * 
        FROM invoice_comparision a
        JOIN invoice_comparision_detail b on a.id  = b.gstr_2a_table_id
        WHERE a.job_id = '$job_id'
        and b.gst_item_taxable_value = '$amount'");

        return $retObj;
    }

    public function deleteJobbyID($job_id)
    {
        $deleted = DB::table('gstr2b_recon_jobs')
            ->where('id', '=', $job_id)
            ->delete();

        $deleted = DB::table('invoice_comparision')
            ->where('job_id', '=', $job_id)
            ->delete();

        return $deleted;
    }

    public function save_expense($data, $merchant_id, $vendor_id, $gstin,  $user_id)
    {
        $id = DB::table('expense')->insertGetId(
            [
                'type' => '1',
                'merchant_id' => $merchant_id,
                'vendor_id' => $vendor_id ?  $vendor_id : '1',
                'category_id' => '1',
                'department_id' =>  '1',
                'expense_no' => $data["gst_request_number"],
                'invoice_no' => $data["gst_request_number"],
                'bill_date' => date('Y-m-d', strtotime($data["gst_request_date"])),
                'due_date' =>  date('Y-m-d', strtotime($data["gst_request_date"])),
                'tds' => '0.00',
                'discount' => '0.00',
                'adjustment' => '0.00',
                'payment_status' => '',
                'payment_mode' =>  '',
                'narrative' => '',
                'base_amount' => $data["gst_request_taxable_amount"],
                'total_amount' => $data["gst_request_total_amount"],
                'cgst_amount' => $data["gst_request_cgst"],
                'sgst_amount' => $data["gst_request_sgst"],
                'igst_amount' => $data["gst_request_igst"],
                'notify' => '0',
                'source' => '1',
                'gst_number' => $gstin,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' =>  $user_id
            ]
        );
        return $id;
    }

    public function getGstApiKey()
    {

        $retObj = DB::table('config')
        ->select(DB::raw('config_value'))
        ->where('config_type', 'IRIS_GST_DATA')
        ->where('config_key', 5)
        ->first();
    return $retObj->config_value;
    }
}
