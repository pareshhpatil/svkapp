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
                ->leftJoin('vendor as v', 'v.vendor_id', '=', 'a.vendor_id')
                ->leftJoin('employee as e', 'e.employee_id', '=', 'a.employee_id')
                ->join('paymentsource as p', 'p.paymentsource_id', '=', 'a.source_id')
                ->where('a.admin_id', $admin_id)
                ->where('a.is_active', 1)
                ->select(DB::raw('a.*,v.business_name as vendor_name,e.name as employee_name,p.name as payment_source'))
                ->get();
        return $retObj;
    }

    public function getBillList($admin_id) {
        $retObj = DB::table('bill as a')
                ->join('vendor as v', 'v.vendor_id', '=', 'a.vendor_id')
                ->where('a.admin_id', $admin_id)
                ->where('a.is_active', 1)
                ->where('a.is_paid', 0)
                ->select(DB::raw('a.*,v.business_name as name'))
                ->get();
        return $retObj;
    }

    public function saveBill($vendor_id, $vehicle_id, $category, $date, $amount, $remark, $is_paid, $user_id, $admin_id) {
        $id = DB::table('bill')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'vendor_id' => $vendor_id,
                    'vehicle_id' => $vehicle_id,
                    'category' => $category,
                    'date' => $date,
                    'is_paid' => $is_paid,
                    'amount' => $amount,
                    'note' => $remark,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
        );
        return $id;
    }

    public function saveTransaction($vendor_id, $employee_id, $vehicle_id, $bill_id, $category, $date, $amount, $payment_mode, $remark, $ref_no, $payment_source, $type, $user_id, $admin_id) {
        $id = DB::table('transaction')->insertGetId(
                [
                    'admin_id' => $admin_id,
                    'vendor_id' => $vendor_id,
                    'vehicle_id' => $vehicle_id,
                    'employee_id' => $employee_id,
                    'bill_id' => $bill_id,
                    'category' => $category,
                    'amount' => $amount,
                    'paid_date' => $date,
                    'note' => $remark,
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

}
