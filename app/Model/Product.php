<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Validator;
use Illuminate\Validation\Rule;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'merchant_product';
    protected $primaryKey = 'product_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
    protected static $logFillable = true;
    protected $guarded = [];

    public function validateRules($request, $merchant_id = null, $product_id = null)
    {
        if ($request['goods_type'] == 'simple') {
            $validator = Validator::make($request, [
                'type' => 'required',
                //'product_name' => 'required|min:4|max:100|unique:merchant_product,product_name,'. $product_id .',product_id',
                'product_name' => ['required', 'min:2', 'max:500', Rule::unique('merchant_product', 'product_name')->where('merchant_id', $merchant_id)->where('is_active', 1)->ignore($product_id, 'product_id')],
                'price' => 'required|numeric|gt:-1',
                'sac_code' => 'nullable|numeric|digits_between:3,8',
                'sku' => 'nullable|min:2|max:45',
                'purchase_cost' => 'nullable|numeric',
                'purchase_info' => 'max:100',
                'sale_info' => 'max:100',
                'available_stock' => 'nullable|required_if:has_stock_keeping,1|numeric',
                'minimum_stock' => 'nullable|numeric'
            ], ['required_if' => 'Available stock is required if you want to add stock keeping data']);
        } else {
            $validator = Validator::make($request, [
                'type' => 'required',
                //'product_name' => 'required|min:4|max:100|unique:merchant_product,product_name,'. $product_id .',product_id',
                'product_name' => ['required', 'min:2', 'max:500', Rule::unique('merchant_product', 'product_name')->where('merchant_id', $merchant_id)->where('is_active', 1)->where('parent_id', 0)->ignore($product_id, 'product_id')],
                'price.*' => 'required|numeric|gt:-1',
                'sac_code' => 'nullable|numeric|digits_between:3,8',
                'sku.*' => 'nullable|min:2|max:45',
                'purchase_cost.*' => 'nullable|numeric',
                'purchase_info' => 'max:100',
                'sale_info' => 'max:100',
                'available_stock.*' => 'nullable|required_if:has_stock_keeping,1|numeric',
                'minimum_stock.*' => 'nullable|numeric'
            ], ['required_if' => 'Available stock is required if you want to add stock keeping data']);
        }
        return $validator;
    }

    public function saveProduct($saveProduct = null)
    {
        if ($saveProduct['type'] == 'Service') {
            $saveProduct['goods_type'] = NULL;
        }
        $saveProduct['product_name'] = ucwords(strtolower($saveProduct['product_name']));
        $saveProduct['minimum_stock'] = (isset($saveProduct['minimum_stock']) && $saveProduct['minimum_stock'] != '') ? $saveProduct['minimum_stock'] : 0;
        $saveProduct['available_stock'] = (isset($saveProduct['available_stock']) && $saveProduct['available_stock'] != '') ? $saveProduct['available_stock'] : 0;
        $saveProduct['price'] = (isset($saveProduct['price']) && $saveProduct['price'] != '') ? $saveProduct['price'] : 0;
        $saveProduct['created_date'] = Carbon::now();
        $saveProduct['last_update_date'] = Carbon::now();
        $saveProduct['created_by'] = (isset($saveProduct['created_by']) && $saveProduct['created_by'] != '') ? $saveProduct['created_by'] : Auth::id();
        $saveProduct['last_update_by'] = (isset($saveProduct['last_update_by']) && $saveProduct['last_update_by'] != '') ? $saveProduct['last_update_by'] : Auth::id();

        $savedQuery = Product::create($saveProduct);

        return $savedQuery->product_id;
    }

    public function updateProduct($saveProduct = null, $product_id = null)
    {
        if (isset($saveProduct['product_name']) && !empty($saveProduct['product_name'])) {
            $saveProduct['product_name'] = ucwords(strtolower($saveProduct['product_name']));
        }
        $updatedQuery = Product::where('product_id', $product_id)->update($saveProduct);
        return $product_id;
    }

    public function getProducts($merchant_id = null, $product_id = null)
    {
        // $getProducts = Product::select('product_id','product_name')
        // ->where('merchant_id', $merchant_id)
        // ->where('is_active',1)
        // ->whereIn('type',['Goods','Service'])
        // ->where('parent_id','!=',0)
        // ->orWhere('product_id',$product_id)
        // ->pluck('product_name', 'product_id')->toArray();

        $product_list =  DB::select("SELECT 
        product_id,product_name FROM merchant_product WHERE
            merchant_id = '$merchant_id' and
            is_active = 1 and (type = 'Service' or goods_type = 'simple' or
            (goods_type = 'variable' and  parent_id != 0)) or product_id = '$product_id'
        ");
        $products = array();
        if (!empty($product_list)) {
            foreach ($product_list as $pr) {
                $pr->product_name = str_replace("'", "", $pr->product_name);
                $products[$pr->product_id] = $pr->product_name;
            }
        }
        return $products;
    }

    public function getProductDetail($product_id = null, $merchant_id = null)
    {
        $getProductDetail = Product::select('product_id', 'product_name', 'type', 'sac_code', 'purchase_cost', 'price', 'mrp', 'product_expiry_date', 'product_number', 'unit_type', 'gst_percent')->where('product_id', $product_id)->where('merchant_id', $merchant_id)->first();
        return $getProductDetail;
    }

    public function getVariationProducts($parent_id = null, $merchant_id = null, $count = 0)
    {
        if ($count == 1) {
            $getVariationProducts =  DB::select("SELECT
            MAX(price) AS max_price,
            MIN(price) AS min_price,
            Count(product_id) AS variations,
            SUM(available_stock) AS total_stock
            FROM
                merchant_product
            WHERE
                merchant_id = '$merchant_id' and
                is_active = 1 and
                parent_id = '$parent_id'
            Group by parent_id
            ");
        } else {
            $getVariationProducts = Product::select('product_id', 'product_name', 'sku', 'product_image', 'product_expiry_date', 'price', 'mrp', 'purchase_cost', 'available_stock', 'minimum_stock', 'has_stock_keeping')
                ->where('merchant_id', $merchant_id)
                ->where('is_active', 1)
                ->where('parent_id', $parent_id)->get();
        }
        return $getVariationProducts;
    }

    public function getAllParentProducts($merchant_id = null)
    {
        // $getAllproducts = Product::select('product_id', 'product_name', 'type','goods_type','sac_code', 'price', 'unit_type', 'gst_percent', 'available_stock', 'has_stock_keeping','is_active')
        // ->where('merchant_id', $merchant_id)
        // ->where('is_active', 1)
        // ->where('goods_type','simple')
        // ->orwhereNull('goods_type')
        // ->orWhere('parent_id',0)->get();   //->whereIn('type',['Goods','Service'])
        // return $getAllproducts;

        $getAllproducts =  DB::select("SELECT 
        product_id, product_name,parent_id,type,goods_type,sac_code, price, unit_type, gst_percent,available_stock,has_stock_keeping,is_active
        FROM
            merchant_product
        WHERE
            merchant_id = '$merchant_id' and
            is_active = 1 and (type = 'Service' or goods_type = 'simple' or
            (goods_type = 'variable' and 
            parent_id = 0))
        ");
        return $getAllproducts;
    }

    public function checkWCProductExist($wc_post_id = null, $merchant_id = null)
    {
        $getProductDetail = Product::select('product_id', 'product_image')->where('wc_post_id', $wc_post_id)->where('merchant_id', $merchant_id)->where('is_active', 1)->first();
        return $getProductDetail;
    }

    public function inventroy_dashboard_statistics($merchant_id = null)
    {
        $getDashboardStatistics =  DB::select("SELECT
            Count(available_stock) AS items_in_stock,
            SUM(available_stock) AS total_stock,
            SUM(price*available_stock) AS total_stock_value
            FROM
                merchant_product
            WHERE
                merchant_id = '$merchant_id' and
                is_active = 1 and
                type = 'Goods'
        ");

        return $getDashboardStatistics;
    }


    public function stock_status($merchant_id = null, $days = null)
    {
        if ($days == 'last_7_days') {
            $filter_days = "DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL 7 day )";
        } else if ($days == 'this_month') {
            $filter_days = "MONTH(created_date) = MONTH(now()) and YEAR(created_date) = YEAR(now())";
        } else if ($days == 'last_6_months') {
            $filter_days = "DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL 6 month )";
        } else if ($days == 'current_year') {
            $filter_days = "DATE_FORMAT(created_date, '%Y') = YEAR (curdate());";
        } else if ($days == 'last_year') {
            $filter_days = "YEAR(created_date) = YEAR(DATE_SUB(curdate(), INTERVAL 1 YEAR))";
        } else {
            $filter_days = "DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL 7 day )";
        }

        $getInstock = DB::select("SELECT
                    Count(product_id) AS in_stock FROM merchant_product
                    WHERE
                        merchant_id = '$merchant_id' and
                        is_active = 1 and
                        type = 'Goods' and
                        available_stock > 0 and
                        $filter_days
                    ");

        $getOutofstock = DB::select("SELECT
                Count(product_id) AS out_of_stock FROM merchant_product
                WHERE
                    merchant_id = '$merchant_id' and
                    is_active = 1 and
                    type = 'Goods' and
                    available_stock < 0 and
                    $filter_days
                ");

        $getlowstock = DB::select("SELECT
                Count(product_id) AS low_stock FROM merchant_product
                WHERE
                    merchant_id = '$merchant_id' and
                    is_active = 1 and
                    type = 'Goods' and
                    minimum_stock > available_stock and 
                    $filter_days
                ");

        $stock_status['in_stock'] = $getInstock[0]->in_stock;
        $stock_status['out_of_stock'] = $getOutofstock[0]->out_of_stock;
        $stock_status['low_stock'] = $getlowstock[0]->low_stock;
        return $stock_status;
    }

    public function get_time_wise_sales_status($merchant_id = null, $days = null)
    {
        if ($days == 'last_7_days') {
            $created_date = "DATE_FORMAT(created_date,'%Y-%m-%d')";
            $filter_days = "DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL 7 day )";
        } else if ($days == 'this_month') {
            $created_date = "DATE_FORMAT(created_date,'%Y-%m-%d')";
            $filter_days = "MONTH(created_date) = MONTH(now()) and YEAR(created_date) = YEAR(now())";
        } else if ($days == 'last_6_months') {
            $created_date = "DATE_FORMAT(created_date,'%M')";
            $filter_days = "DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL 6 month )";
        } else if ($days == 'current_year') {
            $created_date = "DATE_FORMAT(created_date,'%M')";
            $filter_days = "DATE_FORMAT(created_date, '%Y') = YEAR (curdate())";
        } else if ($days == 'last_year') {
            $created_date = "DATE_FORMAT(created_date,'%Y-%M')";
            $filter_days = "YEAR(created_date) = YEAR(DATE_SUB(curdate(), INTERVAL 1 YEAR))";
        } else {
            $created_date = "DATE_FORMAT(created_date,'%Y-%m-%d')";
            $filter_days = "DATE_FORMAT(created_date,'%Y-%m-%d') > DATE_SUB(curdate(),INTERVAL 7 day )";
        }

        $getTimeWiseSales = DB::select("SELECT
                    sum(grand_total) as time_wise_sales, $created_date as created_date
                    FROM
                        payment_request
                    WHERE
                        merchant_id = '$merchant_id' and
                        is_active = 1 and
                        (payment_request_status=1 or payment_request_status=2) and  
                        $filter_days Group by $created_date
                    ");

        //print_r($getTimeWiseSales);

        if ($days == 'last_7_days') {
            $m = date("m"); // Month value
            $de = date("d"); //today's date
            $y = date("Y"); // Year value
            //echo date('d-m-y:D', mktime(0,0,0,$m,($de-0),$y)); 
            for ($i = 0; $i <= 6; $i++) {
                $dt = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
                if ($i == 0) {
                    $dt = date('D', mktime(0, 0, 0, $m, ($de - $i), $y)) . '(Today)';
                }
                $time_period_data[date('Y-m-d', mktime(0, 0, 0, $m, ($de - $i), $y))]['value'] = 0;
                $time_period_data[date('Y-m-d', mktime(0, 0, 0, $m, ($de - $i), $y))]['axis'] = $dt;
            }
            ksort($time_period_data);
        } else if ($days == 'this_month') {
            $end = date('Y-m-d');
            $dates = array(date('Y-m-01'));
            while (end($dates) < $end) {
                $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
            }
            foreach ($dates as $dt) {
                $time_period_data[$dt]['value'] = 0;
                $time_period_data[$dt]['axis'] = date_format(date_create($dt), 'd-m-y');
            }
        } else if ($days == 'last_6_months') {
            $dt = strtotime(date('Y-m-01'));
            for ($j = 5; $j >= 0; $j--) {
                $month = date("F", strtotime(" -$j month", $dt));
                $time_period_data[$month]['value'] = 0;
                $time_period_data[$month]['axis'] = $month;
            }
        } else if ($days == 'current_year') {
            $dt = strtotime(date('Y-m-01'));
            $m = date("m"); // Month value
            $totalMonths = $m - 1;
            for ($j = $totalMonths; $j >= 0; $j--) {
                $month = date("F", strtotime(" -$j month", $dt));
                $time_period_data[$month]['value'] = 0;
                $time_period_data[$month]['axis'] = $month;
            }
        } else if ($days == 'last_year') {
            $year = date('Y') - 1;
            $last_year_date = $year . '-01-01';
            $dt = strtotime($last_year_date);

            for ($j = 0; $j <= 11; $j++) {
                $month = date("F", strtotime(" $j month", $dt));
                $time_period_data[$month]['value'] = 0;
                $time_period_data[$month]['axis'] = $month;
            }
        } else {
            $m = date("m"); // Month value
            $de = date("d"); //today's date
            $y = date("Y"); // Year value
            //echo date('d-m-y:D', mktime(0,0,0,$m,($de-0),$y)); 
            for ($i = 0; $i <= 6; $i++) {
                $dt = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
                if ($i == 0) {
                    $dt = date('D', mktime(0, 0, 0, $m, ($de - $i), $y)) . '(Today)';
                }
                $time_period_data[date('Y-m-d', mktime(0, 0, 0, $m, ($de - $i), $y))]['value'] = 0;
                $time_period_data[date('Y-m-d', mktime(0, 0, 0, $m, ($de - $i), $y))]['axis'] = $dt;
            }
            ksort($time_period_data);
        }

        foreach ($getTimeWiseSales as $sales) {
            if (array_key_exists($sales->created_date, $time_period_data)) {
                $time_period_data[$sales->created_date]['value'] = $sales->time_wise_sales;
            }
        }

        return $time_period_data;
    }
}
