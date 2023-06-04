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

class Mis extends Model
{

    public function saveLogsheetbill($vehicle_id, $date, $employee_name, $location, $start_km, $end_km, $total_km, $logsheet_no, $shift_time, $pick_drop, $remark, $toll, $user_id)
    {
        $id = DB::table('mis')->insertGetId(
            [
                'vehicle_id' => $vehicle_id,
                'employee' => $employee_name,
                'location' => $location,
                'start_km' => $start_km,
                'end_km' => $end_km,
                'total_km' => $total_km,
                'shift_time' => $shift_time,
                'logsheet_no' => $logsheet_no,
                'date' => $date,
                'toll' => $toll,
                'remark' => $remark,
                'pickdrop' => $pick_drop,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' => $user_id
            ]
        );
        return $id;
    }


    public function saveCompanyMIS($request, $det, $date, $pickup_time, $drop_time, $user_id)
    {
        $pickup_location = ($request->pickup != '') ? $request->pickup : $det->from;
        $drop_location = ($request->drop != '') ? $request->drop : $det->to;

        $array = [
            'date' => $date,
            'pickup_time' => $pickup_time,
            'drop_time' => $drop_time,
            'pickup_location' => $pickup_location,
            'drop_location' => $drop_location,
            'company_id' => $request->company_id,
            'office_location' => $request->office_location,
            'vendor' => $request->vendor,
            'user_count' => $request->user_count,
            'car_no' => $request->car_no,
            'car_type' => $det->car_type,
            'zone' => $det->zone,
            'svk_km' => $det->svk_km,
            'vendor_km' => $det->vendor_km,
            'admin_km' => $det->admin_km,
            'company_km' => $det->company_km,
            'svk_amount' => $det->svk_amount,
            'vendor_amount' => $det->vendor_amount,
            'admin_amount' => $det->admin_amount,
            'company_amount' => $det->company_amount,
            'logsheet_no' => $request->logsheet_no,
            'toll' => $request->toll,
            'pickup_drop' => $request->pickup_drop,
            'remark' => $request->remark,
            'employee_name' => $request->employee_name,
            'created_by' => $user_id,
            'created_date' => date('Y-m-d H:i:s'),
            'last_update_by' => $user_id
        ];

        if ($request->id > 0) {
            $id = $request->id;
            DB::table('company_mis')
                ->where('id', $request->id)
                ->update($array);
        } else {
            $id = DB::table('company_mis')->insertGetId(
                $array
            );
        }

        return $id;
    }

    public function saveMISEmployee($name, $user_id)
    {
        $id = DB::table('mis_employee')->insertGetId(
            [
                'employee_name' => $name,
                'admin_id' => 0,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' => $user_id
            ]
        );
        return $id;
    }

    public function saveMISLocation($name, $km, $user_id)
    {
        $id = DB::table('location_km')->insertGetId(
            [
                'location' => $name,
                'km' => $km,
                'admin_id' => 0,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' => $user_id
            ]
        );
        return $id;
    }

    public function getMaxKm($array)
    {
        $retObj = DB::table('location_km')
            ->select(DB::raw('max(km) as km'))
            ->where('is_active', 1)
            ->whereIn('location', $array)
            ->first();
        return $retObj;
    }

    public function getMisVehicle()
    {
        $retObj = DB::table('mis as m')
            ->join('vehicle as v', 'v.vehicle_id', '=', 'm.vehicle_id')
            ->select(DB::raw('distinct v.vehicle_id,v.name'))
            ->where('m.is_active', 1)
            ->get();
        return $retObj;
    }

    public function getMISList($start_date = null, $end_date = null, $vehicle_id = 0)
    {
        if ($start_date == null) {
            $retObj = DB::table('mis as m')
                ->join('vehicle as v', 'v.vehicle_id', '=', 'm.vehicle_id')
                ->select(DB::raw('m.*,v.number as vehicle_name'))
                ->where('m.is_active', 1)
                ->orderBy('date', 'asc')
                ->limit(50)
                ->get();
        } else {

            if ($vehicle_id > 0) {
                $retObj = DB::table('mis as m')
                    ->join('vehicle as v', 'v.vehicle_id', '=', 'm.vehicle_id')
                    ->select(DB::raw('m.*,v.number as vehicle_name'))
                    ->where('date', ">=", $start_date)
                    ->where('date', "<=", $end_date)
                    ->where('m.is_active', 1)
                    ->where('m.vehicle_id', $vehicle_id)
                    ->orderBy('date', 'asc')
                    ->get();
            } else {
                $retObj = DB::table('mis as m')
                    ->join('vehicle as v', 'v.vehicle_id', '=', 'm.vehicle_id')
                    ->select(DB::raw('m.*,v.number as vehicle_name'))
                    ->where('date', ">=", $start_date)
                    ->where('date', "<=", $end_date)
                    ->where('m.is_active', 1)
                    ->orderBy('date', 'asc')
                    ->get();
            }
        }
        return $retObj;
    }

    public function getMISListCompany($start_date = null, $end_date = null, $company_id = 0)
    {
        if ($start_date == null) {
            $retObj = DB::table('company_mis as m')
                ->join('company as v', 'v.company_id', '=', 'm.company_id')
                ->select(DB::raw('m.*,v.name as company_name'))
                ->where('m.is_active', 1)
                ->orderBy('date', 'asc')
                ->limit(50)
                ->get();
        } else {

            if ($company_id > 0) {
                $retObj = DB::table('company_mis as m')
                    ->join('company as v', 'v.company_id', '=', 'm.company_id')
                    ->select(DB::raw('m.*,v.name as company_name'))
                    ->where('date', ">=", $start_date)
                    ->where('date', "<=", $end_date)
                    ->where('m.is_active', 1)
                    ->where('m.company_id', $company_id)
                    ->orderBy('date', 'asc')
                    ->get();
            } else {
                $retObj = DB::table('company_mis as m')
                    ->join('company as v', 'v.company_id', '=', 'm.company_id')
                    ->select(DB::raw('m.*,v.name as company_name'))
                    ->where('date', ">=", $start_date)
                    ->where('date', "<=", $end_date)
                    ->where('m.is_active', 1)
                    ->orderBy('date', 'asc')
                    ->get();
            }
        }
        return $retObj;
    }

    public function getStartKm($month, $year, $vehicle_id)
    {
        $retObj = DB::table('mis')
            ->select(DB::raw('max(end_km) as km'))
            ->whereMonth("date", $month)
            ->whereYear("date", $year)
            ->where('vehicle_id', $vehicle_id)
            ->where('is_active', 1)
            ->first();
        return $retObj;
    }

    function getInvoiceId($company_id, $vehicle_id, $date, $admin_id)
    {
        $retObj = DB::table('logsheet_invoice')
            ->select(DB::raw('invoice_id'))
            ->where('company_id', $company_id)
            ->where('vehicle_id', $vehicle_id)
            ->where('date', $date)
            ->where('admin_id', $admin_id)
            ->first();
        if (isset($retObj->invoice_id)) {
            return $retObj->invoice_id;
        } else {
            return 0;
        }
    }

    public function getALLMISLIST($from_date, $to_date)
    {
        $retObj = DB::table('mis')
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->where('date', ">=", $from_date)
            ->where('date', "<=", $to_date)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        return $retObj;
    }

    public function refreshMISKM($from_date, $to_date)
    {
        DB::table('mis')
            ->where('is_active', 1)
            ->where('date', ">=", $from_date)
            ->where('date', "<=", $to_date)
            ->update([
                'start_km' => 0,
                'end_km' => 0,
                'total_km' => 0
            ]);
    }

    public function updateMISKM($id, $start_km, $end_km, $total_km)
    {
        DB::table('mis')
            ->where('id', $id)
            ->update([
                'start_km' => $start_km,
                'end_km' => $end_km,
                'total_km' => $total_km
            ]);
    }
}
