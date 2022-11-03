<?php

namespace App\Model;

use Carbon\Carbon;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;

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

    public function getProjectList($merchant_id)
    {

        $retObj =  DB::select("SELECT *
        FROM project
        WHERE merchant_id = '$merchant_id' 
        and is_active ='1'
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
                'sequence_number' => $data->sequence_id,
                'last_update_by' => $user_id
            ]);
    }

    public function updateProjectSequence($merchant_id, $id, $val)
    {
        DB::table('merchant_auto_invoice_number')
            ->where('auto_invoice_id', $id)
            ->update([
                'val' => $val
            ]);
    }
}
