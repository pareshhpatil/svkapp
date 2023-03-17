<?php

namespace App\Model;

use Carbon\Carbon;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;
use App\Model\CostType;

class Master extends ParentModel
{

    public function saveFoodDeliveryPartner($data, $merchant_id, $user_id)
    {

        $id = DB::table('food_delivery_partner_comission')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'name' => $data->name,
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
                'commission' => $data->commission,
                'gst' => $data->gst,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateFoodDeliveryPartner($data, $user_id)
    {

        DB::table('food_delivery_partner_comission')
            ->where('id', $data->id)
            ->update([
                'name' => $data->name,
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
                'commission' => $data->commission,
                'gst' => $data->gst,
                'last_update_by' => $user_id
            ]);
    }


    public function saveBilledTransaction($data, $user_id)
    {
        $data['last_update_by'] = $user_id;
        if ($data['id'] > 0) {
            DB::table('billed_transaction')
                ->where('id', $data['id'])
                ->update($data);
        } else {
            unset($data['id']);
            $data['created_by'] = $user_id;
            $data['created_date'] = date('Y-m-d H:i:s');
            DB::table('billed_transaction')->insertGetId(
                $data
            );
        }

    }

    public function getProjectList($merchant_id)
    {

        $retObj =  DB::select("SELECT a.*, ifnull(b.company_name, concat(b.first_name,' ' ,  b.last_name)) company_name
                            FROM project a
                            join customer b on a.customer_id = b.customer_id
                            WHERE a.merchant_id = '$merchant_id' 
                            and a.is_active ='1'
                            ORDER by 1 DESC");

        return $retObj;
    }


    public function getCustomerList($merchant_id, $column_name, $bulk_id, $where = '')
    {
        $retObj = DB::select("call `get_customer_list`('$merchant_id','$column_name','$where','','','$bulk_id')");
        return $retObj;
    }

    public function saveNewProject($data, $merchant_id, $user_id)
    {

        $id = DB::table('project')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'project_id' => $data->project_id,
                'project_name' => $data->project_name,
                'customer_id' => $data->customer_id,
                'end_date' => $data->end_date,
                'start_date' => $data->start_date,
                'sequence_number' => $data->sequence_number,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateProject($data, $user_id)
    {
        DB::table('project')
            ->where('id', $data->id)
            ->update([
                'project_name' => $data->project_name,
                'customer_id' => $data->customer_id,
                'end_date' => $data->end_date,
                'start_date' => $data->start_date,
                'sequence_number' => $data->sequence_number,
                'last_update_by' => $user_id
            ]);
    }

    public function updateProjectSequence($merchant_id, $id, $val, $separator='', $prefix='')
    {
        DB::table('merchant_auto_invoice_number')
            ->where('auto_invoice_id', $id)
            ->update([
                'val' => $val,
                'seprator' => $separator,
                'prefix' => $prefix
            ]);
    }


    public function getProjectCodeList($merchant_id, $project_id, $table = 'csi_code')
    {
        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->where('merchant_id', $merchant_id)
            ->where('project_id', $project_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }


    public function getProjectBillTransactionList($merchant_id, $project_id)
    {
        $retObj = DB::table('billed_transaction as b')
            ->select(DB::raw('b.*,concat(c.abbrevation," - ",c.name) as cost_type_label'))
            ->join('cost_types as c', 'b.cost_type', '=', 'c.id')
            ->where('b.is_active', 1)
            ->where('b.merchant_id', $merchant_id)
            ->where('b.project_id', $project_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }
  

    public function getCostTypes($merchant_id): array
    {
        return CostType::where('merchant_id', $merchant_id)->where('is_active', 1)
                ->select(['id as value', DB::raw('CONCAT(abbrevation, " - ", name) as label') ])
                ->get()->toArray();
    }

    public function saveCustomInvoiceStatus($merchant_id,$user_id,$key,$value){
        $id = DB::table('merchant_config_data')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'user_id'=> $user_id,
                'key'=>$key,
                'value'=>$value,
                'is_active'=>1,
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateMerchantConfigData($merchant_id,$key,$value) {
        DB::table('merchant_config_data')
            ->where('merchant_id', $merchant_id)
            ->where('key',$key)
            ->where('is_active',1)
            ->update([
                'value' => $value,
                'last_update_date' => date('Y-m-d H:i:s')
            ]);
    }
}
