<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use App\Model\ParentModel;

class InvoiceFormat extends ParentModel
{

    protected $table = 'invoice_template';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function getSystemMetadata($template_id)
    {
        $retObj = DB::table('system_template_column_metadata')
            ->select(DB::raw('column_datatype,position,column_name,column_type as type,column_type,is_mandatory,is_delete_allow,function_id,save_table_name,column_position'))
            ->where('system_template_id', $template_id)
            ->orderBy('column_position')
            ->get();
        return $retObj;
    }

    public function getSystemTemplateDesign()
    {
        $retObj = DB::table('system_template_design')
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->orderBy('sequence', 'ASC')
            ->get();
        return $retObj;
    }
    public function getTemplateMandatoryFields()
    {
        $retObj = DB::table('invoice_template_mandatory_fields')
            ->select(DB::raw('*'))
            ->orderBy('seq', 'ASC')
            ->get();
        return $retObj;
    }
    public function getSystemTemplateDesignName($name)
    {
        $retObj = DB::table('system_template_design')
            ->select(DB::raw('title'))
            ->where('design_name', $name)
            ->first();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }


    public function getFormatMetadata($template_id)
    {
        $retObj = DB::table('invoice_column_metadata as m')
            ->select(DB::raw('m.column_id,column_datatype,position,column_name,column_type as type,customer_column_id,column_type,is_mandatory,is_delete_allow,m.function_id,save_table_name,column_position,c.param,c.value as param_value'))
            ->leftJoin('column_function_mapping as c', function ($join) {
                $join->on('m.column_id', '=', 'c.column_id')
                    ->where('c.is_active', '=', 1);
            })
            ->where('template_id', $template_id)
            ->where('m.is_active', 1)
            ->orderBy('sort_order')
            ->get();
        return $retObj;
    }

    public function getInvoiceSequence($merchant_id, $type = 1)
    {
        $retObj = DB::table('merchant_auto_invoice_number')
            ->select(DB::raw('auto_invoice_id,prefix,val,seprator'))
            ->where('merchant_id', $merchant_id)
            ->where('type', $type)
            ->where('is_active', 1)
            ->get();
        return $retObj;
    }

    public function getMerchantDefaultProfile($merchant_id)
    {
        $retObj = DB::table('merchant_billing_profile')
            ->select(DB::raw('*'))
            ->where('merchant_id', $merchant_id)
            ->where('is_default', 1)
            ->first();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function saveSequence($merchant_id, $prefix = '', $val, $user_id, $separator = '')
    {
        $separator = ($separator == null) ? '' : $separator;
        $prefix = ($prefix == null) ? '' : $prefix;
        $id = DB::table('merchant_auto_invoice_number')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'prefix' => $prefix,
                'val' => $val,
                'length' => 1,
                'type' => 1,
                'seprator' => $separator,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function existInvoicePrefix($merchant_id, $prefix='', $separator='')
    {
        $retObj = DB::table('merchant_auto_invoice_number')
            ->select(DB::raw('*'))
            ->where('merchant_id', $merchant_id)
            ->where('prefix',$prefix)
            ->where('seprator',$separator)
            ->where('is_active', 1)
            ->first();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }  
    }

    public function getInvoiceMetaDataColumnsDetails($template_id, $column_label)
    {
        $retObj = DB::table('invoice_column_metadata')
            ->select(DB::raw('column_id'))
            ->where('template_id', $template_id)
            ->where('column_name', $column_label)
            ->first();
        if (!empty($retObj)) {
            return $retObj->column_id;
        } else {
            return false;
        }
    }

    public function insertDefaultConstructionFormat($template_id, $merchant_id, $user_id)
    {

        $id = DB::table('invoice_template')->insertGetId(
            [
                'template_id' => $template_id,
                'merchant_id' => $merchant_id,
                'user_id' => $user_id,
                'template_name' => 'G702/703',
                'template_type' => 'construction',
                'particular_column' => '{"bill_code":"Bill Code","description":"Desc","bill_type":"Bill Type","cost_type":"Cost Type","original_contract_amount":"Original Contract Amount","approved_change_order_amount":"Approved Change Order Amount","current_contract_amount":"Current Contract Amount","previously_billed_percent":"Previously Billed Percent","previously_billed_amount":"Previously Billed Amount","current_billed_percent":"Current Billed Percent","current_billed_amount":"Current Billed Amount","previously_stored_materials":"Previously Stored Materials","current_stored_materials":"Current Stored Materials","stored_materials":"Materials Presently Stored","total_billed":"Total Billed (including this draw)","retainage_percent":"Retainage %","retainage_amount_previously_withheld":"Retainage Amount Previously Withheld","retainage_amount_for_this_draw":"Retainage amount for this draw","net_billed_amount":"Net Billed Amount","retainage_release_amount":"Retainage Release Amount","total_outstanding_retainage":"Total outstanding retainage","project":"Project","cost_code":"Cost Code","group":"Group","bill_code_detail":"Bill code detail"}',
                'default_particular' => 'null',
                'default_tax' => 'null',
                'particular_total' => 'Particular total',
                'tax_total' => 'Tax total',
                'plugin' => '{"has_upload":1,"upload_file_label":"View document","has_signature":1,"has_cc":"1","cc_email":[],"roundoff":"1","has_acknowledgement":"1","is_prepaid":"1","has_autocollect":"1","has_partial":"1","partial_min_amount":"50","has_covering_note":"1","default_covering_note":0,"has_custom_notification":"1","custom_email_subject":"Payment request from %COMPANY_NAME%","custom_sms":" You have received a payment request from %COMPANY_NAME% for amount %TOTAL_AMOUNT%. To make an online payment, access your bill via %SHORT_URL% ","has_custom_reminder":"1","reminders":{"3":{"email_subject":"","sms":""},"1":{"email_subject":"","sms":""},"0":{"email_subject":"","sms":""}},"has_online_payments":"1","enable_payments":"1","has_customized_payment_receipt":"1","save_revision_history":"1","receipt_fields":[{"label":"Customer code","column_id":93099,"order":1},{"label":"Patron Name","column_id":0,"order":2},{"label":"Patron Email ID","column_id":0,"order":3},{"label":"Payment Towards","column_id":0,"order":4},{"label":"Payment Ref Number","column_id":0,"order":5},{"label":"Transaction Ref Number","column_id":0,"order":6},{"label":"Payment Date & Time","column_id":0,"order":7},{"label":"Payment Amount","column_id":0,"order":8},{"label":"Mode of Payment","column_id":0,"order":9}]}',
                'profile_id' => '0',
                'hide_invoice_summary' => '0',
                'is_active' => '1',
                'invoice_title' =>  'Performa Invoice',
                'footer_note' => $user_id,
                'created_by' => $user_id,
                'created_date' => date("Y-m-d h:i:s")
            ]
        );
        return $id;
    }
}
