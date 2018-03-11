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

class Employee extends Model {

    public function getAbsentList($admin_id) {
        $retObj = DB::table('absent as a')
                ->join('vehicle as v', 'v.vehicle_id', '=', 'a.vehicle_id')
                ->join('employee as er', 'er.employee_id', '=', 'a.replace_employee_id')
                ->join('employee as ea', 'ea.employee_id', '=', 'a.employee_id')
                ->where('a.admin_id', $admin_id)
                ->select(DB::raw('a.absent_id,ea.name as absent_name,er.name as replace_name,v.name as vehicle_name,a.date,a.is_deduct'))
                ->get();
        return $retObj;
    }

    public function getOverTimeList($admin_id) {
        $retObj = DB::table('overtime as a')
                ->join('vehicle as v', 'v.vehicle_id', '=', 'a.vehicle_id')
                ->join('employee as er', 'er.employee_id', '=', 'a.replace_employee_id')
                ->join('employee as ea', 'ea.employee_id', '=', 'a.employee_id')
                ->where('a.admin_id', $admin_id)
                ->select(DB::raw('a.ot_id,ea.name as over_name,er.name as replace_name,v.name as vehicle_name,a.date,a.amount'))
                ->get();
        return $retObj;
    }

    public function getAdvanceList($admin_id) {
        $retObj = DB::table('advance as a')
                ->join('employee as ea', 'ea.employee_id', '=', 'a.employee_id')
                ->where('a.admin_id', $admin_id)
                ->select(DB::raw('a.advance_id,ea.name as employee_name,a.amount,a.date,a.note'))
                ->get();
        return $retObj;
    }

    public function getSalaryList($admin_id) {
        $retObj = DB::table('salary as a')
                ->join('employee as ea', 'ea.employee_id', '=', 'a.employee_id')
                ->where('a.admin_id', $admin_id)
                ->select(DB::raw('a.*,ea.name as employee_name'))
                ->get();
        return $retObj;
    }

    public function saveSalary($employee_id, $salary_month, $salary_date, $salary_amount, $absent_amount, $advance_amount, $overtime_amount, $paid_amount, $absent_id, $advance_id, $overtime_id, $remark, $user_id, $admin_id) {
        $id = DB::table('salary')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'employee_id' => $employee_id,
                    'salary_month' => $salary_month,
                    'salary_date' => $salary_date,
                    'salary_amount' => $salary_amount,
                    'absent_amount' => $absent_amount,
                    'advance_amount' => $advance_amount,
                    'overtime_amount' => $overtime_amount,
                    'paid_amount' => $paid_amount,
                    'absent_id' => $absent_id,
                    'advance_id' => $advance_id,
                    'overtime_id' => $overtime_id,
                    'note' => $remark,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function saveAbsent($vehicle_id, $absent_employee_id, $replace_employee_id, $date, $remark, $is_deduct, $user_id, $admin_id) {
        $id = DB::table('absent')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'employee_id' => $absent_employee_id,
                    'vehicle_id' => $vehicle_id,
                    'replace_employee_id' => $replace_employee_id,
                    'date' => $date,
                    'is_deduct' => $is_deduct,
                    'note' => $remark,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function saveOverTime($vehicle_id, $absent_employee_id, $replace_employee_id, $date, $amount, $remark, $user_id, $admin_id) {
        $id = DB::table('overtime')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'employee_id' => $absent_employee_id,
                    'vehicle_id' => $vehicle_id,
                    'replace_employee_id' => $replace_employee_id,
                    'date' => $date,
                    'amount' => $amount,
                    'note' => $remark,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function saveAdvance($employee_id, $amount, $date, $remark, $user_id, $admin_id) {
        $id = DB::table('advance')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'employee_id' => $employee_id,
                    'amount' => $amount,
                    'date' => $date,
                    'note' => $remark,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function updateAbsentAmount($amount, $id, $user_id) {
        DB::table('absent')
                ->where('absent_id', $id)
                ->update([
                    'amount_deduct' => $amount,
                    'is_deduct' => 1,
                    'last_update_by' => $user_id
        ]);
    }
}
