<?php

namespace App\Model;

use Carbon\Carbon;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;

class Import extends ParentModel
{

    protected $table = 'bulk_upload';
    protected $primaryKey = 'bulk_upload_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function saveBulkuploadRecord($merchant_id, $type, $parent_id, $merchant_filename, $system_filename, $status, $total_rows, $user_id)
    {
        $id = DB::table('bulk_upload')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'type' => $type,
                'merchant_filename' =>   $merchant_filename,
                'system_filename' =>  $system_filename,
                'parent_id' =>  $parent_id,
                'status' =>  $status,
                'total_rows' =>  $total_rows,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );

        return $id;
    }

    public function updateBulkuploadStatus($id, $system_filename, $status)
    {
        DB::table('bulk_upload')
            ->where('bulk_upload_id', $id)
            ->update([
                'system_filename' => $system_filename,
                'status' => $status,
            ]);
    }

    public function approveBillCodes($bulk_id)
    {
        DB::statement("INSERT INTO `csi_code`(`project_id`,`merchant_id`,`code`,`title`,`description`,`bulk_id`,`is_active`,`created_by`,`created_date`,`last_update_by`)select `project_id`,`merchant_id`,`code`,`title`,`description`,`bulk_id`,`is_active`,`created_by`,`created_date`,`last_update_by` from staging_csi_code where bulk_id=" . $bulk_id);
    }

    public function getBillCodesUploadList($merchant_id)
    {
        $retObj = DB::table('bulk_upload as b')
            ->select(DB::raw('b.*,p.project_name,c.config_value'))
            ->join('project as p', 'b.parent_id', '=', 'p.id')
            ->join('config as c', 'b.status', '=', 'c.config_key')
            ->where('b.status', '<>', 6)
            ->where('b.type', 11)
            ->where('c.config_type', 'bulk_upload_status')
            ->where('b.merchant_id', $merchant_id)
            ->get();
        return $retObj;
    }
}
