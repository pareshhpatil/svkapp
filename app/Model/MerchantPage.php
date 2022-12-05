<?php

namespace App\Model;

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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class MerchantPage extends ParentModel
{
    public function getIndustry($industry_type)
    {
        $retObj = DB::table('config')
            ->select(DB::raw('*'))
            ->where('config_key', $industry_type)
            ->where('config_type', 'industry_type')
            ->first();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function getMerchantWebsite($merchant_id)
    {
        $retObj = DB::table('website_live')
            ->select(DB::raw('merchant_domain'))
            ->where('merchant_id', $merchant_id)
            ->where('status', '>', 1)
            ->where('is_active', 1)
            ->first();
        if ($retObj != null) {
            return 'http://' . $retObj->merchant_domain;
        } else {
            return false;
        }
    }

    public function getMerchantFeeDetail($merchant_id, $currency = 'INR')
    {
        $retObj = DB::select("select pg_type,pg_val4,f.pg_id,f.fee_detail_id,f.pg_surcharge_enabled,enable_tnc from merchant_fee_detail f inner join payment_gateway g on f.pg_id=g.pg_id where f.is_active=1 
        and merchant_id='" . $merchant_id . "' and currency like '%" . $currency . "%'");
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function getMerchantBills($user_input, $type, $merchant_id)
    {
        $mybills = DB::select("call get_mybills('" . $user_input . "', '" . $type . "', '" . $merchant_id . "')");
        return $mybills;
    }

    public function getMerchantSitemap()
    {
        $retObj = DB::table('merchant as m')
            ->select(DB::raw('m.company_name,m.merchant_id,m.display_url,l.last_update_date'))
            ->join('merchant_landing as l', 'm.merchant_id', '=', 'l.merchant_id')
            ->where('l.publishable', 1)
            ->where('m.display_url', '!=', null)
            ->where('m.display_url', '!=', '')
            ->where('m.company_name', '!=', '')
            ->orderBy('l.last_update_date')
            ->get();
        return $retObj;
    }

    public function getPaymentGatewayDetails($merchant_id, $fee_id = NULL, $franchise_id = 0, $currency = 'INR')
    {
        $currency = (strlen($currency) == 3) ? $currency : 'INR';
        if ($fee_id == NULL) {

            $retObj = DB::select("select fee_detail_id,
                pg.pg_id,pg.pg_type,pg.pg_val1,pg.pg_val2,
                pg.pg_val4,pg.pg_val3,pg.req_url,
                pg.pg_val5,pg.pg_val6,pg.pg_val7,
                pg.pg_val8,pg.pg_val9,status_url,pg.type 
                from payment_gateway as pg 
                inner join merchant_fee_detail as fd on pg.pg_id=fd.pg_id 
                where fd.merchant_id='$merchant_id' 
                and franchise_id='$franchise_id' 
                and fd.is_active=1 
                and fd.currency like '%" . $currency . "%' 
                order by fd.pg_fee_val desc");
        } else {

            $retObj = DB::select("select fee_detail_id,pg.pg_id,
                pg.pg_type,pg.pg_val1,pg.pg_val2,pg.pg_val4,
                pg.pg_val3,pg.req_url,pg.pg_val5,pg.pg_val6,
                pg.pg_val7,pg.pg_val8,pg.pg_val9,status_url,pg.type 
                from payment_gateway as pg 
                inner join merchant_fee_detail as fd on pg.pg_id=fd.pg_id 
                where fd.fee_detail_id='$fee_id'");
        }
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }


    public function getXwayPaymentGatewayDetails($merchant_id, $fee_id = NULL, $franchise_id = 0, $currency = 'INR')
    {
        $currency = (strlen($currency) == 3) ? $currency : 'INR';
        if ($fee_id == NULL) {

            $retObj = DB::select("select xway_merchant_detail_id as fee_detail_id,
                pg.pg_id,pg.pg_type,pg.pg_val1,pg.pg_val2,
                pg.pg_val4,pg.pg_val3,pg.req_url,
                pg.pg_val5,pg.pg_val6,pg.pg_val7,
                pg.pg_val8,pg.pg_val9,status_url,pg.type 
                from payment_gateway as pg 
                inner join xway_merchant_detail as fd on pg.pg_id=fd.pg_id 
                where fd.merchant_id='$merchant_id' 
                and franchise_id='$franchise_id' 
                and fd.currency like '%" . $currency . "%' 
                ");
        } else {

            $retObj = DB::select("select xway_merchant_detail_id as fee_detail_id,pg.pg_id,
                pg.pg_type,pg.pg_val1,pg.pg_val2,pg.pg_val4,
                pg.pg_val3,pg.req_url,pg.pg_val5,pg.pg_val6,
                pg.pg_val7,pg.pg_val8,pg.pg_val9,status_url,pg.type 
                from payment_gateway as pg 
                inner join xway_merchant_detail as fd on pg.pg_id=fd.pg_id 
                where fd.xway_merchant_detail_id='$fee_id'");
        }
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function saveSetuResponse($data, $merchant_id, $user_id)
    {
        $datetime = date('Y-m-d H:i:s');

        $id = DB::table('pg_ret_bank11')->insertGetId(
            [
                'billerBillID' =>  (isset($data["data"]["billerBillID"])) ? $data["data"]["billerBillID"] : 'SETTLEMENT',
                'amountPaid' =>  (isset($data["data"]["amountPaid"]["value"])) ? $data["data"]["amountPaid"]["value"] : '0',
                'currencyCode' =>  (isset($data["data"]["amountPaid"]["currencyCode"])) ? $data["data"]["amountPaid"]["currencyCode"] :'INR',
                'payerVpa' =>   (isset($data["data"]["payerVpa"])) ? $data["data"]["payerVpa"] :'SETTLEMENT@SWIPEZ',
                'platformBillID' =>   (isset($data["data"]["platformBillID"])) ? $data["data"]["platformBillID"] : '',
                'receiptId' =>   (isset($data["data"]["receiptId"])) ? $data["data"]["receiptId"] :'',
                'sourceAccount_ifsc' => (isset($data["data"]["sourceAccount"]["ifsc"])) ? $data["data"]["sourceAccount"]["ifsc"] : '',
                'sourceAccount_name' => (isset($data["data"]["sourceAccount"]["name"])) ? $data["data"]["sourceAccount"]["name"] : '',
                'sourceAccount_number' => (isset($data["data"]["sourceAccount"]["number"])) ? $data["data"]["sourceAccount"]["number"] : '',
                'transactionNote' =>  (isset($data["data"]["transactionNote"])) ? $data["data"]["transactionNote"] : 'SETTLEMENT',
                'transactionId' =>  (isset($data["data"]["transactionId"])) ? $data["data"]["transactionId"] : '',
                'status' =>  (isset($data["data"]["status"])) ? $data["data"]["status"] : 'SETTLEMENT',
                'timeStamp' =>  (isset($data["timeStamp"])) ? $data["timeStamp"] : '',
                'type' =>  (isset($data["type"])) ? $data["type"] : 'SETTLE',
                'trans_unique_id' =>  (isset($data["id"])) ? $data["id"] : ''
            ]
        );

        //$this->savePaymentResponse($data, $merchant_id, $user_id);
        return true;
    }

    public function savePaymentResponse($data, $merchant_id, $user_id)
    {

        $retObj = DB::select("call `insert_payment_response`(
            'PAYMENT','" . $data['data']['billerBillID'] . "','" . $data['data']['billerBillID'] . "','" . $data["data"]["platformBillID"] . "',
            '" . $data["data"]["amountPaid"]["value"]/100 . "','SETU','UPI',
            '" . $data["data"]["transactionNote"] . "','success','Guest')");

        return  $retObj;
    }

    public function getCountryCode($country_type) {
        $retObj = DB::table('config')
            ->select(DB::raw('description'))
            ->where('config_value', $country_type)
            ->where('config_type', 'country_name')
            ->first();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }
}
