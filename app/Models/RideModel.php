<?php

namespace App\Models;

use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;


class RideModel extends ParentModel
{
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
            ->select(DB::raw('p.id,pr.address,pr.mobile ,p.status,p.otp,TIME_FORMAT(p.pickup_time, "%h %i %p") as pickup_time ,rating,TIME_FORMAT(p.cab_time, "%h %i %p") as cab_time ,TIME_FORMAT(p.in_time, "%h %i %p") as in_time ,TIME_FORMAT(p.drop_time, "%h %i %p") as drop_time ,
            p.pickup_location,p.drop_location,r.icon,pr.location,pr.employee_name as name,pr.gender,p.passenger_id'))
            ->get();
        return $retObj;
    }
}
