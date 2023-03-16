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

class Contract extends ParentModel
{

    public function saveNewContract($data, $merchant_id, $user_id)
    {
        $id = DB::table('contract')->insertGetId(
            [
                'contract_code' => $data->contract_no,
                'merchant_id' => $merchant_id,
                'customer_id' => $data->customer_code,
                'project_id' => $data->project_id,
                'contract_amount' => $data->contract_amount,
                'contract_date' => $data->contract_date,
                'bill_date' => $data->bill_date,
                'billing_frequency' => $data->billing_frequency,
                'particulars' => $data->particulars,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function saveMaster($table, $name, $merchant_id, $user_id)
    {

        $id = DB::table($table)->insertGetId(
            [
                'name' => $name,
                'merchant_id' => $merchant_id,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateContract($data, $merchant_id, $user_id, $id)
    {
        DB::table('contract')
            ->where('contract_id', $id)
            ->update([
                'contract_code' => $data->contract_no,
                'merchant_id' => $merchant_id,
                'customer_id' => $data->customer_code,
                'project_id' => $data->project_id,
                'contract_amount' => $data->totalcost,
                'contract_date' => $data->contract_date,
                'bill_date' => $data->bill_date,
                'billing_frequency' => $data->billing_frequency,
                'particulars' => $data->particulars,
                'created_by' => $user_id,
                'last_update_by' => $user_id
            ]);
    }
    //DBTodo - remove raw query 
    public function getContractList($merchant_id, $from_date, $to_data, $project, $start='',$limit='')
    {
        $project_cond = "";

        if ($project != '') {
            $project_cond = "AND a.project_id = '$project'";
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

        $retObj =  DB::select("SELECT a.contract_id,a.contract_code,a.customer_id,a.project_id,a.contract_amount,a.contract_date,a.bill_date,a.billing_frequency,a.created_date,a.last_update_date,c.company_name,b.project_name, b.project_id  project_code,c.customer_code,  concat(first_name,' ', last_name) name
        FROM contract a
        join project b on a.project_id  = b.id
        join customer c on a.customer_id  = c.customer_id
        WHERE a.merchant_id = '$merchant_id' 
        $project_cond
        AND DATE(a.created_date) between DATE('$from_date') AND DATE('$to_data')
        AND a.is_active ='1'
        AND a.status ='1'
        ORDER BY a.created_date desc $limit $start");
        
        return $retObj;
    }

    public function getAllProjectDetails($project_id)
    {
        $retObj =  DB::select("SELECT id, project_id,  project_name, a.customer_id, b.customer_code, b.company_name,a.sequence_number,
        concat(b.customer_code, ' | ' , b.company_name) customer_company_code, email, mobile, concat(first_name,' ', last_name) name
        FROM project a
        JOIN customer b on a.customer_id  = b.customer_id
        where id = '$project_id'
        and a.is_active ='1'");

        return $retObj;
    }

    public function saveNewBillCode($data, $merchant_id, $user_id)
    {
        $id = DB::table('csi_code')->insertGetId(
            [
                'code' => $data->bill_code,
                'title' => $data->bill_description,
                'description' => $data->bill_description,
                'merchant_id' => $merchant_id,
                'project_id' => $data->project_id,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateBillcode($data, $merchant_id, $user_id)
    {
        DB::table('csi_code')
            ->where('id', $data->bill_id)
            ->update([
                'code' => $data->bill_code,
                'title' => $data->bill_description,
                'description' => $data->bill_description,
                'last_update_by' => $user_id
            ]);
    }

    public function getContractData($contract_id) {
        $retObj = DB::table('contract as c')
        ->select(DB::raw("c.contract_id,c.contract_code,c.project_id,p.project_id,p.project_name,c.contract_amount,c.contract_date,c.bill_date,c.billing_frequency,c.project_address,c.owner_address,c.contractor_address,c.architect_address,u.customer_id,u.customer_code,concat(first_name,' ', last_name) name,c.status,c.particulars"))
        ->join('project as p','c.project_id','=','p.id')
        ->join('customer as u','c.customer_id','=','u.customer_id')
        ->where('c.contract_id',$contract_id)
        ->first();

        return $retObj;
    }
}
