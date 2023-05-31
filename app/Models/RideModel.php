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
            ->where('p.status', 0)
            ->whereDate('r.date', '>=', date('Y-m-d'))
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time ,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo'));
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
            ->where('r.status', '<', 3)
            ->where('r.status', '>', 1)
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo'));
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
            ->where('p.rating', 0)
            ->where('p.status',  2)
            ->where('p.passenger_id', $id)
            ->orderBy('p.id', 'DESC')
            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo'));
        $array = $retObj->first();
        return json_decode(json_encode($array), 1);
    }


    public function driverLiveRide($id, $single = 1)
    {
        $retObj = DB::table('ride as r')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('r.is_active', 1)
            ->where('r.status', 2);
        if ($id > 0) {
            $retObj->where('r.driver_id', $id);
        }
        $retObj->select(DB::raw('*,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(start_time, "%l:%i %p") as only_time,d.name as driver_name, r.id as pid , start_location as pickup_location ,end_location as drop_location'));
        if ($single == 1) {
            $array = $retObj->first();
        } else {
            $array = $retObj->get();
        }
        return json_decode(json_encode($array), 1);
    }

    public function driverUpcomingRides($id, $single = 0)
    {
        $retObj = DB::table('ride as r')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('r.is_active', 1)
            ->whereIn('r.status', [1, 2])
            ->whereDate('r.date', '>=', date('Y-m-d'));
        if ($id > 0) {
            $retObj->where('r.driver_id', $id);
        }
        $retObj->select(DB::raw('*,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time, r.id as pid , start_location as pickup_location ,end_location as drop_location'));
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
        $retObj->select(DB::raw('*,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(date, "%a %d %b %Y") as date, r.id as pid , start_location as pickup_location ,end_location as drop_location'));
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
        $retObj->select(DB::raw('*,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time , r.id as pid, start_location as pickup_location ,end_location as drop_location'));
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
        $retObj->select(DB::raw('*,DATE_FORMAT(start_time, "%a %d %b %y %l:%i %p") as pickup_time , r.id as pid, start_location as pickup_location ,end_location as drop_location'));
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
            ->where('p.status', '>', 1)
            ->where('p.passenger_id', $id)
            ->orderBy('p.id', 'desc')

            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time,DATE_FORMAT(pickup_time, "%l:%i %p") as only_time,d.name as driver_name, p.id as pid,d.photo,p.rating'))
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
            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%l:%i %p") as pickup_time , p.id as pid'));
        $array = $retObj->get();
        return json_decode(json_encode($array), 1);
    }

    public function passengerBookingRides($id)
    {
        $retObj = DB::table('ride_request as p')
            ->where('p.is_active', 1)
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,DATE_FORMAT(time, "%a %d %b %y %l:%i %p") as pickup_time'))
            ->get();
        return json_decode(json_encode($retObj), 1);
    }


    public function getRidePassenger($ride_id)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('passenger as pr', 'pr.id', '=', 'p.passenger_id')
            ->leftJoin('users as r', function ($join) {
                $join->on("r.parent_id", "=", "p.passenger_id")
                    ->where("r.user_type",  5);
            })
            ->where('p.is_active', 1)
            ->where('p.ride_id', $ride_id)
            ->select(DB::raw('p.id,pr.address,pr.mobile ,p.status,p.otp,TIME_FORMAT(p.pickup_time, "%h %i %p") as pickup_time ,TIME_FORMAT(p.drop_time, "%h %i %p") as drop_time ,
            p.pickup_location,p.drop_location,r.icon,pr.location,pr.employee_name as name,pr.gender'))
            ->get();
        return json_decode(json_encode($retObj), 1);
    }
}
