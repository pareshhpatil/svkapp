<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Paresh
 */

use Illuminate\Support\Facades\DB;
use App\Model\ParentModel;

class Invoice extends ParentModel
{

    public function getActiveCoupon($merchant_id)
    {
        $retObj = DB::select("select coupon_id,coupon_code,descreption,type,percent,fixed_amount,start_date,end_date,`limit`,available from coupon where merchant_id='" . $merchant_id . "' and is_active=1 and DATE_FORMAT(start_date,'%Y-%m-%d') <= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and DATE_FORMAT(end_date,'%Y-%m-%d') >= DATE_FORMAT(CURDATE(),'%Y-%m-%d') and (available > 0 or `limit`=0);");
        return $retObj;
    }

    public function getInvoiceInfo($payment_request_id, $merchant_id)
    {
        $retObj = DB::select("call `getinvoice_details`('" . $merchant_id . "', '" . $payment_request_id . "');");
        return $retObj[0];
    }
    public function getStagingInvoiceInfo($payment_request_id, $merchant_id)
    {
        $retObj = DB::select("call `get_staging_invoice_details`('" . $merchant_id . "', '" . $payment_request_id . "');");
        return $retObj[0];
    }
    public function getReceipt($payment_transaction_id, $type)
    {

        $retObj = DB::select("call `getPayment_receipt`('" . $payment_transaction_id . "', '" . $type . "');");
        return $retObj[0];
    }
    public function isPaymentActive($merchant_id)
    {
        $paymentGateway = DB::table('merchant_fee_detail')->where('merchant_id', $merchant_id)->where('is_active', 1)->count();
        if ($paymentGateway == 0) {
            return false;
        } else {
            return true;
        }
    }
    public function getInvoiceMetadata($template_id, $payment_request_id, $staging = 0)
    {
        if ($staging == 1) {
            $table_name = "staging_invoice_values";
        } else {
            $table_name = "invoice_column_values";
        }
        $retObj = DB::table('invoice_column_metadata as m')
            ->select(DB::raw('v.invoice_id as id,m.column_id,column_datatype,position,column_name,column_type as type,customer_column_id,column_type,is_mandatory,is_delete_allow,m.function_id,save_table_name,column_position,c.param,c.value as param_value,v.value'))
            ->leftJoin('column_function_mapping as c', function ($join) {
                $join->on('m.column_id', '=', 'c.column_id')
                    ->where('c.is_active', '=', 1);
            })
            ->leftJoin($table_name . ' as v', function ($join) use ($payment_request_id) {
                $join->on('m.column_id', '=', 'v.column_id')
                    ->where('v.payment_request_id', '=', $payment_request_id)
                    ->where('v.is_active', '=', 1);
            })
            ->where('m.template_id', $template_id)
            ->where('m.is_active', 1)
            ->orderBy('sort_order')
            ->get();
        return $retObj;
    }

    public function getIrisInvoiceDetail($merchant_id, $gst_number, $from_date, $to_date, $type)
    {
        if ($type == 'RI') {
            $date_col = 'idt';
        } else {
            $date_col = 'ntDt';
        }
        $retObj = DB::table('iris_invoice_detail as d')
            ->select(DB::raw('*'))
            ->join('iris_invoice as i', 'd.invoice_id', '=', 'i.id')
            ->where('i.is_active', 1)
            ->where('i.merchant_id', $merchant_id)
            ->where('i.dty', $type)
            ->whereDate($date_col, '>=', $from_date)
            ->whereDate($date_col, '<=', $to_date);
        if ($gst_number != '') {
            $retObj->where('i.gstin', $gst_number);
        }
        $retObj->orderBy('invoice_id');
        $retObj->orderBy('d.id');
        $data = $retObj->get();
        return $data;
    }

    public function getContract($merchant_id)
    {
        $retObj = DB::table('contract as c')
            ->select(DB::raw('*'))
            ->join('project as p', 'p.id', '=', 'c.project_id')
            ->where('c.is_active', 1)
            ->where('c.status', 1)
            ->where('c.merchant_id', $merchant_id)
            ->get();
        return $retObj;
    }

    public function getContractDetail($id)
    {
        $retObj = DB::table('contract as c')
            ->select(DB::raw('*'))
            ->join('project as p', 'p.id', '=', 'c.project_id')
            ->where('c.contract_id', $id)
            ->first();
        return $retObj;
    }

    public function getPreviousContractBill($merchant_id, $contract_id)
    {
        $retObj = DB::table('payment_request')
            ->select(DB::raw('payment_request_id'))
            ->where('merchant_id', $merchant_id)
            ->where('contract_id', $contract_id)
            ->orderBy('payment_request_id', 'desc')
            ->first();
        if (!empty($retObj)) {
            return $retObj->payment_request_id;
        } else {
            return false;
        }
    }

    public function getOrderbyContract($contract_id, $date)
    {
        $retObj = DB::table('order')
            ->select(DB::raw('*'))
            ->where('contract_id', $contract_id)
            ->where('status',1)
            ->where('is_active',1)            
            ->whereDate('approved_date', '<=', $date)
            ->orderBy('order_id', 'desc')
            ->get();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function invoiceTax($payment_request_id, $staging = 0)
    {
        $table = ($staging == 1) ? 'staging_invoice_tax' : 'invoice_tax';
        $retObj = DB::table($table . ' as i')
            ->select(DB::raw('i.*,t.tax_type,t.tax_calculated_on'))
            ->join('merchant_tax as t', 't.tax_id', '=', 'i.tax_id')
            ->where('i.is_active', 1)
            ->where('i.payment_request_id', $payment_request_id)
            ->get();
        return $retObj;
    }
    public function getmerchantfeeID($merchant_id)
    {


        $retObj = DB::table('merchant_fee_detail')
            ->select(DB::raw('fee_detail_id'))
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->orderBy('pg_fee_val', 'desc')
            ->first();
        if (!empty($retObj)) {
            return $retObj->fee_detail_id;
        } else {
            return false;
        }
    }
    public function getProjectDeatils($payment_request_id)
    {
        $retObj = DB::table('payment_request as p')
            ->select(DB::raw('c.contract_code,c.project_id,c.contract_date,c.bill_date,pro.project_name,pro.start_date,pro.end_date'))
            ->join('contract as c', 'p.contract_id', '=', 'c.contract_id')
            ->join('project as pro', 'c.project_id', '=', 'pro.id')
            ->where('p.is_active', 1)
            ->where('p.payment_request_id', $payment_request_id)
            ->first();
        return $retObj;
    }

    public function getSwipezInvoice($merchant_id, $from_date, $to_date)
    {
        $retObj = DB::table('payment_request as p')
            ->select(DB::raw('p.*,c.first_name,c.last_name,mobile,state,c.gst_number,address'))
            ->join('customer as c', 'p.customer_id', '=', 'c.customer_id')
            ->where('p.is_active', 1)
            ->where('p.merchant_id', $merchant_id)
            ->whereDate('bill_date', '>=', $from_date)
            ->whereDate('bill_date', '<=', $to_date)
            ->get();
        return $retObj;
    }

    public function getMerchantFormatList($merchant_id, $type)
    {
        $templateArray = ['event'];
        if ($type == 'subscription') {
            $templateArray = ['travel', 'event'];
        }

        $retObj = DB::table('invoice_template')
            ->select(DB::raw('*'))
            ->where('merchant_id', $merchant_id)
            ->whereNotIn('template_type', $templateArray)
            ->where('is_active', 1)
            ->orderBy('template_id', 'DESC')
            ->get();
        return $retObj;
    }

    public function defaultBillingProfile($merchant_id)
    {
        $retObj = DB::table('merchant_billing_profile')
            ->select(DB::raw('*'))
            ->where('merchant_id', $merchant_id)
            ->where('is_default', 1)
            ->where('is_active', 1)
            ->first();
        return $retObj;
    }

    public function getInvoiceParticular($payment_request_id)
    {
        $retObj = DB::table('invoice_particular')
            ->select(DB::raw('*'))
            ->where('payment_request_id', $payment_request_id)
            ->where('is_active', 1)
            ->get();
        return $retObj;
    }


    public function getTravelInvoiceParticular($payment_request_id)
    {
        $retObj = DB::table('invoice_travel_particular')
            ->select(DB::raw('*'))
            ->where('payment_request_id', $payment_request_id)
            ->where('type', '<>', 2)
            ->where('is_active', 1)
            ->get();
        return $retObj;
    }
    public function getCustomerbreckup($customer_id, $staging = 0)
    {

        if ($staging == 1) {
            $table = 'staging_customer_column_values';
        } else {
            $table = 'customer_column_values';
        }



        $retObj = DB::table($table)
            ->select(DB::raw('column_id,value'))
            ->where('customer_id', $customer_id)

            ->get();

        $retObj = json_decode($retObj, 1);
        $values = [];
        foreach ($retObj as $row) {

            $values[$row['column_id']] = $row['value'];
        }
        return $values;
    }

    public function getInvoiceTax($payment_request_id)
    {
        $retObj = DB::table('invoice_tax as i')
            ->select(DB::raw('i.*,t.tax_name,t.tax_type'))
            ->join('merchant_tax as t', 't.tax_id', '=', 'i.tax_id')
            ->where('payment_request_id', $payment_request_id)
            ->where('i.is_active', 1)
            ->get();
        return $retObj;
    }

    public function getGstState()
    {
        $retObj = DB::table('config')
            ->select(DB::raw('config_key,config_value'))
            ->where('config_type', 'gst_state_code')
            ->get();
        return $retObj;
    }

    public function getGstStateCode($state)
    {
        $retObj = DB::table('config')
            ->select(DB::raw('config_key'))
            ->where('config_type', 'gst_state_code')
            ->where('config_value', $state)
            ->first();
        if (!empty($retObj)) {
            return $retObj->config_key;
        } else {
            return false;
        }
    }

    public function updateTallyExportRequest($id, $status, $file_name)
    {
        DB::table('tally_export_request')
            ->where('id', $id)
            ->update([
                'system_filename' => $file_name,
                'status' => $status
            ]);
    }

    public function getDefaultTaxList($merchant_id, $default_tax_ids)
    {
        $retObj = DB::table('merchant_tax')
            ->select(DB::raw('*'))
            ->where('merchant_id', $merchant_id)
            ->whereIn('tax_id', $default_tax_ids)
            ->get();
        return $retObj;
    }

    public function updateAutoInvoiceRequestStatus($auto_invoice_request_id, $invoice_id = null, $status)
    {
        DB::table('auto_invoice_api_request')->where('id', $auto_invoice_request_id)
            ->update([
                'status' => $status,
                'payment_request_id' => $invoice_id
            ]);
    }



    public function saveEinvoiceRequest($info,  $source, $notify)
    {
        $date = date_create($info['bill_date']);
        $bill_date = date_format($date, "Y-m-d");
        $id = DB::table('einvoice_request')->insertGetId(
            [
                'merchant_id' => $info['merchant_id'],
                'payment_request_id' => $info['payment_request_id'],
                'invoice_number' => $info['invoice_number'],
                'invoice_date' => $bill_date,
                'client_name' => $info['customer_name'],
                'client_gst' => $info['customer_gst_number'],
                'merchant_gst' => $info['gst_number'],
                'source' => $source,
                'notify' => $notify,
                'status' => 0,
                'created_by' => $info['merchant_id'],
                'last_update_by' => $info['merchant_id'],
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateEinvoice($request_id, $payload, $status, $error, $irn, $qr_code, $ack_no, $ack_date)
    {
        DB::table('einvoice_request')->where('id', $request_id)
            ->update([
                'status' => $status,
                'request_json' => $payload,
                'ack_no' => $ack_no,
                'ack_date' => $ack_date,
                'irn' => $irn,
                'qr_code' => $qr_code,
                'error' => $error
            ]);
    }

    public function GetEinvoiceList($merchant_id, $from_date, $to_date)
    {

        $retObj = DB::table('einvoice_request')
            ->select(DB::raw('*'))
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->whereDate('created_date', '>=', $from_date)
            ->whereDate('created_date', '<=', $to_date);
        $data = $retObj->get();
        return $data;
    }

    public function getAllParentProducts($merchant_id = null)
    {
        $getAllproducts =  DB::select("SELECT 
        * FROM merchant_product WHERE
            merchant_id = '$merchant_id' and
            is_active = 1 and (type = 'Service' or goods_type = 'simple' or
            (goods_type = 'variable' and  parent_id != 0))
        ");
        return $getAllproducts;
    }


    public function getInvoiceSupplierlist($supplier_ids)
    {

        $retObj = DB::table('supplier')
            ->select(DB::raw('supplier_id,supplier_company_name,config_value,contact_person_name,mobile1,mobile2,email_id1,email_id2'))
            ->join('config', 'config.config_key', '=', 'supplier.industry_type')
            ->whereIn("supplier.supplier_id", $supplier_ids)
            ->where("config.config_type", '=', 'industry_type')
            ->orderby("supplier.supplier_id", 'desc')

            ->get();

        return $retObj;
    }
    public function getCouponDetails($coupon_id)
    {
        $retObj = DB::table('coupon')
            ->select(DB::raw('coupon_id,coupon_code,descreption,type,percent,fixed_amount,start_date,end_date,`limit`,available'))

            ->where("coupon_id", $coupon_id)
            ->where("is_active", 1)
            ->whereDate("start_date", '<=', date('Y-m-d'))
            ->whereDate("end_date", '>=', date('Y-m-d'))
            ->where("available", '>', '0')
            ->first();

        return $retObj;
    }

    public function getSingleCoveringNoteDetails($covering_id)
    {
        $retObj =  DB::select("SELECT covering_id, merchant_id ,template_name,body,subject,invoice_label,pdf_enable     FROM covering_note
        where covering_id = '$covering_id'
        and is_active ='1'");

        return $retObj;
    }
    public function getCoveringNoteDetails($payment_req_id)
    {


        $retObj = DB::table('invoice_covering_note')
            ->select(DB::raw('*'))
            ->where('payment_request_id', $payment_req_id)
            ->where('is_active', 1)
            ->first();

        return $retObj;
    }

    public function getRevisionList($payment_request_id, $id)
    {
        $retObj = DB::table('invoice_revision')
            ->select(DB::raw('*'))
            ->where('payment_request_id', $payment_request_id)
            ->where('is_active', 1)
            ->where('id', '>=', $id)
            ->orderby("id", 'desc')
            ->get();
        return $retObj;
    }

    public function validateUpdateConstructionInvoice($contract_id, $merchant_id)
    {
        $retObj = DB::table('payment_request')
            ->where('contract_id', $contract_id)
            ->where('merchant_id', $merchant_id)
            ->max('payment_request_id');
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }
}