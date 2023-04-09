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

class Bill extends Model {

    public function getTransactionList($admin_id) {
        $retObj = DB::table('transaction as a')
                ->leftJoin('employee as e', 'e.employee_id', '=', 'a.employee_id')
                ->join('paymentsource as p', 'p.paymentsource_id', '=', 'a.source_id')
                ->where('a.admin_id', $admin_id)
                ->where('a.is_active', 1)
                ->where('a.status', 1)
                ->select(DB::raw('a.*,e.name as employee_name,p.name as payment_source'))
                ->get();
        return $retObj;
    }

    public function getStatement($source_id) {
        $retObj = DB::table('transaction as a')
                ->leftJoin('employee as e', 'e.employee_id', '=', 'a.employee_id')
                ->where('a.source_id', $source_id)
                ->where('a.is_active', 1)
                ->where('a.status', 1)
                ->select(DB::raw('a.*,e.name as employee_name'))
                ->get();
        return $retObj;
    }
    
    public function getRequestList($admin_id) {
        $retObj = DB::table('request as a')
                ->leftJoin('employee as e', 'e.employee_id', '=', 'a.employee_id')
                ->where('a.admin_id', $admin_id)
                ->where('a.is_active', 1)
                ->select(DB::raw('a.*,e.name as employee_name'))
                ->get();
        return $retObj;
    }

    public function getPendingRequest() {
        $retObj = DB::table('request as a')
                ->leftJoin('employee as e', 'e.employee_id', '=', 'a.employee_id')
                ->where('a.adjust_status',0)
                ->where('a.pending_amount','>',0)
                ->where('a.is_active', 1)
                ->select(DB::raw('a.*,e.name as employee_name'))
                ->get();
        return $retObj;
    }

    public function getBillList($admin_id) {
        $retObj = DB::table('transaction as a')
                ->join('employee as v', 'v.employee_id', '=', 'a.employee_id')
                ->where('a.admin_id', $admin_id)
                ->where('a.is_active', 1)
                ->where('a.status', 0)
                ->select(DB::raw('a.*,v.name'))
                ->get();
        return $retObj;
    }

    public function saveBill($employee_id,$category,$company_id,$vehicle_id,$pending_amount, $remark, $date, $amount, $user_id, $admin_id) {
        $id = DB::table('request')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'employee_id' => $employee_id,
                    'category' => $category,
                    'company_id' => $company_id,
                    'vehicle_id' => $vehicle_id,
                    'pending_amount' => $pending_amount,
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

    public function saveCredit($from_id,$to_id, $remark, $date, $amount, $user_id, $admin_id) {
        $id = DB::table('payment_credit')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'from_id' => $from_id,
                    'source_id' => $to_id,
                    'paid_date' => $date,
                    'amount' => $amount,
                    'narrative' => $remark,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function saveTransaction($status, $employee_id, $date, $amount, $payment_mode, $remark, $ref_no, $payment_source, $type, $user_id, $admin_id) {
        $id = DB::table('transaction')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'status' => $status,
                    'employee_id' => $employee_id,
                    'amount' => $amount,
                    'paid_date' => $date,
                    'narrative' => $remark,
                    'payment_mode' => $payment_mode,
                    'ref_no' => $ref_no,
                    'source_id' => $payment_source,
                    'type' => $type,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function updateTransaction($status, $id,$amount, $payment_mode, $source_id, $date, $user_id) {
        DB::table('transaction')
                ->where('transaction_id', $id)
                ->update([
                    'status' => $status,
                    'payment_mode' => $payment_mode,
                    'source_id' => $source_id,
                    'amount' => $amount,
                    'paid_date' => $date,
                    'last_update_by' => $user_id
        ]);
    }

}
