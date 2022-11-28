<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Log;

class FranchiseSale extends Model
{

    protected $table = 'invoice_food_franchise_sales';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    static function getFoodFranchiseList()
    {
        $retObj = DB::table('franchise')
            ->where('enable_franchise_sale', 1)
            ->where('is_active', 1)
            ->select(DB::raw('*'))
            ->get();
        return $retObj;
    }

    static function getMasterList($table, $merchant_id)
    {
        $retObj = DB::table($table)
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->select(DB::raw('*'))
            ->get();
        return $retObj;
    }

    static function getSaleSummary($customer_id)
    {
        $retObj = DB::table('invoice_food_franchise_sales')
            ->where('customer_id', $customer_id)
            ->where('is_active', 1)
            ->where('status', 0)
            ->where('date', '<>', date('Y-m-d'))
            ->select(DB::raw('sum(gross_sale) as sum_gross_sale,sum(tax) as sum_tax,sum(billable_sale) as sum_billable_sale,sum(gross_sale_online) as sum_gross_sale_online,sum(tax_online) as sum_tax_online,sum(billable_sale_online) as sum_billable_sale_online,sum(non_brand_gross_sale) as sum_non_brand_gross_sale,sum(non_brand_tax) as sum_non_brand_tax,sum(non_brand_billable_sale) as sum_non_brand_billable_sale,sum(non_brand_gross_sale_online) as sum_non_brand_gross_sale_online,sum(non_brand_tax_online) as sum_non_brand_tax_online,sum(non_brand_billable_sale_online) as sum_non_brand_billable_sale_online,sum(delivery_partner_commission) as sum_delivery_partner_commission,sum(non_brand_delivery_partner_commission) as sum_non_brand_delivery_partner_commission,min(date) as start_date,max(date) as end_date'))
            ->first();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function updateFranchiseSales($payment_request_id, $customer_id)
    {
        try {
            DB::table('invoice_food_franchise_sales')
                ->where('customer_id', $customer_id)
                ->where('is_active', 1)
                ->where('status', 0)
                ->where('date', '<>', date('Y-m-d'))
                ->update([
                    'status' => 1,
                    'payment_request_id' => $payment_request_id
                ]);
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }

    static function getFranchiseConfig($merchant_id)
    {
        $retObj = DB::table('merchant_config_data')
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->where('key', 'FRANCHISE_INVOICE_DETAILS')
            ->select(DB::raw('value'))
            ->first();
        if (!empty($retObj)) {
            return json_decode($retObj->value);
        } else {
            return false;
        }
    }


    public function saveInvoice($merchant_id, $user_id, $customer_id, $invoice_number, $template_id, $values, $ids, $billdate, $duedate, $cyclename, $narrative, $amount, $tax, $previous_dues, $plugin,  $invoice_type = 1, $notify = 0)
    {
        try {
            $retObj = DB::select("call `insert_invoicevalues`('$merchant_id','$user_id','$customer_id','$invoice_number','$template_id','$values','$ids','$billdate','$duedate','$cyclename','$narrative',$amount,$tax,$previous_dues,0,0,0,0,$notify,0,0,0,null,'system',$invoice_type,1,0,0,'$plugin',0,1,0,'INR',null,1);");
            return $retObj[0];
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }

    public function saveTax($payment_request_id, $tax_id, $tax_percent, $tax_applicable, $tax_amt, $tax_detail_id, $user_id)
    {
        try {
            $retObj = DB::select("call `save_invoice_tax`('$payment_request_id','$tax_id','$tax_percent','$tax_applicable','$tax_amt','$tax_detail_id','$user_id',0,0 );");
            return $retObj[0];
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }

    public function saveInvoiceSummary(
        $payment_request_id,
        $gross_sale,
        $net_sale,
        $gross_sale_online,
        $net_sale_online,
        $non_brand_gross_sale,
        $non_brand_net_sale,
        $non_brand_gross_sale_online,
        $non_brand_net_sale_online,
        $fee_percent,
        $waiver_percent,
        $net_percent,
        $non_brand_fee_percent,
        $non_brand_waiver_percent,
        $non_brand_net_percent,
        $gross_fee,
        $waiver_fee,
        $net_fee,
        $non_brand_gross_fee,
        $non_brand_waiver_fee,
        $non_brand_net_fee,
        $delivery_partner_commision,
        $non_brand_delivery_partner_commision,
        $penalty,
        $bill_period,
        $user_id
    ) {
        try {
            $id = DB::table('invoice_food_franchise_summary')->insertGetId(
                [
                    'payment_request_id' => $payment_request_id,
                    'gross_sale' => $gross_sale,
                    'net_sale' => $net_sale,
                    'gross_sale_online' => $gross_sale_online,
                    'net_sale_online' => $net_sale_online,
                    'non_brand_gross_sale' => $non_brand_gross_sale,
                    'non_brand_net_sale' => $non_brand_net_sale,
                    'non_brand_gross_sale_online' => $non_brand_gross_sale_online,
                    'non_brand_net_sale_online' => $non_brand_net_sale_online,
                    'commision_fee_percent' => $fee_percent,
                    'commision_waiver_percent' => $waiver_percent,
                    'commision_net_percent' => $net_percent,
                    'non_brand_commision_fee_percent' => $non_brand_fee_percent,
                    'non_brand_commision_waiver_percent' => $non_brand_waiver_percent,
                    'non_brand_commision_net_percent' => $non_brand_net_percent,

                    'delivery_partner_commision' => $delivery_partner_commision,
                    'non_brand_delivery_partner_commision' => $non_brand_delivery_partner_commision,

                    'gross_fee' => $gross_fee,
                    'waiver_fee' => $waiver_fee,
                    'net_fee' => $net_fee,
                    'non_brand_gross_fee' => $non_brand_gross_fee,
                    'non_brand_waiver_fee' => $non_brand_waiver_fee,
                    'non_brand_net_fee' => $non_brand_net_fee,
                    'penalty' => $penalty,
                    'bill_period' => $bill_period,
                    'created_by' => $user_id,
                    'last_update_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s')
                ]
            );
            return $id;
        } catch (Exception $e) {
            dd($e);
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }


    public function getInvoiceReport($customer_id, $merchant_id, $from_date, $to_date)
    {
        $retObj = DB::table('payment_request as p')
            ->select(DB::raw('p.payment_request_id,p.bill_date,p.due_date,p.invoice_number,p.payment_request_status,p.basic_amount,p.invoice_total,c.first_name,c.last_name,c.customer_code,c.gst_number,c.state,c.email,
            s.commision_fee_percent,s.commision_waiver_percent,s.commision_net_percent,s.gross_sale,s.net_sale,s.waiver_fee,s.gross_fee,s.net_fee,s.penalty,p.previous_due,s.bill_period,c.customer_id,c.merchant_id'))
            ->join('customer as c', 'p.customer_id', '=', 'c.customer_id')
            ->join('invoice_food_franchise_summary as s', 'p.payment_request_id', '=', 's.payment_request_id')
            ->where('p.is_active', 1)
            ->where('p.payment_request_status', '<>', 3)
            ->where('p.merchant_id', $merchant_id)
            ->where('p.customer_id', $customer_id)
            ->whereDate('p.bill_date', '>=', $from_date)
            ->whereDate('p.bill_date', '<=', $to_date);
        $data = $retObj->get();
        return $data;
    }

    public function getCustomerColumnId($merchant_id, $column_name)
    {
        $retObj = DB::table('customer_column_metadata')
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->where('column_name', $column_name)
            ->select(DB::raw('column_id'))
            ->first();
        if (!empty($retObj)) {
            return $retObj->column_id;
        } else {
            return false;
        }
    }

    public function getCustomerColumnValue($customer_id, $column_id)
    {
        $retObj = DB::table('customer_column_values')
            ->where('customer_id', $customer_id)
            ->where('is_active', 1)
            ->where('column_id', $column_id)
            ->select(DB::raw('value'))
            ->first();
        if (!empty($retObj)) {
            return $retObj->value;
        } else {
            return false;
        }
    }

    public function getInvoiceTax($payment_request_id)
    {
        $retObj = DB::table('invoice_tax')
            ->where('payment_request_id', $payment_request_id)
            ->where('is_active', 1)
            ->select(DB::raw('tax_amount'))
            ->first();
        if (!empty($retObj)) {
            return $retObj->tax_amount;
        } else {
            return 0;
        }
    }

    public function getInvoiceCreditNoteSummary($customer_id, $merchant_id, $invoice_number)
    {
        $retObj = DB::table('credit_debit_note')
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->where('customer_id', $customer_id)
            ->where('invoice_no', $invoice_number)
            ->select(DB::raw('id'))
            ->first();
        if (!empty($retObj)) {
            $retObjSummary = DB::table('credit_note_food_franchise_summary')
                ->where('credit_note_id', $retObj->id)
                ->where('is_active', 1)
                ->select(DB::raw('*'))
                ->first();
            return $retObjSummary;
        } else {
            return false;
        }
    }

    public static function saveSalesDeatails($array)
    {
        DB::table('invoice_franchise_sales_details')->insertGetId(
            $array
        );
    }
}
