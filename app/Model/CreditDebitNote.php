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

class CreditDebitNote extends ParentModel
{

    public function getCreditNoteList($merchant_id, $from_date, $to_date)
    {
        $retObj = DB::table('credit_debit_note as e')
            ->select(DB::raw('e.*,c.first_name,c.last_name,c.customer_code,c.gst_number,c.state'))
            ->join('customer as c', 'e.customer_id', '=', 'c.customer_id')
            ->where('e.is_active', 1)
            ->where('e.type', 1)
            ->where('e.merchant_id', $merchant_id)
            ->whereDate('e.created_date', '>=', $from_date)
            ->whereDate('e.created_date', '<=', $to_date);

        $data = $retObj->get();
        return $data;
    }

    public function getDebitNoteList($merchant_id, $from_date, $to_date)
    {
        $retObj = DB::table('credit_debit_note as e')
            ->select(DB::raw('e.*,c.vendor_name,c.gst_number,c.state'))
            ->join('vendor as c', 'e.vendor_id', '=', 'c.vendor_id')
            ->where('e.is_active', 1)
            ->where('e.type', 2)
            ->where('e.merchant_id', $merchant_id)
            ->whereDate('e.created_date', '>=', $from_date)
            ->whereDate('e.created_date', '<=', $to_date);

        $data = $retObj->get();
        return $data;
    }

    public function getCreditNoteData($id, $merchant_id)
    {
        $retObj = DB::table('credit_debit_note as e')
            ->select(DB::raw("e.*,concat(c.first_name ,' ',c.last_name) as name,c.state,c.gst_number,c.email as email_id,c.mobile,c.address,c.gst_number,c.customer_code"))
            ->join('customer as c', 'e.customer_id', '=', 'c.customer_id')
            ->where('e.id', $id)
            ->where('e.merchant_id', $merchant_id)
            ->first();
        return $retObj;
    }

    public function getDebitNoteData($id, $merchant_id)
    {
        $retObj = DB::table('credit_debit_note as e')
            ->select(DB::raw("e.*,c.vendor_name as name,c.state,c.gst_number,c.email_id,c.mobile,c.address"))
            ->join('vendor as c', 'e.vendor_id', '=', 'c.vendor_id')
            ->where('e.id', $id)
            ->where('e.merchant_id', $merchant_id)
            ->first();
        return $retObj;
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
        return $retObj->get();
    }

    public function getExpenseData($expense_id, $merchant_id, $table = 'expense')
    {
        $retObj = DB::table($table . ' as e')
            ->select(DB::raw('e.*,c.name as category,d.name as department,v.vendor_name,v.state,v.gst_number,v.email_id,v.mobile,v.address'))
            ->join('expense_category as c', 'e.category_id', '=', 'c.id')
            ->join('expense_department as d', 'e.department_id', '=', 'd.id')
            ->join('vendor as v', 'e.vendor_id', '=', 'v.vendor_id')
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

    public function deleteFranchiseSale($note_id, $user_id)
    {
        DB::table('credit_note_food_franchise_sales')
            ->where('credit_note_id', $note_id)
            ->update([
                'is_active' => 0,
                'last_update_by' => $user_id
            ]);
    }

    public function saveFranchiseSale($note_id, $date, $invoice_sale, $note_sale,$non_brand_invoice_sale, $non_brand_note_sale, $user_id)
    {
        $id = DB::table('credit_note_food_franchise_sales')->insertGetId(
            [
                'credit_note_id' => $note_id,
                'date' => $date,
                'inv_gross_sale' => $invoice_sale,
                'credit_gross_sale' => $note_sale,
                'non_brand_inv_gross_sale' => $non_brand_invoice_sale,
                'non_brand_credit_gross_sale' => $non_brand_note_sale,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function saveFranchiseSummary($note_id, $data, $bill_period, $user_id)
    {
        $id = DB::table('credit_note_food_franchise_summary')->insertGetId(
            [
                'credit_note_id' => $note_id,
                'gross_comm_percent' => $data->gross_comm_percent,
                'waiver_comm_percent' => $data->waiver_comm_percent,
                'net_comm_percent' => $data->net_comm_percent,
                'new_gross_comm_percent' => $data->new_gross_comm_percent,
                'new_waiver_comm_percent' => $data->new_waiver_comm_percent,
                'new_net_comm_percent' => $data->new_net_comm_percent,
                'gross_comm_amt' => $data->gross_comm_amt,
                'waiver_comm_amt' => $data->waiver_comm_amt,
                'net_comm_amt' => $data->net_comm_amt,
                'new_gross_comm_amt' => $data->new_gross_comm_amt,
                'new_waiver_comm_amt' => $data->new_waiver_comm_amt,
                'new_net_comm_amt' => $data->new_net_comm_amt,
                'gross_bilable_sale' => $data->gross_bilable_sale,
                'new_gross_bilable_sale' => $data->new_gross_bilable_sale,
                'net_bilable_sale' => $data->net_bilable_sale,
                'new_net_bilable_sale' => $data->new_net_bilable_sale,
                'non_brand_gross_comm_percent' => $data->non_brand_gross_comm_percent,
                'non_brand_waiver_comm_percent' => $data->non_brand_waiver_comm_percent,
                'non_brand_net_comm_percent' => $data->non_brand_net_comm_percent,
                'non_brand_new_gross_comm_percent' => $data->non_brand_new_gross_comm_percent,
                'non_brand_new_waiver_comm_percent' => $data->non_brand_new_waiver_comm_percent,
                'non_brand_new_net_comm_percent' => $data->non_brand_new_net_comm_percent,
                'non_brand_gross_comm_amt' => $data->non_brand_gross_comm_amt,
                'non_brand_waiver_comm_amt' => $data->non_brand_waiver_comm_amt,
                'non_brand_net_comm_amt' => $data->non_brand_net_comm_amt,
                'non_brand_new_gross_comm_amt' => $data->non_brand_new_gross_comm_amt,
                'non_brand_new_waiver_comm_amt' => $data->non_brand_new_waiver_comm_amt,
                'non_brand_new_net_comm_amt' => $data->non_brand_new_net_comm_amt,
                'non_brand_gross_bilable_sale' => $data->non_brand_gross_bilable_sale,
                'non_brand_new_gross_bilable_sale' => $data->non_brand_new_gross_bilable_sale,
                'non_brand_net_bilable_sale' => $data->non_brand_net_bilable_sale,
                'non_brand_new_net_bilable_sale' => $data->non_brand_new_net_bilable_sale,
                'penalty' => $data->penalty,
                'new_penalty' => $data->new_penalty,
                'totalcost' => $data->totalcost,
                'new_totalcost' => $data->new_totalcost,
                'previous_dues' => $data->previous_dues,
                'new_previous_dues' => $data->new_previous_dues,
                'bill_period' => $bill_period,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }
    public function updateFranchiseSummary($note_id, $data, $bill_period, $user_id)
    {

        DB::table('credit_note_food_franchise_summary')
            ->where('credit_note_id', $note_id)
            ->update(
                [
                    'gross_comm_percent' => $data->gross_comm_percent,
                    'waiver_comm_percent' => $data->waiver_comm_percent,
                    'net_comm_percent' => $data->net_comm_percent,
                    'new_gross_comm_percent' => $data->new_gross_comm_percent,
                    'new_waiver_comm_percent' => $data->new_waiver_comm_percent,
                    'new_net_comm_percent' => $data->new_net_comm_percent,
                    'gross_comm_amt' => $data->gross_comm_amt,
                    'waiver_comm_amt' => $data->waiver_comm_amt,
                    'net_comm_amt' => $data->net_comm_amt,
                    'new_gross_comm_amt' => $data->new_gross_comm_amt,
                    'new_waiver_comm_amt' => $data->new_waiver_comm_amt,
                    'new_net_comm_amt' => $data->new_net_comm_amt,
                    'gross_bilable_sale' => $data->gross_bilable_sale,
                    'new_gross_bilable_sale' => $data->new_gross_bilable_sale,
                    'net_bilable_sale' => $data->net_bilable_sale,
                    'new_net_bilable_sale' => $data->new_net_bilable_sale,
                    'penalty' => $data->penalty,
                    'new_penalty' => $data->new_penalty,
                    'totalcost' => $data->totalcost,
                    'new_totalcost' => $data->new_totalcost,
                    'previous_dues' => $data->previous_dues,
                    'new_previous_dues' => $data->new_previous_dues,
                    'bill_period' => $bill_period,
                    'last_update_by' => $user_id
                ]
            );
    }
}
