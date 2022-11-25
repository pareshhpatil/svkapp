<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Model\FranchiseSale;
use Exception;
use Log;

class FoodFranchiseSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'franchise:sale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch daily sale from Petpooja';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $list = FranchiseSale::getFoodFranchiseList();
            $date = date('Y-m-d', strtotime("-1 days"));
            $file_date = date('Ymd', strtotime($date));
            $merchant_data = array();
            foreach ($list as $franchise) {
                if (!isset($merchant_data[$franchise->merchant_id])) {
                    $merchant_data[$franchise->merchant_id]['config'] = FranchiseSale::getFranchiseConfig($franchise->merchant_id);
                    $delivery_partners  = FranchiseSale::getMasterList('food_delivery_partner_comission', $franchise->merchant_id);
                    $food_delivery_partners = array();
                    if (!empty($delivery_partners)) {
                        foreach ($delivery_partners as $row) {
                            $food_delivery_partners[strtolower($row->name)] = array('commission' => $row->commission, 'gst' => $row->gst);
                        }
                        $merchant_data[$franchise->merchant_id]['delivery_partners'] = $food_delivery_partners;
                    }
                }
                $config = $merchant_data[$franchise->merchant_id]['config'];
                $delivery_partners = $merchant_data[$franchise->merchant_id]['delivery_partners'];
                $s3_folder = $config->s3_folder;
                $file_name = $franchise->franchise_code . '_' . $file_date . '.json';
                $exist = Storage::disk('s3_petpooja')->exists($s3_folder . '/new/' . $file_name);
                $sales = array();
                $sales['base_price'] = 0;
                $sales['tax'] = 0;
                $sales['total_price'] = 0;
                $sales['base_price_online'] = 0;
                $sales['tax_online'] = 0;
                $sales['total_price_online'] = 0;
                $sales['delivery_partner_commission'] = 0;
                $sales['non_brand_base_price'] = 0;
                $sales['non_brand_tax'] = 0;
                $sales['non_brand_total_price'] = 0;
                $sales['non_brand_base_price_online'] = 0;
                $sales['non_brand_tax_online'] = 0;
                $sales['non_brand_total_price_online'] = 0;
                $sales['non_brand_delivery_partner_commission'] = 0;

                if ($exist) {
                    $file = Storage::disk('s3_petpooja')->get($s3_folder . '/new/' . $file_name);
                    $array = json_decode($file);
                    foreach ($array as $row) {
                        $sales = $this->calculateSale($row, $sales, $config, $delivery_partners);
                    }
                }
                $this->saveFranchiseSale($sales, $franchise, $date);
                if ($exist) {
                    Storage::disk('s3_petpooja')->move($s3_folder . '/new/' . $file_name, $s3_folder . '/processed/' . $file_name);
                }
            }
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }

    private function calculateSale($row, $sales, $config, $delivery_partners)
    {
        $order = $row->Order;
        $sale_p = [];
        $sale_p['invoice_id'] = $order->invoice_id;
        $sale_p['date'] = $order->created_date;
        $sale_p['store_id'] = $order->restaurant_id;
        if ($order->status == 1) {
            if ($config->sale_type == 2) {
                #Chettys calculation for brand non brand
                $items = $row->OrderItem;
                $order_info = json_decode($order->extra_data);
                $packaging_charge =  ($order->packing_charge > 0) ? $order->packing_charge : 0;
                $order_base_price = 0;
                $order_tax = 0;
                $order_non_brand_base_price = 0;
                $order_non_brand_tax = 0;
                $order_delivery_partner_commission = 0;
                $order_non_brand_delivery_partner_commission = 0;
                foreach ($items as $item) {
                    if ($item->group_category_id > 0) {
                        $discount = ($item->total_discount > 0) ? $item->total_discount : 0;
                        $tax = ($item->total_tax > 0) ? $item->total_tax : 0;
                        $total_price = ($item->total > 0) ? $item->total : 0;
                        $base_price = $total_price - $discount;
                        if ($config->non_brand_group_id == $item->group_category_id) {
                            $order_non_brand_base_price = $order_non_brand_base_price + $base_price;
                            $order_non_brand_tax = $order_non_brand_tax + $tax;
                        } else {
                            $order_base_price = $order_base_price + $base_price;
                            $order_tax = $order_tax + $tax;
                        }
                    }
                }

                if ($packaging_charge > 0) {
                    $order_non_brand_base_price = $order_non_brand_base_price + $packaging_charge;
                }
                $delivery_partner = (isset($order->custom_payment_type)) ? strtolower($order->custom_payment_type) : '';
                if (isset($delivery_partners[$delivery_partner])) {
                    $order_non_brand_delivery_partner_commission = $order_non_brand_base_price * $delivery_partners[$delivery_partner]['commission'] / 100;
                    $tax = $order_non_brand_delivery_partner_commission * $delivery_partners[$delivery_partner]['gst'] / 100;
                    // $order_non_brand_base_price = $order_non_brand_base_price - $order_non_brand_delivery_partner_commission - $tax;
                    $order_non_brand_delivery_partner_commission = $order_non_brand_delivery_partner_commission + $tax;

                    $order_non_brand_delivery_partner_commission_tax = $order_non_brand_tax * $delivery_partners[$delivery_partner]['commission'] / 100;
                    $tax = $order_non_brand_delivery_partner_commission_tax * $delivery_partners[$delivery_partner]['gst'] / 100;
                    //$order_non_brand_tax = $order_non_brand_tax - $order_non_brand_delivery_partner_commission_tax - $tax;
                    $order_non_brand_delivery_partner_commission = $order_non_brand_delivery_partner_commission + $order_non_brand_delivery_partner_commission_tax + $tax;

                    $order_delivery_partner_commission = $order_base_price * $delivery_partners[$delivery_partner]['commission'] / 100;
                    $tax = $order_delivery_partner_commission * $delivery_partners[$delivery_partner]['gst'] / 100;
                    //$order_base_price = $order_base_price - $order_delivery_partner_commission - $tax;
                    $order_delivery_partner_commission = $order_delivery_partner_commission + $tax;

                    $order_delivery_partner_commission_tax = $order_tax * $delivery_partners[$delivery_partner]['commission'] / 100;
                    $tax = $order_delivery_partner_commission_tax * $delivery_partners[$delivery_partner]['gst'] / 100;
                    // $order_tax = $order_tax - $order_delivery_partner_commission_tax - $tax;
                    $order_delivery_partner_commission = $order_delivery_partner_commission + $order_delivery_partner_commission_tax + $tax;


                    $sales['non_brand_base_price_online'] = $sales['non_brand_base_price_online'] + $order_non_brand_base_price;
                    $sales['non_brand_tax_online'] = $sales['non_brand_tax_online'] + $order_non_brand_tax;
                    $sales['non_brand_total_price_online'] = $sales['non_brand_total_price_online'] + $order_non_brand_base_price + $order_non_brand_tax;
                    $sales['base_price_online'] = $sales['base_price_online'] + $order_base_price;
                    $sales['tax_online'] = $sales['tax_online'] + $order_tax;
                    $sales['total_price_online'] = $sales['total_price_online'] + $order_base_price + $order_tax;
                    $sales['non_brand_delivery_partner_commission'] = $sales['non_brand_delivery_partner_commission'] + $order_non_brand_delivery_partner_commission;
                    $sales['delivery_partner_commission'] = $sales['delivery_partner_commission'] + $order_delivery_partner_commission;


                    $sale_p['non_brand_base_price_online'] = $order_non_brand_base_price;
                    $sale_p['non_brand_tax_online'] =  $order_non_brand_tax;
                    $sale_p['non_brand_total_price_online'] =  $order_non_brand_base_price + $order_non_brand_tax;
                    $sale_p['base_price_online'] =  $order_base_price;
                    $sale_p['tax_online'] =  $order_tax;
                    $sale_p['total_price_online'] =  $order_base_price + $order_tax;
                    $sale_p['non_brand_delivery_partner_commission'] =  $order_non_brand_delivery_partner_commission;
                    $sale_p['delivery_partner_commission'] =  $order_delivery_partner_commission;

                    $sale_p['non_brand_base_price'] =  0;
                    $sale_p['non_brand_tax'] = 0;
                    $sale_p['non_brand_total_price'] = 0;
                    $sale_p['base_price'] =  0;
                    $sale_p['tax'] =  0;
                    $sale_p['total_price'] =  0;
                } else {
                    $sales['non_brand_base_price'] = $sales['non_brand_base_price'] + $order_non_brand_base_price;
                    $sales['non_brand_tax'] = $sales['non_brand_tax'] + $order_non_brand_tax;
                    $sales['non_brand_total_price'] = $sales['non_brand_total_price'] + $order_non_brand_base_price + $order_non_brand_tax;
                    $sales['base_price'] = $sales['base_price'] + $order_base_price;
                    $sales['tax'] = $sales['tax'] + $order_tax;
                    $sales['total_price'] = $sales['total_price'] + $order_base_price + $order_tax;

                    $sale_p['non_brand_base_price_online'] = 0;
                    $sale_p['non_brand_tax_online'] =  0;
                    $sale_p['non_brand_total_price_online'] = 0;
                    $sale_p['base_price_online'] = 0;
                    $sale_p['tax_online'] =  0;
                    $sale_p['total_price_online'] =  0;
                    $sale_p['non_brand_delivery_partner_commission'] =  0;
                    $sale_p['delivery_partner_commission'] =  0;

                    $sale_p['non_brand_base_price'] =  $order_non_brand_base_price;
                    $sale_p['non_brand_tax'] = $order_non_brand_tax;
                    $sale_p['non_brand_total_price'] =  $order_non_brand_base_price + $order_non_brand_tax;
                    $sale_p['base_price'] =  $order_base_price;
                    $sale_p['tax'] =  $order_tax;
                    $sale_p['total_price'] =  $order_base_price + $order_tax;
                }

                FranchiseSale::saveSalesDeatails($sale_p);
            } else {
                #jumboking calculation 
                $tax = ($order->tax > 0) ? $order->tax : 0;
                $total_price = ($order->total > 0) ? $order->total : 0;
                $base_price = $total_price - $tax;
                $sales['base_price'] = $sales['base_price'] + $base_price;
                $sales['tax'] = $sales['tax'] + $tax;
                $sales['total_price'] = $sales['total_price'] + $total_price;
            }
        }
        return $sales;
    }

    private function saveFranchiseSale($sales, $row, $date)
    {
        try {
            $model = new FranchiseSale();
            if ($sales['base_price'] > 0 || $sales['non_brand_base_price'] > 0) {
                $model->gross_sale = $sales['total_price'];
                $tax = round($sales['total_price'] * 100 / 105, 2);
                $tax = $sales['total_price'] - $tax;
                $model->tax =  $tax;
                $model->billable_sale = $sales['total_price'] - $tax;

                $model->non_brand_gross_sale = $sales['non_brand_total_price'];
                $tax = round($sales['non_brand_total_price'] * 100 / 105, 2);
                $tax = $sales['non_brand_total_price'] - $tax;
                $model->non_brand_tax = $tax;
                $model->non_brand_billable_sale = $sales['non_brand_total_price'] - $tax;

                $model->gross_sale_online = $sales['total_price_online'];
                $tax = round($sales['total_price_online'] * 100 / 105, 2);
                $tax = $sales['total_price_online'] - $tax;
                $model->tax_online =  $tax;
                $model->billable_sale_online = $sales['total_price_online'] - $tax;

                $model->non_brand_gross_sale_online = $sales['non_brand_total_price_online'];
                $tax = round($sales['non_brand_total_price_online'] * 100 / 105, 2);
                $tax = $sales['non_brand_total_price_online'] - $tax;
                $model->non_brand_tax_online = $tax;
                $model->non_brand_billable_sale_online = $sales['non_brand_total_price_online'] - $tax;

                $model->delivery_partner_commission = $sales['delivery_partner_commission'];
                $model->non_brand_delivery_partner_commission = $sales['non_brand_delivery_partner_commission'];
            } else {
                $model->gross_sale = $row->default_sale;
                $base_sale = round($row->default_sale * 100 / 105);
                $model->tax = $row->default_sale - $base_sale;
                $model->billable_sale = $base_sale;
            }
            $model->customer_id = $row->customer_id;
            $model->date = $date;
            $model->created_by = 'System';
            $model->last_update_by = 'System';
            $model->save();
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }
}
