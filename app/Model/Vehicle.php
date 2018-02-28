<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Paresh
 */
use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vehicle extends Model {

    public function getReplaceList($admin_id) {
        $retObj = DB::table('replace_cab as a')
                ->join('vehicle as v', 'v.vehicle_id', '=', 'a.replace_vehicle_id')
                ->where('a.admin_id', $admin_id)
                ->select(DB::raw('a.id,v.name as vehicle_name,a.date,a.is_paid,a.amount,a.owner_name,a.vehicle_number,in_time,out_time'))
                ->get();
        return $retObj;
    }

    public function saveReplaceCab($vehicle_id, $owner_name, $number, $in_time, $out_time, $amount, $date, $remark, $is_paid, $user_id, $admin_id) {
        $id = DB::table('replace_cab')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'replace_vehicle_id' => $vehicle_id,
                    'owner_name' => $owner_name,
                    'vehicle_number' => $number,
                    'in_time' => $in_time,
                    'out_time' => $out_time,
                    'amount' => $amount,
                    'date' => $date,
                    'is_paid' => $is_paid,
                    'note' => $remark,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

}
