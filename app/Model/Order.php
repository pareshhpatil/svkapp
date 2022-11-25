<?php

namespace App\Model;

/**
 *
 * @author Paresh
 */

use Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class Order extends ParentModel
{

    public function saveNewOrder($data, $merchant_id, $user_id)
    {
        
        $id = DB::table('order')->insertGetId(
            [
                'order_no' => $data->order_no,
                'order_desc' => $data->order_desc,
                'merchant_id' => $merchant_id,
                'contract_id' => $data->contract_id,
                'total_original_contract_amount' => $data->total_original_contract_amount,
                'total_change_order_amount' => $data->total_change_order_amount,
                'order_date' => $data->order_date,
                'status' => $data->status,
                'particulars' => $data->particulars,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }


    public function updateOrder($data, $merchant_id, $user_id, $id)
    {
        DB::table('order')
            ->where('order_id', $id)
            ->update([
                'order_no' => $data->order_no,
                'order_desc' => $data->order_desc,
                'merchant_id' => $merchant_id,
                'contract_id' => $data->contract_id,
                'total_original_contract_amount' => $data->total_original_contract_amount,
                'total_change_order_amount' => $data->total_change_order_amount,
                'order_date' => $data->order_date,
                'status' => $data->status,
                'particulars' => $data->particulars,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]);
    }

    public function getOrderList($merchant_id, $from_date, $to_data, $contract)
    {
        $project_cond = "";

        if ($contract != '') {
            $project_cond = "AND a.contract_id = '$contract'";
        }
        
        $retObj =  DB::select("SELECT a.*,c.company_name, a.invoice_status, p.project_name, p.project_id  project_code,b.contract_code, a.order_no, concat(first_name,' ', last_name) name , c.customer_id, c.customer_code
        FROM `order` a
        join contract b on a.contract_id  = b.contract_id
        join project p on b.project_id  = p.id
        join customer c on b.customer_id  = c.customer_id
        WHERE a.merchant_id = '$merchant_id' 
        $project_cond
        AND DATE(a.created_date) between DATE('$from_date') AND DATE('$to_data')
        AND a.is_active ='1'
        ORDER BY a.created_date desc");

        return $retObj;
    }

    public function changeOrderApproveStatus($order_id, $date, $status, $unapprove_message)
    {
        DB::table('order')
            ->where('order_id', $order_id)
            ->update([
                'status' => $status,
                'approved_date' => $date,
                'unapprove_message' => $unapprove_message
            ]);
    }

    
    public function getCostTypeList($merchant_id)
    {
        $retObj = DB::table('cost_types')
            ->select(DB::raw('*'))
            ->where('created_by', $merchant_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }
}
