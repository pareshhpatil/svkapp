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
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]);
    }

    public function getContractList($merchant_id, $from_date, $to_data, $project, $privilegesIDs = [])
    {
        $project_cond = "";

        if ($project != '') {
            $project_cond = "AND a.project_id = '$project'";
        }

        $ids = implode(",", $privilegesIDs);

        if(!empty($ids) && !in_array('all', $privilegesIDs)) {
            $retObj =  DB::select("SELECT a.*,c.company_name,b.project_name, b.project_id  project_code,c.customer_code,  concat(first_name,' ', last_name) name
        FROM contract a
        join project b on a.project_id  = b.id
        join customer c on a.customer_id  = c.customer_id
        WHERE a.contract_id in($ids)
        AND a.merchant_id = '$merchant_id' 
        $project_cond
        AND DATE(a.created_date) between DATE('$from_date') AND DATE('$to_data')
        AND a.is_active ='1'
        AND a.status ='1'
        ORDER BY a.created_date desc");
        } else {
            $retObj =  DB::select("SELECT a.*,c.company_name,b.project_name, b.project_id  project_code,c.customer_code,  concat(first_name,' ', last_name) name
        FROM contract a
        join project b on a.project_id  = b.id
        join customer c on a.customer_id  = c.customer_id
        WHERE a.merchant_id = '$merchant_id' 
        $project_cond
        AND DATE(a.created_date) between DATE('$from_date') AND DATE('$to_data')
        AND a.is_active ='1'
        AND a.status ='1'
        ORDER BY a.created_date desc");
        }


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
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]);
    }
}
