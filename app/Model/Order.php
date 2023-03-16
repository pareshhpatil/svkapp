<?php

namespace App\Model;

/**
 *
 * @author Paresh
 */

use Illuminate\Support\Facades\Session;
use Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;
use App\Constants\Models\ITable;

class Order extends ParentModel
{
    protected $table = ITable::ORDER;

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

    public function getPrivilegesOrderList($merchant_id, $from_date, $to_data, $contract, $privilegesIDs = [])
    {
        $userRole = Session::get('user_role');
        $project_cond = "";
        $order_cond = "WHERE a.order_id in('')";

        if ($contract != '') {
            $project_cond = "AND a.contract_id = '$contract'";
        }

        $ids = implode(",", $privilegesIDs);

        if($userRole != 'Admin' && !in_array('all', $privilegesIDs)) {
            if(!empty($ids)) {
                $order_cond = "WHERE a.order_id in($ids)";
            }
        }

        if($userRole != 'Admin' && !in_array('all', $privilegesIDs)) {
            $retObj =  DB::select("SELECT a.*,c.company_name, a.invoice_status, p.project_name, p.project_id  project_code,b.contract_code, a.order_no, concat(first_name,' ', last_name) name , c.customer_id, c.customer_code
        FROM `order` a
        join contract b on a.contract_id  = b.contract_id
        join project p on b.project_id  = p.id
        join customer c on b.customer_id  = c.customer_id
        $order_cond
        AND a.merchant_id = '$merchant_id' 
        $project_cond
        AND DATE(a.created_date) between DATE('$from_date') AND DATE('$to_data')
        AND a.is_active ='1'
        ORDER BY a.created_date desc");
        } else {
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
        }

        return $retObj;
    }

    public function getOrderList($merchant_id, $from_date, $to_data, $contract,$start='',$limit='')
    {
        $project_cond = "";

        if ($contract != '') {
            $project_cond = "AND a.contract_id = '$contract'";
        }

        if($limit!='') {
            $limit = "limit ".$limit;
        }
        if($start!='') {
            if($start==-1) {
                $start = "offset 0";
            }else{
                $start = "offset ".$start;
            }
        }

        $retObj =  DB::select("SELECT a.order_id,a.order_no,a.order_desc,a.contract_id,a.total_original_contract_amount,a.total_change_order_amount,a.order_date,a.created_date,a.last_update_date,a.status,a.approved_date,c.company_name, a.invoice_status, p.project_name, p.project_id  project_code,b.contract_code, a.order_no, concat(first_name,' ', last_name) name , c.customer_id, c.customer_code
        FROM `order` a
        join contract b on a.contract_id  = b.contract_id
        join project p on b.project_id  = p.id
        join customer c on b.customer_id  = c.customer_id
        WHERE a.merchant_id = '$merchant_id' 
        $project_cond
        AND DATE(a.created_date) between DATE('$from_date') AND DATE('$to_data')
        AND a.is_active ='1'
        ORDER BY a.created_date desc $limit $start");

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
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }

    public function getOrderData($order_id) {
       
        $retObj = DB::table('order as o')
            ->select(DB::raw("o.*,c.contract_code,p.project_id,p.project_name,u.customer_id,u.customer_code,concat(first_name,' ', last_name) name"))
            ->join('contract as c', 'o.contract_id', '=', 'c.contract_id')
            ->join('project as p','c.project_id','=','p.id')
            ->join('customer as u','c.customer_id','=','u.customer_id')
            ->where('o.order_id',$order_id)
            //->whereRaw("d.payment_request_id='" . $payment_request_id . "' or (d.project_id='" . $project_id . "' and d.date<='" . $date . "' and d.status=0 and d.is_active=1)")
            ->first();

        return $retObj;
    }
}
