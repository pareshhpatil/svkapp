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

class Roster extends Model {

    public function saveRoster($date, $pickup, $route_id, $narrative, $user_id) {
        $id = DB::table('roster')->insertGetId(
                [
                    'route_id' => $route_id,
                    'pickupdrop' => $pickup,
                    'narrative' => $narrative,
                    'date' => $date,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function updateRoster($id, $date, $pickup, $route_id, $narrative, $user_id) {
        DB::table('roster')
                ->where('roster_id', $id)
                ->update([
                    'route_id' => $route_id,
                    'pickupdrop' => $pickup,
                    'narrative' => $narrative,
                    'date' => $date,
                    'last_update_by' => $user_id
        ]);
    }

    public function asignRoster($id, $driver_id, $vehicle_id, $route_id, $user_id) {
        DB::table('roster')
                ->where('roster_id', $id)
                ->update([
                    'driver_id' => $driver_id,
                    'vehicle_id' => $vehicle_id,
                    'package_route_id' => $route_id,
                    'status' => 1,
                    'last_update_by' => $user_id
        ]);
    }

    public function deleteRosterEmployee($id, $user_id) {
        DB::table('roster_employee')
                ->where('roster_id', $id)
                ->update([
                    'is_active' => 0,
                    'last_update_by' => $user_id
        ]);
    }

    public function saveRosterEmployee($roster_id, $employee_id, $time, $user_id) {
        $id = DB::table('roster_employee')->insertGetId(
                [
                    'roster_id' => $roster_id,
                    'employee_id' => $employee_id,
                    'pickup_time' => $time,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function upcomingRoster($date) {
        $retObj = DB::table('roster as m')
                ->join('route as v', 'v.route_id', '=', 'm.route_id')
                ->select(DB::raw('m.*,v.name as route_name'))
                ->where('m.is_active', 1)
                ->where('m.complete', 0)
                ->where('m.date', '<=', $date)
				->where('m.date', '>=',date('Y-m-d'))
                ->orderBy('roster_id', 'asc')
                ->get();
        return $retObj;
    }

    public function rosteremployees($date) {
        $retObj = DB::table('roster_employee as m')
                ->join('passenger as v', 'v.id', '=', 'm.employee_id')
                ->join('roster as ro', 'ro.roster_id', '=', 'm.roster_id')
                ->join('route as r', 'r.route_id', '=', 'ro.route_id')
                ->select(DB::raw('m.*,r.name as route_name,v.employee_name, v.mobile'))
                ->where('m.is_active', 1)
                ->where('ro.date', $date)
                ->get();
        return $retObj;
    }

    public function dateRoster($from_date, $to_date) {
        $retObj = DB::table('roster as m')
                ->join('route as v', 'v.route_id', '=', 'm.route_id')
                ->select(DB::raw('m.*,v.name as route_name'))
                ->where('m.is_active', 1)
                ->where('m.date', '>=', $from_date)
                ->where('m.date', '<=', $to_date)
                ->get();
        return $retObj;
    }
    
    public function getRoster($date) {
        $retObj = DB::table('roster')
                ->select(DB::raw('*'))
                ->where('is_active', 1)
                ->where('date', '=', $date)
                ->where('pickupdrop', '=', 'Pickup')
                ->get();
        return $retObj;
    }
    
    public function closeReccord($id, $user_id) {
        DB::table('roster')
                ->where('roster_id', $id)
                ->update([
                    'complete' => 1,
                    'last_update_by' => $user_id
        ]);
    }

    public function rosterEmployee($roster_id) {
        $retObj = DB::table('roster_employee as m')
                ->join('passenger as v', 'v.id', '=', 'm.employee_id')
                ->select(DB::raw('m.id,m.pickup_time,v.employee_name,v.map,v.gender,v.location,v.address,v.mobile'))
                ->where('m.is_active', 1)
                ->where('m.roster_id', $roster_id)
                ->get();
        return $retObj;
    }

    public function updateRosterSMS($job_id, $sms, $id) {
        DB::table('roster_employee')
                ->where('id', $id)
                ->update([
                    'job_id' => $job_id,
                    'sms' => $sms
        ]);
    }

    public function updateTripRating($rating, $id) {
        DB::table('roster_employee')
                ->where('id', $id)
                ->update([
                    'rating' => $rating
        ]);
    }

}
