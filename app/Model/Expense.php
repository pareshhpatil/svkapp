<?php

namespace App\Model;

/**
 *
 * @author Paresh
 */

use Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class Expense extends ParentModel
{
    public function saveMaster($table, $name, $merchant_id, $user_id)
    {

        $id = DB::table($table)->insertGetId(
            [
                'name' => $name,
                'merchant_id' => $merchant_id,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function saveVendorTransfer($data, $merchant_id, $user_id)
    {

        $id = DB::table('vendor_transfer')->insertGetId(
            [
                'merchant_id' => $merchant_id,
                'vendor_id' => $data->vendor_id,
                'amount' => $data->amount,
                'narrative' => $data->narrative,
                'status' => 1,
                'type' => 2,
                'offline_response_type' => $data->payment_mode,
                'transfer_date' => $data->date,
                'bank_name' => $data->bank_name,
                'bank_transaction_no' => $data->bank_transaction_no,
                'cheque_no' => $data->cheque_no,
                'cash_paid_to' => $data->cash_paid_to,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function updateVendorTransfer($data, $id, $user_id)
    {

        DB::table('vendor_transfer')
            ->where('transfer_id', $id)
            ->update([
                'vendor_id' => $data->vendor_id,
                'amount' => $data->amount,
                'narrative' => $data->narrative,
                'status' => 1,
                'type' => 2,
                'offline_response_type' => $data->payment_mode,
                'transfer_date' => $data->date,
                'bank_name' => $data->bank_name,
                'bank_transaction_no' => $data->bank_transaction_no,
                'cheque_no' => $data->cheque_no,
                'cash_paid_to' => $data->cash_paid_to,
                'last_update_by' => $user_id
            ]);
    }

    public function updateMaster($table, $name, $id, $user_id)
    {

        DB::table($table)
            ->where('id', $id)
            ->update([
                'name' => $name,
                'last_update_by' => $user_id
            ]);
    }

    public function updateBulkUploadStatus($id, $status)
    {

        DB::table('bulk_upload')
            ->where('bulk_upload_id', $id)
            ->update([
                'status' => $status
            ]);
    }

    public function updateExpense($column, $value, $expense_id, $table = 'expense')
    {

        if ($table == 'credit_debit_note') {
            $column_name = 'id';
        } else {
            $column_name = 'expense_id';
        }
        DB::table($table)
            ->where($column_name, $expense_id)
            ->update([
                $column => $value
            ]);
    }

    public function getExpenseSequence($merchant_id, $type)
    {

        $retObj = DB::table('merchant_auto_invoice_number')
            ->select(DB::raw('*'))
            ->where('merchant_id', $merchant_id)
            ->where('type', $type)
            ->where('is_active', 1)
            ->first();
        return $retObj;
    }

    public function getPendingBulkExpense()
    {

        $retObj = DB::table('bulk_upload')
            ->select(DB::raw('*'))
            ->where('status', 4)
            ->where('type', 10)
            ->get();
        return $retObj;
    }

    public function getStagingExpenseList($bulk_id)
    {

        $retObj = DB::table('staging_expense')
            ->select(DB::raw('*'))
            ->where('bulk_id', $bulk_id)
            ->where('is_active', 1)
            ->get();
        return $retObj;
    }

    public function bulkexpensesave($expense_id, $expense_no)
    {
        $retObj = DB::select("call save_bulk_expense(" . $expense_id . ",'" . $expense_no . "');");
        $data = json_decode(json_encode($retObj), true);
        return $data;
    }

    public function deleteLedger($id)
    {
        DB::select("select delete_ledger('" . $id . "',3);");
    }

    public function expenseNumber($id)
    {
        $retObj = DB::select("select generate_invoice_number(" . $id . ") as auto_invoice_number");
        $data = json_decode(json_encode($retObj), true);
        return $data;
    }

    public function getExpenseList($merchant_id, $from_date, $to_date, $type, $category_id, $department_id, $payment_status)
    {

        $retObj = DB::table('expense as e')
            ->select(DB::raw('e.*,c.name as category,d.name as department,v.vendor_name,v.state,v.gst_number'))
            ->join('expense_category as c', 'e.category_id', '=', 'c.id')
            ->join('expense_department as d', 'e.department_id', '=', 'd.id')
            ->join('vendor as v', 'e.vendor_id', '=', 'v.vendor_id')
            ->where('e.is_active', 1)
            ->where('e.type', $type)
            ->where('e.merchant_id', $merchant_id)
            ->whereDate('e.created_date', '>=', $from_date)
            ->whereDate('e.created_date', '<=', $to_date);
        if ($category_id > 0) {
            $retObj->where('e.category_id', $category_id);
        }
        if ($department_id > 0) {
            $retObj->where('e.department_id', $department_id);
        }
        if ($payment_status != '') {
            $retObj->where('e.payment_status', $payment_status);
        }
        $data = $retObj->get();
        return $data;
    }

    public function getBulkExpenseList($merchant_id, $bulk_id, $table = 'staging_expense', $category_id = 0, $department_id = 0, $payment_status = '')
    {

        $retObj = DB::table($table . ' as e')
            ->select(DB::raw('e.*,c.name as category,d.name as department,v.vendor_name,v.state,v.gst_number'))
            ->join('expense_category as c', 'e.category_id', '=', 'c.id')
            ->join('expense_department as d', 'e.department_id', '=', 'd.id')
            ->join('vendor as v', 'e.vendor_id', '=', 'v.vendor_id')
            ->where('e.is_active', 1)
            ->where('e.bulk_id', $bulk_id)
            ->where('e.merchant_id', $merchant_id);
        if ($category_id > 0) {
            $retObj->where('e.category_id', $category_id);
        }
        if ($department_id > 0) {
            $retObj->where('e.department_id', $department_id);
        }
        if ($payment_status != '') {
            $retObj->where('e.payment_status', $payment_status);
        }
        $data = $retObj->get();
        return $data;
        return $retObj;
    }

    public function getPendingExpenseList($merchant_id, $from_date, $to_date)
    {

        $retObj = DB::table('staging_expense as e')
            ->select(DB::raw('e.*,v.vendor_name,v.state,v.email_id,v.mobile,v.gst_number'))
            ->leftJoin('vendor as v', 'e.vendor_id', '=', 'v.vendor_id')
            ->where('e.type', 3)
            ->where('e.is_active', 1)
            ->whereDate('e.created_date', '>=', $from_date)
            ->whereDate('e.created_date', '<=', $to_date)
            ->where('e.merchant_id', $merchant_id);

        $data = $retObj->get();
        return $data;
    }

    public function getExpenseData($expense_id, $merchant_id, $table = 'expense')
    {
        $retObj = DB::table($table . ' as e')
            ->select(DB::raw('e.*,c.name as category,d.name as department,v.vendor_name,v.state,v.gst_number,v.email_id,v.mobile,v.address'))
            ->leftJoin('expense_category as c', 'e.category_id', '=', 'c.id')
            ->leftJoin('expense_department as d', 'e.department_id', '=', 'd.id')
            ->leftJoin('vendor as v', 'e.vendor_id', '=', 'v.vendor_id')
            ->where('e.expense_id', $expense_id)
            ->where('e.merchant_id', $merchant_id)
            ->first();
        return $retObj;
    }

    public function updatePaymentData($payment_status, $payment_mode, $expense_id, $user_id)
    {
        DB::table('expense')
            ->where('expense_id', $expense_id)
            ->update([
                'payment_status' => $payment_status,
                'payment_mode' => $payment_mode,
                'last_update_by' => $user_id
            ]);
    }

    public function stockManagement($expense_id,$merchant_id,$type=1)
    {
        DB::select("call `stock_management`('".$merchant_id."',".$expense_id.",2,".$type.");");
    }
}
