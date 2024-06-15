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

class StaffModel extends ParentModel
{

    public function saveBill($employee_id, $category, $company_id, $vehicle_id, $pending_amount, $remark, $date, $amount, $user_id, $admin_id)
    {
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

    public function saveTransaction($status, $employee_id, $date, $amount, $payment_mode, $remark, $ref_no, $payment_source, $type, $user_id, $admin_id)
    {
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

    public function getBalance($employee_id)
    {
        $retObj = DB::table('employee')
            ->select(DB::raw('balance'))
            ->where('employee_id', $employee_id)
            ->first();
        return $retObj->balance;
    }

    public function savePaymentTransaction($request_id, $amount)
    {
        $id = DB::table('payment_transaction')->insertGetId(
            [
                'request_id' => $request_id,
                'amount' => $amount,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }


    public function updatePaymentTransaction($id, $status, $referenceId, $utr, $json)
    {
        DB::table('payment_transaction')
            ->where('id', $id)
            ->update([
                'status' => $status,
                'referenceId' => $referenceId,
                'json' => $json,
                'utr' => $utr
            ]);
    }

    public function updateTransaction($status, $id, $amount, $payment_mode, $source_id, $date, $user_id, $code = '')
    {
        DB::table('transaction')
            ->where('transaction_id', $id)
            ->update([
                'status' => $status,
                'payment_mode' => $payment_mode,
                'code' => $code,
                'source_id' => $source_id,
                'amount' => $amount,
                'last_update_by' => $user_id,
                'paid_date' => $date
            ]);
    }

    public function savePaymentStatement($source_id, $from_id, $paid_date, $amount, $transaction_type, $type, $narrative, $user_id)
    {
        $id = DB::table('payment_statement')->insertGetId(
            [
                'source_id' => $source_id,
                'from_id' => $from_id,
                'paid_date' => $paid_date,
                'amount' => $amount,
                'transaction_type' => $transaction_type,
                'type' => $type,
                'narrative' => $narrative,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' => $user_id
            ]
        );
        return $id;
    }


    public function getBillDetail($id)
    {
        $retObj = DB::table('transaction as a')
            ->join('employee as v', 'v.employee_id', '=', 'a.employee_id')
            ->where('a.transaction_id', $id)
            ->where('a.is_active', 1)
            ->where('a.status', 0)
            ->select(DB::raw("a.*,v.name,v.account_no,v.account_holder_name"))
            ->first();
        return $retObj;
    }


    public function getBillList($admin_id)
    {
        $retObj = DB::table('transaction as a')
            ->join('employee as v', 'v.employee_id', '=', 'a.employee_id')
            ->where('a.admin_id', $admin_id)
            ->where('a.is_active', 1)
            ->where('a.status', 0)
            ->select(DB::raw("a.*,v.name,v.account_no,v.account_holder_name"))
            ->orderBy('a.transaction_id', 'desc')
            ->get();
        return $retObj;
    }

    public function getTransactionList($last_update_by)
    {
        $retObj = DB::table('transaction as a')
            ->join('employee as v', 'v.employee_id', '=', 'a.employee_id')
            ->join('paymentsource as p', 'p.paymentsource_id', '=', 'a.source_id')
            ->where(function ($retObj) use ($last_update_by) {
                $retObj->where('a.last_update_by', $last_update_by)
                    ->orWhere('a.created_by', $last_update_by);
            })
            ->where('a.is_active', 1)
            ->whereIn('a.status', [1, 2])
            ->select(DB::raw("a.*,v.name,v.account_no,v.account_holder_name,p.name as payment_source"))
            ->orderBy('a.last_update_date', 'desc')
            ->get();
        return $retObj;
    }


    public function getPendingSum($admin_id)
    {
        $sum = DB::table('transaction as a')
            ->where('a.is_active', 1)
            ->where('a.admin_id', $admin_id)
            ->where('a.status', 0)
            ->sum('a.amount');
        return $sum;
    }

    public function getSourceBalance($ids)
    {
        $sum = DB::table('paymentsource as a')
            ->where('a.is_active', 1)
            ->whereIn('a.paymentsource_id', $ids)
            ->sum('a.balance');
        return $sum;
    }

    public function updateBankBalance($amount, $source_id, $minus = 1)
    {
        $data = $this->getTableRow('paymentsource', 'paymentsource_id', $source_id);
        $current_balance = $data->balance;
        if ($minus == 1) {
            $current_balance = $current_balance - $amount;
        } else {
            $current_balance = $current_balance + $amount;
        }
        $this->updateTable('paymentsource', 'paymentsource_id', $source_id, 'balance', $current_balance);
    }


    public function updateEmployeeBalance($amount, $employee_id, $minus = 1)
    {
        $current_balance = $this->getBalance($employee_id);
        if ($minus == 1) {
            $current_balance = $current_balance - $amount;
        } else {
            $current_balance = $current_balance + $amount;
        }
        $this->updateTable('employee', 'employee_id', $employee_id, 'balance', $current_balance);
    }


    public function saveWhatsapp($mobile, $name, $type, $status, $message_type, $message, $message_id)
    {
        $id = DB::table('whatsapp_messages')->insertGetId(
            [
                'mobile' => $mobile,
                'name' => $name,
                'type' => $type,
                'status' => $status,
                'message_type' => $message_type,
                'message' => $message,
                'message_id' => $message_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }
}
