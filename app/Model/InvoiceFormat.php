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
            ->orderBy('sequence','ASC')
            ->get();
        return $retObj;
    }
    public function getTemplateMandatoryFields()
    {
        $retObj = DB::table('invoice_template_mandatory_fields')
            ->select(DB::raw('*'))
            ->orderBy('seq','ASC')
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
            ->select(DB::raw('auto_invoice_id,prefix,val'))
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

    public function saveSequence($merchant_id, $prefix, $val, $user_id)
    {
        $id = DB::table('merchant_auto_invoice_number')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'prefix' => $prefix,
                'val' => $val,
                'length' => 1,
                'type' => 1,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function getInvoiceMetaDataColumnsDetails($template_id,$column_label) {
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
}
