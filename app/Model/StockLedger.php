<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use DB;
use App\Libraries\Encrypt;

class StockLedger extends Model
{
    protected $table = 'stock_ledger';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
    protected static $logFillable = true;
    protected $guarded = []; 


    public function saveStockLedger($saveStockLedger=null) {
        $saveStockLedger['created_date'] = Carbon::now();
        $saveStockLedger['last_update_date'] = Carbon::now();
        $saveStockLedger['created_by'] =  (isset($saveStockLedger['created_by']) && $saveStockLedger['created_by'] != '') ? $saveStockLedger['created_by'] : Auth::id();
        $saveStockLedger['last_update_by'] = (isset($saveStockLedger['last_update_by']) && $saveStockLedger['last_update_by'] != '') ? $saveStockLedger['last_update_by'] : Auth::id();
        
        $savedQuery = StockLedger::create($saveStockLedger);
        return $savedQuery->id;
    }

    public function getProductStockLedger($product_id) {
        //$getStockLedger = StockLedger::where('product_id', $product_id)->where('is_active', 1)->get();

        $getStockLedger = DB::table('stock_ledger')
            ->join('merchant_product', 'merchant_product.product_id', '=', 'stock_ledger.product_id')
            ->select('stock_ledger.*', 'merchant_product.product_name')
            ->where('stock_ledger.product_id', $product_id)
            ->where('stock_ledger.is_active', 1)
            ->get();

        if (!empty($getStockLedger)) {
            foreach ($getStockLedger as $sk => $sval) {
                if ($sval->reference_type == 2) {
                    $getExpenseData = DB::table('expense')->select('expense_id', 'expense_no')->where('expense_id', $sval->reference_id)->first();
                    $getStockLedger[$sk]->reference_no = $getExpenseData->expense_no;
                    $getStockLedger[$sk]->reference_link = '/merchant/expense/view/' . Encrypt::encode($getExpenseData->expense_id);
                } else if ($sval->reference_type == 3) {
                    $getInvoiceData = DB::table('invoice_column_values')->select('invoice_id', 'value', 'payment_request_id')->where('payment_request_id', $sval->reference_id)->first();
                    $getStockLedger[$sk]->reference_no = (!empty($getInvoiceData->value) && isset($getInvoiceData->value)) ? $getInvoiceData->value : $sval->reference_id;
                    $getStockLedger[$sk]->reference_link = '/merchant/paymentrequest/view/' . Encrypt::encode($sval->reference_id);
                } else {
                    if ($sval->reference_type == 1) {
                        $getStockLedger[$sk]->reference_no = '';
                        $getStockLedger[$sk]->reference_link = '';
                    }
                }
            }
        }
        return $getStockLedger;
    }

    public function checkProductStockLedger($product_id=null) {
        $getProductStkDetail = StockLedger::select('id','product_id','amount','quantity')->where('product_id',$product_id)->where('reference_type', 1)->where('is_active',1)->first();
        return $getProductStkDetail;
    }

}
