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
            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time'));
        if ($single == 1) {
            $array = $retObj->first();
        } else {
            $array = $retObj->get();
        }
        return json_decode(json_encode($array), 1);
    }

    public function passengerLiveRide($id)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->leftJoin('driver as d', 'd.id', '=', 'r.driver_id')
            ->leftJoin('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('p.is_active', 1)
            ->where('r.is_active', 1)
            ->where('p.status', 1)
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time'))
            ->first();
        return json_decode(json_encode($retObj), 1);
    }

    public function passengerPastRides($id)
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->leftJoin('driver as d', 'd.id', '=', 'r.driver_id')
            ->leftJoin('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->where('p.is_active', 1)
            ->where('r.is_active', 1)
            ->whereDate('p.pickup_time', '<', date('Y-m-d'))
            ->where('p.status', '>', 1)
            ->where('p.passenger_id', $id)
            ->select(DB::raw('*,DATE_FORMAT(pickup_time, "%a %d %b %y %l:%i %p") as pickup_time'))
            ->get();
        return json_decode(json_encode($retObj), 1);
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
}
