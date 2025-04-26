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
                $retObj->where('r.start_time', '>=', $from_date);
            }
            if ($to_date != null) {
                $retObj->where('r.start_time', '<=', $to_date);
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


    public function getRoute($project_id, $from_date, $to_date, $status, $project_ids = [])
    {
        $retObj = DB::table('ride as a')
            ->join('project as ea', 'ea.project_id', '=', 'a.project_id')
            ->join('config as c', 'c.value', '=', 'a.status')
            ->where('a.is_active', 1)
            ->where('c.type', 'ride_status')
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
        if (!empty($status)) {
            $retObj->whereIn('a.status', $status);
        }

        $array =   $retObj->get();
        return $array;
    }

    public function getRides($project_id, $from_date, $to_date, $status, $project_ids = [])
    {
        $retObj = DB::table('ride as a')
            ->join('project as ea', 'ea.project_id', '=', 'a.project_id')
            ->join('config as c', 'c.value', '=', 'a.status')
            ->where('a.is_active', 1)
            ->where('c.type', 'ride_status')
            ->select(DB::raw('a.*,ea.name as project_name,c.description,TIME_FORMAT(a.start_time, "%h:%i %p") as display_start_time,DATE_FORMAT(a.date,"%d-%b-%Y") as date'));
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
        if (!empty($status)) {
            $retObj->whereIn('a.status', $status);
        }

        $array =   $retObj->get();
        return $array;
    }


    public function getRouteRoster($project_id, $from_date, $to_date, $status, $project_ids = [])
    {
        $retObj = DB::table('ride_passenger as rp')
            ->join('ride as r', 'rp.ride_id', '=', 'r.id')
            ->join('passenger as p', 'rp.passenger_id', '=', 'p.id')
            ->leftJoin('driver as d', 'r.driver_id', '=', 'd.id')
            ->leftJoin('vehicle as v', 'r.vehicle_id', '=', 'v.vehicle_id')
            ->leftJoin('roster as ro', 'rp.roster_id', '=', 'ro.id')
            ->where('rp.is_active', 1)
            ->where('r.is_active', 1)
            ->select(DB::raw('r.id,r.type,p.employee_name,p.employee_code,p.cost_center_code,p.gender,p.mobile,ro.shift,TIME_FORMAT(rp.pickup_time, "%h:%i %p") as pickup_time,rp.pickup_location,rp.drop_location,v.number as vehicle_number,v.car_type,d.name as driver_name,d.mobile as driver_mobile,DATE_FORMAT(r.date,"%d %b %y") as date,r.escort'));
        if ($project_id > 0) {
            $retObj->where('r.project_id', $project_id);
        } else {
            if (!empty($project_ids)) {
                $retObj->whereIn('r.project_id', $project_ids);
            }
        }
        if ($from_date != null) {
            $retObj->where('r.start_time', '>=', $from_date)
                ->where('r.start_time', '<=', $to_date);
        }
        if (!empty($status)) {
            $retObj->whereIn('r.status', $status);
        }

        $retObj->orderBy('r.id','asc');
        $retObj->orderBy('r.start_time','asc');

        $array =   $retObj->get();
        return $array;
    }
}
