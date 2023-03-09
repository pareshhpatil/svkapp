<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;


class Project extends ParentModel
{
    use HasFactory;
    //protected $table = 'project';
    
    public function getProjectList($merchant_id, $start, $limit)
    {
        $retObj = DB::table('project as a')
            ->select(DB::raw('a.*,ifnull(b.company_name, concat(b.first_name," " ,  b.last_name)) company_name'))
            ->join('customer as b', 'a.customer_id', '=', 'b.customer_id')
            ->where('a.is_active', 1)
            ->where('a.merchant_id', $merchant_id)
            ->orderBy('a.id', 'DESC');
       
        $retObj =  $retObj->offset($start)
            ->limit($limit)
            ->get();
        return $retObj;
    }

    public function getBillCodesList($merchant_id, $project_id, $start, $limit) {
        $retObj = DB::table('csi_code')
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->where('merchant_id', $merchant_id)
            ->where('project_id', $project_id);

        $retObj =  $retObj->offset($start)
            ->limit($limit)
            ->get();
        return $retObj;
    }

    public function updateBillcode($data, $user_id)
    {
        DB::table('csi_code')
            ->where('id', $data->bill_code_id)
            ->update([
                'code' => $data->bill_code,
                'title' => $data->bill_description,
                'description' => $data->bill_description,
                'last_update_by' => $user_id,
                'last_update_date' => date('Y-m-d H:i:s')
            ]);
    }
    

    // public function createProject($data,$merchant_id,$user_id) {
    //     return DB::table('project')->insertGetId(
    //         [
    //             'merchant_id' => $merchant_id,
    //             'project_id' => $data->project_code,
    //             'project_name' => $data->project_name,
    //             'customer_id' => $data->customer_id,
    //             'end_date' => $data->end_date,
    //             'start_date' => $data->start_date,
    //             'sequence_number' => $data->sequence_number,
    //             'created_by' => $user_id,
    //             'last_update_by' => $user_id,
    //             'created_date' => date('Y-m-d H:i:s')
    //         ]
    //     );
    // }
}
