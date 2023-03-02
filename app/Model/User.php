<?php

namespace App\Model;

use App\Helpers\RuleEngine\RuleEngineManager;
use App\Libraries\Helpers;
use App\Jobs\SubDomainManagement;
use Carbon\Carbon;

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
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class User extends ParentModel
{

    public function saveOTP($mobile, $email, $otp, $user_id = null)
    {
        $id = DB::table('otp')->insertGetId(
            [
                'mobile' => $mobile,
                'email_id' => $email,
                'otp' => $otp,
                'user_id' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }

    public function validateOTP($value, $otp)
    {
        $column = (strlen($value) == 10) ? 'mobile' : 'email_id';
        $retObj = DB::table('otp')
            ->select(DB::raw('*'))
            ->where($column, $value)
            ->where('otp', $otp)
            ->where('is_active', 1)
            ->whereRaw('created_date >= DATE_SUB(NOW(),INTERVAL 400 MINUTE)')
            ->first();
        if (!empty($retObj)) {
            //$this->disableOTP($retObj->id);
            return $retObj;
        } else {
            return false;
        }
    }

    public function disableOTP($id)
    {

        DB::table('otp')
            ->where('id', $id)
            ->update([
                'is_active' => 0
            ]);
    }

    public function emailExist($email)
    {

        $retObj = DB::table('user')
            ->select(DB::raw('*'))
            ->wherein('user_status', [12, 15, 16])
            ->where('email_id', $email)
            ->first();
        if (empty($retObj)) {
            return false;
        } else {
            return $retObj;
        }
    }

    public function saveLoginToken($email, $user_id)
    {
        $token = md5(rand(1000, 9999) . time());
        DB::table('login_token')->insertGetId(
            [
                'email' => $email,
                'token' => $token,
                'user_id' => $user_id,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $token;
    }

    // Getting this to check if merchant has completed the profile details or not.
    public function getRegCity($merchant_id)
    {
        $reg_city = DB::table('merchant_billing_profile')->where('merchant_id', $merchant_id)->pluck('reg_city');
        $city = 0;
        if ($reg_city == null) {
            $city = 0;
        }
        foreach ($reg_city as $place) {
            $city = $place;
        }
        return $city;
    }

    public function getBankVerification($merchant_id)
    {
        $bank_detail = DB::table('merchant_bank_detail')->where('merchant_id', $merchant_id)->pluck('verification_status');
        if (count($bank_detail) == 0) {
            $status = 0;
        } else {
            foreach ($bank_detail as $detail) {
                $status = $detail;
            }
        }
        $status = ($status == 2) ? 1 : 0;
        return $status;
    }

    // By this function we are checking if the merchant is active for the xway payment gateway or not
    public function isXwayActive($merchant_id)
    {
        $xwayCount = DB::table('xway_merchant_detail')->where('merchant_id', $merchant_id)->count();
        if ($xwayCount == 0) {
            return false;
        } else {
            return true;
        }
    }

    // By this function we are checking if the merchant is active for the default payment gateway or not
    public function isPaymentActive($merchant_id)
    {
        $paymentGateway = DB::table('merchant_fee_detail')->where('merchant_id', $merchant_id)->where('is_active', 1)->count();
        if ($paymentGateway == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function updateTokenStatus($id, $status)
    {

        DB::table('login_token')
            ->where('id', $id)
            ->update([
                'status' => 1
            ]);
    }

    public function updateProfileStep($merchant_id, $step)
    {

        DB::table('merchant_setting')
            ->where('merchant_id', $merchant_id)
            ->update([
                'profile_step' => $step
            ]);
    }
    public function saveSignPreference($merchant_id, $online_payment, $create_invoice, $create_customer, $send_paymentlink, $bulk_invoice, $create_subscription)
    {

        $data = [
            'merchant_id' => $merchant_id,
            'online_payment' => $online_payment,
            'create_invoice' => $create_invoice,
            'create_customer' => $create_customer,
            'send_paymentlink' => $send_paymentlink,
            'bulk_invoice' => $bulk_invoice,
            'create_subscription' => $create_subscription
        ];
        $merchant = DB::table('merchant_signup_preferences')->where('merchant_id', $merchant_id)->first();
        if (!$merchant) {
            $data['created_date'] = date('Y-m-d H:i:s');
            DB::table('merchant_signup_preferences')->insertGetId(
                $data
            );
        } else {
            DB::table('merchant_signup_preferences')
                ->where('merchant_id', $merchant_id)
                ->update($data);
        }
    }

    public function updateMerchantIndustry($merchant_id, $industry_type, $employee_count, $customer_count)
    {

        DB::table('merchant')
            ->where('merchant_id', $merchant_id)
            ->update([
                'industry_type' => $industry_type,
                'employee_count' => $employee_count,
                'customer_count' => $customer_count
            ]);
    }

    public function generateUrl($url)
    {
        $exist = true;
        $int = 1;
        $newurl = $url;
        while ($exist == true) {
            $existDefaultUrl = DB::table('merchant')->where('display_url', $newurl)->first();
            if ($existDefaultUrl == null) {
                $exist = false;
            } else {
                $exist = true;
                $newurl = $url . $int;
                $int++;
            }
        }
        return $newurl;
    }

    public function setDefaultDisplayURL($merchant_id)
    {
        $merchant = DB::table('merchant')->where('merchant_id', $merchant_id)->first();
        if ($merchant->company_name != '') {
            $stripped_company_name = str_replace(' ', '', $merchant->company_name);
            $url = strtolower($stripped_company_name);
            $url = str_replace('/', '-', $url);
            $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
            if (strlen($url) > 10) {
                $url = substr($url, 0, 10);
            }
            $existDefaultUrl = DB::table('merchant')->where('display_url', $url)->first();
            if ($existDefaultUrl != null) {
                $url = $this->generateUrl($url);
            }
            DB::table('merchant')->where('merchant_id', $merchant->merchant_id)->update(['display_url' => $url]);

            $industry = DB::table('config')->where('config_key', $merchant->industry_type)->where('config_type', 'industry_type')->first();
            if ($industry == null) {
                $industry = DB::table('config')->where('config_key', 28)->where('config_type', 'industry_type')->first();
            }
            $merchant_detail = DB::table('default_industry_merch_landing')->where('industry_id', $industry->config_key)->first();
            if ($merchant_detail->industry_id == 0 || $merchant_detail->industry_id == 28) {      // if industry is 0 or null or other
                $default_banner = $merchant_detail->default_image_path;
            } else {                                                                               // if insustry is valid industry means not zero or other or null.
                $random = rand(1, 10);
                $default_banner = 'landingpage/' . $industry->config_key . '_' . $random . '.jpg';
                if (!is_file(public_path($default_banner))) {
                    $default_banner = $merchant_detail->default_image_path;
                }
            }

            $merchant_landing = $this->getTableRow('merchant_landing', 'merchant_id', $merchant_id);
            if ($merchant_landing == false) {
                DB::table('merchant_landing')->insertGetId([
                    'merchant_id' => $merchant_id,
                    'overview' => $merchant_detail->overview,
                    'terms_condition' => $merchant_detail->terms_condition,
                    'cancellation_policy' => $merchant_detail->cancellation_policy,
                    'about_us' => $merchant_detail->about_us,
                    'office_location' => $merchant_detail->office_location,
                    'contact_no' => $merchant_detail->contact_no,
                    'email_id' => $merchant_detail->email_id,
                    'logo' => 'landingpage/default-logo.png',
                    'banner' => $default_banner,
                    'booking_background' => $merchant_detail->booking_background,
                    'booking_title' => $merchant_detail->booking_title,
                    'booking_hide_menu' => $merchant_detail->booking_hide_menu,
                    'banner_text' => $merchant_detail->banner_text,
                    'banner_paragraph' => $merchant_detail->banner_paragraph,
                    'pay_my_bill_text' => $merchant_detail->pay_my_bill_text,
                    'pay_my_bill_paragraph' => $merchant_detail->pay_my_bill_paragraph,
                    'created_by' => $merchant_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $merchant_id,
                    'last_update_date' => date('Y-m-d H:i:s')
                ]);
            }

            return $url;
        }
    }

    public function getMasterLoginList($group_id)
    {

        $retObj = DB::table('master_login as l')
            ->join('user as u', 'u.user_id', '=', 'l.user_id')
            ->join('merchant as m', 'm.merchant_id', '=', 'l.merchant_id')
            ->where('l.group_id', $group_id)
            ->select(DB::raw('u.email_id,m.company_name as display_name,l.user_id,l.merchant_id'))
            ->get();
        return json_decode(json_encode($retObj), true);
    }

    public function getActiveServiceList($merchant_id, $type = 1)
    {

        $retObj = DB::table('merchant_active_apps as l')
            ->join('swipez_services as m', 'm.service_id', '=', 'l.service_id')
            ->where('l.status', 1)
            ->where('l.is_active', 1);
        if ($type == 1) {
            $retObj =  $retObj->where('l.merchant_id', $merchant_id);
        } else {
            $retObj =  $retObj->where('l.user_id', $merchant_id);
        }
        $retObj = $retObj->select(DB::raw('l.service_id,m.title'))
            ->orderBy('l.seq', 'ASC')
            ->get();
        return json_decode(json_encode($retObj), true);
    }

    public function merchantRegister($email, $first_name, $last_name, $mobile_code, $mobile, $password, $company_name, $plan_id, $campaign_id, $service_id)
    {
        $mobile_code = '+' . substr($mobile_code, 0, 4);
        $first_name = addslashes($first_name);
        $last_name = addslashes($last_name);
        $company_name = addslashes($company_name);
        $retObj = DB::select("call `merchant_register`('" . $email . "','" . $first_name . "','" . $last_name . "','" . $mobile_code . "','" . $mobile . "','" . $password . "','" . $company_name . "','" . $plan_id . "','" . $campaign_id . "',0,'" . $service_id . "');");
        return $retObj[0];
    }

    public function briqRegister($email, $first_name, $last_name, $mobile_code, $mobile, $password, $company_name, $plan_id, $campaign_id, $service_id)
    {
        $mobile_code = '+' . substr($mobile_code, 0, 4);
        $first_name = addslashes($first_name);
        $last_name = addslashes($last_name);
        $company_name = addslashes($company_name);
        $retObj = DB::select("call `briq_merchant_register`('" . $email . "','" . $first_name . "','" . $last_name . "','" . $mobile_code . "','" . $mobile . "','" . $password . "','" . $company_name . "','" . $plan_id . "','" . $campaign_id . "',0,'" . $service_id . "');");
        return $retObj[0];
    }


    public function subuserRegister($user_id, $email, $first_name, $last_name, $mobile, $password)
    {
        $first_name = addslashes($first_name);
        $last_name = addslashes($last_name);
        $retObj = DB::select("call `save_sub_merchant`('" . $user_id . "','" . $email . "','" . $first_name . "','" . $last_name . "','+91','" . $mobile . "','" . $password . "',0,0,null);");
        return $retObj[0];
    }

    public function getConfigKey($config_type, $config_value)
    {

        $retObj = DB::table('config')
            ->select(DB::raw('config_key'))
            ->where('config_type', $config_type)
            ->where('config_value', $config_value)
            ->first();
        if (!empty($retObj)) {
            return $retObj->config_key;
        } else {
            return 0;
        }
    }
    public function getPreferences($user_id)
    {
        $merchant = DB::table('preferences')->where('user_id', $user_id)->first();
        return $merchant;
    }
    public function getMerchantLandingDetails($merchant_id)
    {
        $merchant_detail = DB::table('merchant_landing')->where('merchant_id', $merchant_id)->first();
        return $merchant_detail;
    }

    public function getMerchantDetails($merchant_id)
    {
        $merchant = DB::table('merchant')->where('merchant_id', $merchant_id)->first();
        return $merchant;
    }

    public function updateCFID($merchant_id, $cf_id)
    {
        DB::table('merchant_landing')->where('merchant_id', $merchant_id)->update(['cf_id' => $cf_id]);
    }

    public function updateCFResponse($merchant_id, $cf_response)
    {
        DB::table('merchant_landing')->where('merchant_id', $merchant_id)->update(['cf_response' => $cf_response]);
    }

    public function updateCFIDCFResponse($merchant_id, $cf_response, $cf_id)
    {
        DB::table('merchant_landing')->where('merchant_id', $merchant_id)->update(['cf_id' => $cf_id, 'cf_response' => $cf_response]);
    }

    public function updateMerchantDetails($merchant_id, $partner_merchant_id, $gst_number, $pan_no, $industry_type, $entity_type, $partner_id, $merchant_domain)
    {

        DB::table('merchant')
            ->where('merchant_id', $merchant_id)
            ->update([
                'partner_merchant_id' => $partner_merchant_id,
                'gst_number' => $gst_number,
                'pan' => $pan_no,
                'partner_id' => $partner_id,
                'merchant_domain' => $merchant_domain,
                'industry_type' => $industry_type,
                'entity_type' => $entity_type
            ]);
    }

    public function updateUserDetails($user_id, $partner_user_id, $user_status)
    {

        DB::table('user')
            ->where('user_id', $user_id)
            ->update([
                'partner_user_id' => $partner_user_id,
                'user_status' => $user_status
            ]);
    }

    public function updateUserPassword($user_id, $password, $user_status)
    {

        $password = password_hash($password, PASSWORD_DEFAULT);
        DB::table('user')
            ->where('user_id', $user_id)
            ->update([
                'password' => $password,
                'user_status' => $user_status
            ]);
    }

    public function updateCompanyName($merchant_id, $company_name)
    {

        DB::table('merchant')
            ->where('merchant_id', $merchant_id)
            ->update([
                'company_name' => $company_name
            ]);
    }

    public function updateMerchantAddress($merchant_id, $user_id, $address, $city, $zipcode, $state, $country)
    {

        $address = addslashes($address);
        DB::table('merchant_billing_profile')
            ->where('merchant_id', $merchant_id)
            ->update([
                'address' => $address,
                'city' => $city,
                'zipcode' => $zipcode,
                'state' => $state,
                'country' => $country
            ]);
    }

    public function addPaymentGateway($response, $merchant_id)
    {
        $payment_gateway = DB::table('payment_gateway')->insertGetId([
            'pg_name' => 'STRIPE-' . auth()->user()->name,
            'pg_type' => 11,
            'is_active' => 1,
            'pg_val1' => $response->stripe_user_id,
            'pg_val2' => $response->refresh_token,
            'pg_val3' => 'Live',
            'nodal_settlement' => 0,
            'type' => 1,
            'ret_tname' => 'pg_ret_bank10',
            'created_by' => $merchant_id,
            'last_update_by' => $merchant_id
        ]);

        DB::table('merchant_fee_detail')->insertGetId([
            'merchant_id' => $merchant_id,
            'pg_id' => $payment_gateway,
            'swipez_fee_type' => 'F',
            'swipez_fee_val' => '0.00',
            'pg_fee_type' => 'F',
            'pg_fee_val' => '0.00',
            'pg_tax_type' => 'GST',
            'pg_tax_val' => '18.00',
            'surcharge_enabled' => 0,
            'is_active' => 1
        ]);
    }

    public function getPaymentGateway($merchant_id)
    {
        $payment_gateway = DB::table('merchant_fee_detail')->where('merchant_id', $merchant_id)->get();
    }

    public function getUserPrivileges($user_id) {
        $privilegesCollect = DB::table('briq_privileges')
            ->where('user_id', $user_id)
            ->where('is_active', 1)
            ->select(['type', 'type_id', 'access', 'rule_engine_query'])
            ->get()
            ->collect();

        $ruleEngineInvoices = $this->fetchRuleEngineInvoices($privilegesCollect);

        $customerPrivilegesArray = $ruleEngineInvoices["customer_ids"];
        $projectPrivilegesArray = $ruleEngineInvoices["project_ids"];
        $contractPrivilegesArray = $ruleEngineInvoices["contract_ids"];
        $invoicePrivilegesArray = $ruleEngineInvoices["invoice_ids"];
        
//        $customerPrivilegesCollect = clone $privilegesCollect->where('type', 'customer')
//            ->pluck('access', 'type_id');
//
//        $customerPrivilegesArray = $customerPrivilegesCollect->toArray();
//
//        $projectPrivilegesCollect = clone $privilegesCollect->where('type', 'project')
//            ->pluck('access', 'type_id');
//
//        $projectPrivilegesArray = $projectPrivilegesCollect->toArray();
//
//
//        $contractPrivilegesCollect = clone $privilegesCollect->where('type', 'contract')
//            ->pluck('access', 'type_id');
//
//        $contractPrivilegesArray = $contractPrivilegesCollect->toArray();

//        $invoicePrivilegesCollect = clone $privilegesCollect->where('type', 'invoice')
//            ->pluck('access', 'type_id');
//
//        $invoicePrivilegesArray = $invoicePrivilegesCollect->toArray();

        $orderPrivilegesCollect = clone $privilegesCollect->where('type', 'change-order')
            ->pluck('access', 'type_id');

        $orderPrivilegesArray = $orderPrivilegesCollect->toArray();

        if(!empty($customerPrivilegesArray)) {
            $projectPrivilegesArray = $this->createProjectPrivilegesAccess($customerPrivilegesArray, $projectPrivilegesArray);
        }

        if (!empty($projectPrivilegesArray)) {
            $contractPrivilegesArray = $this->createContractPrivilegesAccess($projectPrivilegesArray, $contractPrivilegesArray);
        }

        if(!empty($contractPrivilegesArray)) {
            $invoicePrivilegesArray = $this->createInvoicePrivilegesAccess($user_id, $contractPrivilegesArray, $invoicePrivilegesArray, $ruleEngineInvoices);
        }

        if(!empty($contractPrivilegesArray)) {
            $orderPrivilegesArray = $this->createOrderPrivilegesAccess($contractPrivilegesArray, $orderPrivilegesArray);
        }

        return [
            'customer_privileges' => $customerPrivilegesArray,
            'project_privileges' => $projectPrivilegesArray,
            'contract_privileges' => $contractPrivilegesArray,
            'invoice_privileges' => $invoicePrivilegesArray,
            'change_order_privileges' => $orderPrivilegesArray
        ];
    }

    public function createProjectPrivilegesAccess($customerPrivilegesArray, $projectPrivilegesArray) {

        $projectIDs = DB::table('project')
            ->where('is_active', 1)
            ->whereIn('customer_id', array_keys($customerPrivilegesArray))
            ->whereNotIn('id', array_keys($projectPrivilegesArray))
            ->select(['id', 'customer_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($projectIDs as $projectID) {
            $tempArr[$projectID->id] = $customerPrivilegesArray[$projectID->customer_id];
        }

        return $projectPrivilegesArray + $tempArr;
    }

    public function createContractPrivilegesAccess($projectPrivilegesArray, $contractPrivilegesArray) {
        $tempArr= [];

        $contractIDs = DB::table('contract')
            ->where('is_active', 1)
            ->whereIn('project_id', array_keys($projectPrivilegesArray))
            ->whereNotIn('contract_id', array_keys($contractPrivilegesArray))
            ->select(['contract_id', 'project_id'])
            ->get()
            ->toArray();

        foreach ($contractIDs as $contractID) {
            $tempArr[$contractID->contract_id] = $projectPrivilegesArray[$contractID->project_id];
        }

        return $contractPrivilegesArray + $tempArr;
    }

    public function createOrderPrivilegesAccess($contractPrivilegesArray, $orderPrivilegesArray) {
        //invoiceIDS
        //fetch contract_ids from payment_request and assign it

        $orderIDs = DB::table('order')
            ->where('is_active', 1)
            ->whereIn('contract_id', array_keys($contractPrivilegesArray))
            ->whereNotIn('order_id', array_keys($orderPrivilegesArray))
            ->select(['order_id', 'contract_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($orderIDs as $orderID) {
            $tempArr[$orderID->order_id] = $contractPrivilegesArray[$orderID->contract_id];
        }

        return $orderPrivilegesArray + $tempArr;
    }

    /**
     * @param $user_id
     * @param $contractPrivilegesArray
     * @param $invoicePrivilegesArray
     * @return array
     */
    public function createInvoicePrivilegesAccess($user_id, $contractPrivilegesArray, $invoicePrivilegesArray, $ruleEngineInvoices): array
    {
        $invoiceIDs = DB::table('payment_request')
            ->where('is_active', 1)
            ->whereIn('contract_id', array_keys($contractPrivilegesArray))
            ->whereNotIn('payment_request_id', array_keys($invoicePrivilegesArray))
            ->orWhere('created_by', $user_id)
            ->select(['payment_request_id', 'contract_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($invoiceIDs as $invoiceID) {
            if(!isset($contractPrivilegesArray[$invoiceID->contract_id])) {
                if(!isset($ruleEngineInvoices[$invoiceID->payment_request_id])) {
                    $tempArr[$invoiceID->payment_request_id] = 'full';
                }

            } else {
                if(!isset($ruleEngineInvoices[$invoiceID->payment_request_id])) {
                    $tempArr[$invoiceID->payment_request_id] = $contractPrivilegesArray[$invoiceID->contract_id];
                }
            }
        }

        return $invoicePrivilegesArray + $tempArr + $ruleEngineInvoices;
    }

    public function fetchRuleEngineInvoices($PrivilegesCollect)
    {
        $customerPrivilegesCollect = clone $PrivilegesCollect->where('type', 'customer')->values();
        $customerInvoiceIDs = $this->customerRuleEngineInvoices('customer_id', $customerPrivilegesCollect);

        $contractPrivilegesCollect = clone $PrivilegesCollect->where('type', 'contract')->values();
        $contractInvoiceIDs = $this->contractRuleEngineInvoices('contract_id', $contractPrivilegesCollect);

        $projectPrivilegesCollect = clone $PrivilegesCollect->where('type', 'project')->values();
        $projectInvoiceIDs = $this->projectRuleEngineInvoices($projectPrivilegesCollect);

//        $changeOrderPrivilegesCollect = clone $PrivilegesCollect->where('type', 'change-order')->values();
//        $changeOrderInvoiceIDs = $this->changeOrderRuleEngineInvoices($changeOrderPrivilegesCollect);

        $invoicePrivilegesCollect = clone $PrivilegesCollect->where('type', 'invoices')->values();
        $paymentRequestIDs = $this->invoiceRuleEngineInvoices('payment_request_id', $invoicePrivilegesCollect);


        $finalArray = [];

        $invoiceIDs = [];
        foreach ($paymentRequestIDs as $paymentRequestID) {
            foreach ($paymentRequestID["payment_request_ids"] as $key => $payment_request_id) {
                if(!isset($finalArray[$key])) {
                    $finalArray[$key] = $payment_request_id;
                }
            }

            foreach ($paymentRequestID["invoice_ids"] as $key => $invoiceID) {
                $invoiceIDs[$key] = $invoiceID;
            }
        }

        $projectIDs = [];
        foreach ($projectInvoiceIDs as $projectInvoiceID){
            foreach ($projectInvoiceID["payment_request_ids"] as $key => $payment_request_id) {
                if(!isset($finalArray[$key])) {
                    $finalArray[$key] = $payment_request_id;
                }
            }

            foreach ($projectInvoiceID["project_ids"] as $key => $invoiceID) {
                $projectIDs[$key] = $invoiceID;
            }
        }

        $contractIDs = [];
        foreach ($contractInvoiceIDs as $contractInvoiceID){
            foreach ($contractInvoiceID["payment_request_ids"] as $key => $payment_request_id) {
                if(!isset($finalArray[$key])) {
                    $finalArray[$key] = $payment_request_id;
                }
            }

            foreach ($contractInvoiceID["contract_ids"] as $key => $contractID) {
                $contractIDs[$key] = $contractID;
            }
        }

        $customerIDs = [];
        foreach ($customerInvoiceIDs as $customerInvoiceID) {
            foreach ($customerInvoiceID["payment_request_ids"] as $key => $payment_request_id) {
                if(!isset($finalArray[$key])) {
                    $finalArray[$key] = $payment_request_id;
                }
            }

            foreach ($customerInvoiceID["customer_ids"] as $key => $customerID) {
                $customerIDs[$key] = $customerID;
            }
        }

        return [
            'customer_ids' => $customerIDs,
            'project_ids' => $projectIDs,
            'contract_ids' => $contractIDs,
            'invoice_ids' => $invoiceIDs,
            'payment_request_ids' => array_filter($finalArray)
        ];
    }

    public function customerRuleEngineInvoices($type, $customerPrivilegesCollect)
    {
        return $customerPrivilegesCollect->map(function ($customerPrivilege) use($type) {
            $invoiceIDs = [];
            $customerIDs = [];
            $typeID = $customerPrivilege->type_id;
            if(($customerPrivilege->access == 'full' || $customerPrivilege->access == 'approve') && !empty($customerPrivilege->rule_engine_query)) {
                $ruleEngineQuery = json_decode($customerPrivilege->rule_engine_query, true);

                $ids = (new RuleEngineManager($type, $typeID, $ruleEngineQuery))->run();

                if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $invoiceIDs[$id] = $customerPrivilege->access;
                    }
                }
            } else {
                $customerIDs[$typeID] = $customerPrivilege->access;
            }

            return [
                'payment_request_ids' => $invoiceIDs,
                'customer_ids' => $customerIDs
            ];
        });
    }

    public function contractRuleEngineInvoices($type, $contractPrivilegesCollect)
    {

        return $contractPrivilegesCollect->map(function ($contractPrivilege) use($type) {
            $invoiceIDs = [];
            $customerIDs = [];
            $typeID = $contractPrivilege->type_id;
            if(($contractPrivilege->access == 'full' || $contractPrivilege->access == 'approve') && !empty($contractPrivilege->rule_engine_query)) {
                $ruleEngineQuery = json_decode($contractPrivilege->rule_engine_query, true);

                $ids = (new RuleEngineManager($type, $typeID, $ruleEngineQuery))->run();

                if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $invoiceIDs[$id] = $contractPrivilege->access;
                    }
                }
            } else {
                $customerIDs[$typeID] = $contractPrivilege->access;
            }

            return [
                'payment_request_ids' => $invoiceIDs,
                'contract_ids' => $customerIDs
            ];
        });
    }

    public function projectRuleEngineInvoices($projectPrivilegesCollect)
    {
        return $projectPrivilegesCollect->map(function ($projectPrivilege) {
            $invoiceIDs = [];
            $projectIds = [];
            $typeID = $projectPrivilege->type_id;

            if(($projectPrivilege->access == 'full' || $projectPrivilege->access == 'approve') && !empty($projectPrivilege->rule_engine_query)) {
                $ContractIDs = DB::table('contract')
                    ->where('project_id', $typeID)
                    ->pluck('contract_id');

                $ruleEngineQuery = json_decode($projectPrivilege->rule_engine_query, true);

                foreach ($ContractIDs as $ContractID) {
                    $ids = (new RuleEngineManager('contract_id', $ContractID, $ruleEngineQuery))->run();
                    if(!empty($ids)) {
                        foreach ($ids as $id) {
                            $invoiceIDs[$id] = $projectPrivilege->access;
                        }
                    }
                }
            } else {
                $projectIds[$typeID] = $projectPrivilege->access;
            }

            return [
                'payment_request_ids' => $invoiceIDs,
                'project_ids' => $projectIds
            ];
        });
    }

    public function invoiceRuleEngineInvoices($type, $invoicePrivilegesCollect)
    {
        return $invoicePrivilegesCollect->map(function ($invoicePrivilege) use($type) {
            $IDs = [];
            $invoiceIDs = [];

            $typeID = $invoicePrivilege->type_id;
            if(($invoicePrivilege->access == 'full' || $invoicePrivilege->access == 'approve') && !empty($invoicePrivilege->rule_engine_query)) {
                $ruleEngineQuery = json_decode($invoicePrivilege->rule_engine_query, true);

                $ids = (new RuleEngineManager($type, $typeID, $ruleEngineQuery))->run();

                if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $IDs[$id] = $invoicePrivilege->access;
                    }
                }
            } else {
                $invoiceIDs[$typeID] = $invoicePrivilege->access;
            }

            return [
                'payment_request_ids' => $IDs,
                'invoice_ids' => $invoiceIDs
            ];
        });
    }

    public function changeOrderRuleEngineInvoices($changeOrderPrivilegesCollect)
    {
        return $changeOrderPrivilegesCollect->map(function ($changeOrderPrivilege) {
            $invoiceIDs = [];
            $typeID = $changeOrderPrivilege->type_id;

            if(($changeOrderPrivilege->access == 'full' || $changeOrderPrivilege->access == 'approve') && !empty($changeOrderPrivilege->rule_engine_query)) {
                $paymentRequestIds = DB::table('payment_request')
                    ->where('is_active', 1)
                    ->where('change_order_id', 'like', '%'.$typeID.'%')
                    ->pluck('payment_request_id');

                $ruleEngineQuery = json_decode($changeOrderPrivilege->rule_engine_query, true);

                foreach ($paymentRequestIds as $paymentRequestId) {
                    $ids = (new RuleEngineManager('payment_request_id', $paymentRequestId, $ruleEngineQuery))->run();
                    if(!empty($ids)) {
                        foreach ($ids as $id) {
                            $invoiceIDs[$id] = $changeOrderPrivilege->access;
                        }
                    }
                }
            }

            return $invoiceIDs;
        });
    }


}
