<?php

namespace App\Models;

use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;


class RosterModel extends ParentModel
{

    public function getRoster($table, $project_id = 0, $bulk_id = 0, $from_date = null, $to_date = null, $project_ids = [], $type = '', $shift = '', $status = null)
    {
        $retObj = DB::table($table . ' as r')
            ->join('passenger as p', 'p.id', '=', 'r.passenger_id')
            ->where('r.is_active', 1)
            ->select(DB::raw('r.status,r.id,employee_name,mobile,gender,photo,location,address,r.type,DATE_FORMAT(r.date,"%d %M") as date,shift,TIME_FORMAT(r.start_time, "%h %i %p") as display_start_time,TIME_FORMAT(r.start_time, "%H:%i") as start_time'));
        if ($project_id > 0) {
            $retObj->where('r.project_id', $project_id);
        }
        if ($bulk_id > 0) {
            $retObj->where('r.bulk_id', $bulk_id);
        } else {
            if ($from_date != null) {
                $retObj->where('r.start_time', '>', $from_date);
            }
            if ($to_date != null) {
                $retObj->where('r.start_time', '<', $to_date);
            }
            if ($status != null) {
                $retObj->where('r.status',  $status);
            }
            if ($shift != '') {
                $retObj->where('r.shift',  $shift);
            }
            if ($type != '') {
                $retObj->where('r.type',  $type);
            }
            if (!empty($project_ids)) {
                $retObj->whereIn('r.project_id', $project_ids);
            }
        }

        return $retObj->get();
    }
}
