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
            ->where('p.passenger_type', 1)
            ->where('p.ride_id', $ride_id)
            ->select(DB::raw('p.id,pr.address,pr.mobile ,p.status,p.otp,TIME_FORMAT(p.pickup_time, "%h %i %p") as pickup_time ,rating,TIME_FORMAT(p.cab_time, "%h %i %p") as cab_time ,TIME_FORMAT(p.in_time, "%h %i %p") as in_time ,TIME_FORMAT(p.drop_time, "%h %i %p") as drop_time ,
            p.pickup_location,p.drop_location,r.icon,pr.location,pr.employee_name as name,pr.gender,p.passenger_id,p.actual_pickup_location,p.actual_drop_location'))
            ->get();
        return $retObj;
    }

    public function getRides($date, $project_id, $status, $project_ids = [])
    {
        $retObj = DB::table('ride_passenger as p')
            ->join('ride as r', 'r.id', '=', 'p.ride_id')
            ->join('passenger as pr', 'pr.id', '=', 'p.passenger_id')
            ->join('driver as d', 'd.id', '=', 'r.driver_id')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'r.vehicle_id')
            ->join('project as pro', 'pro.project_id', '=', 'r.project_id')
            ->where('p.is_active', 1)
            ->where('r.date', $date);
        if ($status != 0) {
            $retObj->where('r.status', $status);
        } else {
            $retObj->whereIn('r.status', [1, 2, 5]);
        }

        if ($project_id != 0) {
            $retObj->where('r.project_id', $project_id);
        } else {
            if (!empty($project_ids)) {
                $retObj->whereIn('r.project_id', $project_ids);
            }
        }
        $retObj = $retObj->select(DB::raw('p.id,p.passenger_type,p.ride_id,p.pickup_time,p.pickup_location,p.drop_location,p.status as pstatus,pr.employee_name,pr.gender,pr.location,pr.icon as photo,d.name as driver_name,d.mobile,d.photo,v.number as vehicle_number,r.type,r.start_time,r.end_time,r.start_location,r.end_location,r.status as ride_status,pro.lat_long as project_cords,pr.address as passenger_address,pro.location as office_location'))
            ->orderBy('r.status', 'asc')
            ->get();
        return $retObj;
    }

    public function getPendingMis($project_id, $from_date, $to_date, $project_ids = [])
    {
        $retObj = DB::table('ride as a')
            ->join('project as ea', 'ea.project_id', '=', 'a.project_id')
            ->join('config as c', 'c.value', '=', 'a.status')
            ->where('a.is_active', 1)
            ->where('c.type', 'ride_status')
            ->where('a.status', 5)
            ->where('a.mis_generated', 0)
            ->select(DB::raw('a.*,ea.name as project_name,c.description,TIME_FORMAT(a.start_time, "%h:%i %p") as display_start_time,DATE_FORMAT(a.date,"%d-%b-%Y") as date,c.Description as status'));
        if ($project_id > 0) {
            $retObj->where('a.project_id', $project_id);
        } else {
            if (!empty($project_ids)) {
                $retObj->whereIn('a.project_id', $project_ids);
            }
        }
        if ($from_date != null) {
            $retObj->where('a.start_time', '>=', $from_date)
                ->where('a.start_time', '<=', $to_date);
        }

        $array =   $retObj->get();
        return $array;
    }
}
