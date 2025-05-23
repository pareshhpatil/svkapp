<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payout
 *
 * @author Paresh
 */

use Log;
use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;
use Exception;

class RideModel extends ParentModel
{

    public function passengerUpcomingRides($id, $single = 0)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->leftJoin('driver as d', 'd.id', '=', 'r.driver_id')
            ->leftJoin('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('p.is_active', 1)
            ->where('r.is_active', 1)
            ->whereIn('r.status', [0, 1])
            ->where('p.status', 0)
            ->whereDate('r.date', '>=', date('Y-m-d'))
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time ,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo,null as actual_pickup_location,null as actual_drop_location,null as cab_reach_location'));
        if ($single == 1) {
            $array = $retObj->first();
        } else {
            $array = $retObj->get();
        }
        return json_decode(json_encode($array), 1);
    }

    public function passengerLiveRide($id, $single = 1)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->leftJoin('driver as d', 'd.id', '=', 'r.driver_id')
            ->leftJoin('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('p.is_active', 1)
            ->where('r.is_active', 1)
            ->where('p.status', '<', 2)
            ->where('r.status', 2)
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo,null as actual_pickup_location,null as actual_drop_location,null as cab_reach_location'));
        if ($single == 1) {
            $array = $retObj->first();
        } else {
            $array = $retObj->get();
        }
        return json_decode(json_encode($array), 1);
    }

    public function passengerLastRide($id)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('p.is_active', 1)
            ->where('r.is_active', 1)
            ->where('p.status',  2)
            ->where('p.passenger_id', $id)
            ->orderBy('p.id', 'DESC')
            ->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo,null as actual_pickup_location,null as actual_drop_location,null as cab_reach_location,p.rating'));
        $array = $retObj->first();
        return json_decode(json_encode($array), 1);
    }


    public function driverLiveRide($id, $single = 1, $status = [2, 6, 7])
    {
        $retObj = DB::table('ride as r')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('r.is_active', 1)
            ->whereIn('r.status', $status);
        if ($id > 0) {
            $retObj->where('r.driver_id', $id);
        }
        $retObj->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(start_time, "%l:%i %p") as only_time,d.name as driver_name, r.id as pid , start_location as pickup_location ,end_location as drop_location,d.photo'));
        if ($single == 1) {
            $array = $retObj->first();
        } else {
            $array = $retObj->get();
        }
        return json_decode(json_encode($array), 1);
    }

    public function driverUpcomingRides($id, $single = 0, $status = [1, 2])
    {
        $retObj = DB::table('ride as r')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('r.is_active', 1)
            ->whereIn('r.status', $status);
        if ($id > 0) {
            $retObj->where('r.driver_id', $id);
        }
        $retObj->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time, r.id as pid , start_location as pickup_location ,end_location as drop_location,d.photo'));
        if ($single == 1) {
            $array = $retObj->first();
        } else {
            $array = $retObj->get();
        }
        return json_decode(json_encode($array), 1);
    }

    public function adminPendingRides()
    {
        $retObj = DB::table('ride as r')
            ->where('r.is_active', 1)
            ->where('r.status', 0)
            ->whereDate('r.date', '>=', date('Y-m-d'));
        $retObj->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(date, "%a %d %b %Y") as date, r.id as pid , start_location as pickup_location ,end_location as drop_location'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }

    public function driverPastRides($id)
    {
        $retObj = DB::table('ride as r')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('r.is_active', 1)
            ->where('r.status', 5)
            ->whereDate('r.date', '<=', date('Y-m-d'))
            ->orderBy('r.id', 'desc');
        if ($id > 0) {
            $retObj->where('r.driver_id', $id);
        }
        $retObj->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time , r.id as pid, start_location as pickup_location ,end_location as drop_location,d.photo'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }
    public function driverAllRides($id)
    {
        $retObj = DB::table('ride as r')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('r.is_active', 1);
        if ($id > 0) {
            $retObj->where('r.driver_id', $id);
        }
        $retObj->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time , r.id as pid, start_location as pickup_location ,end_location as drop_location'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }

    public function passengerPastRides($id)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->leftJoin('driver as d', 'd.id', '=', 'r.driver_id')
            ->leftJoin('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('p.is_active', 1)
            ->where('r.is_active', 1)
            //  ->whereDate('p.pickup_time', '<=', date('Y-m-d'))
            ->where('p.status',  2)
            ->where('p.passenger_id', $id)
            ->orderBy('p.id', 'desc')
            ->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo,p.rating,null as actual_pickup_location,null as actual_drop_location,null as cab_reach_location'))
            ->get();
        return json_decode(json_encode($retObj), 1);
    }



    public function passengerAllRides($id)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->leftJoin('driver as d', 'd.id', '=', 'r.driver_id')
            ->leftJoin('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('p.is_active', 1)
            ->where('r.is_active', 1)
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,r.status as ride_status,DATE_FORMAT(pickup_time, "%l:%i %p") as pickup_time , p.id as pid,null as actual_pickup_location,null as actual_drop_location,null as cab_reach_location'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }

    public function passengerBookingRides($id)
    {
        $retObj = DB::table('ride_request as p')
            ->select(DB::raw('*,DATE_FORMAT(time, "%a %d %b %y %l:%i %p") as pickup_time,TIMESTAMPDIFF(HOUR,NOW(),`time`) as hours'))
            ->where('p.is_active', 1)
            ->where('p.passenger_id', $id)
            ->where('p.time', '>', date('Y-m-d H:i:s'))
            ->get();
        return json_decode(json_encode($retObj), 1);
    }


    public function pendingBookingRides($project_id = 0)
    {
        $retObj = DB::table('ride_request as p')
            ->select(DB::raw('p.*,employee_name,employee_code,gender,DATE_FORMAT(time, "%a %d %b %y %l:%i %p") as pickup_time,TIMESTAMPDIFF(HOUR,NOW(),`time`) as hours'))
            ->where('p.is_active', 1)
            ->join('passenger as r', 'r.id', '=', 'p.passenger_id')
            ->whereIn('p.status', [0, 2])
            ->where('p.project_id', $project_id)
            ->where('p.time', '>', date('Y-m-d H:i:s'))
            ->get();
        return json_decode(json_encode($retObj), 1);
    }


    public function getRidePassenger($ride_id)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('passenger as pr', 'pr.id', '=', 'p.passenger_id')
            ->where('p.is_active', 1)
            ->where('p.status', '<>', 4)
            ->where('p.ride_id', $ride_id)
            ->select(DB::raw('p.id,pr.address,pr.mobile ,p.status,p.otp,TIME_FORMAT(p.pickup_time, "%h:%i %p") as pickup_time ,TIME_FORMAT(p.drop_time, "%h:%i %p") as drop_time ,
            p.pickup_location,p.drop_location,pr.icon,pr.location,pr.employee_name as name,pr.gender,p.passenger_id,null as actual_pickup_location,null as actual_drop_location,null as cab_reach_location,p.passenger_type'))
            ->get();
        return json_decode(json_encode($retObj), 1);
    }


    public function chatList($id)
    {
        $retObj = DB::table('chat_group_member as p')
            ->join('chat_group as r', 'r.id', '=', 'p.group_id')
            ->where('p.is_active', 1)
            ->where('p.user_id', $id)
            ->orderBy('r.id', 'desc')
            ->select(DB::raw('distinct r.id,r.name,r.status,DATE_FORMAT(r.created_date, "%a %d %b %y %l:%i %p") as datetime'))
            ->get();
        return json_decode(json_encode($retObj), 1);
    }

    public function WhatsappList()
    {
        $results = DB::table('whatsapp_messages as t1')
            ->select(DB::raw('
        MAX(view.name) as name,
        t1.mobile,
        DATE_FORMAT(MAX(t1.created_date), "%a %d %b %y %l:%i %p") as last_update_date
    '))
            ->leftJoin('new_view as view', 't1.mobile', '=', 'view.mobile')
            ->whereNotNull('t1.mobile')
            ->where('t1.mobile', '!=', '')
            ->groupBy('t1.mobile')
            ->orderByDesc(DB::raw('MAX(t1.created_date)'))
            ->get()
            ->toArray();
        $results = json_decode(json_encode($results), 1);
        return $results;
    }

    public function getPendingRequestCount($project_id = 0)
    {
        $retObj = DB::table('ride_request as p')
            ->where('p.is_active', 1)
            ->whereIn('p.status', [0, 2])
            ->where('p.time', '>', date('Y-m-d H:i:s'));
        if ($project_id > 0) {
            $retObj->where('p.project_id', $project_id);
        }
        return $retObj->count();
    }

    public function getPendingMessageCountsByMobiles($mobiles = [])
    {
        return DB::table('whatsapp_messages')
            ->select('mobile', DB::raw('COUNT(*) as pending_count'))
            ->where('status', '=', 'pending') // replace with actual pending logic
            ->groupBy('mobile')
            ->pluck('pending_count', 'mobile') // returns [mobile => count]
            ->toArray();
    }


    public function getPendingMessagetCount($mobile)
    {
        return DB::table('whatsapp_messages as p')
            ->where('p.is_active', 1)
            ->where('p.mobile', $mobile)
            ->where('p.status', 'delivered')
            ->where('p.type', 'Received')
            ->count();
    }
}
