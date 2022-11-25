<?php

namespace App\Model;


/**
 * Description of Integration
 *
 * @author darshana
 */

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class IntegrationSetup extends ParentModel
{

    public function getIntegrations()
    {
        $retObj = DB::table('integrations')
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->orderBy('display_order')
            ->get();
        return $retObj;
    }

    public function getIntegrationDetails($integation_id)
    {
        $retObj = DB::table('integrations')
            ->where('id', $integation_id)
            ->first();

        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function updateStripeAccount($account_id, $merchant_id, $status = 1, $payoneer_id = null)
    {
        $retObj = DB::table('merchant_bank_detail')
            ->where('merchant_id', $merchant_id)
            ->first();
        if (!empty($retObj)) {
            if ($account_id == null) {
                $account_id = $retObj->stripe_account_id;
            }
            if ($payoneer_id == null) {
                $payoneer_id = $retObj->payoneer_account_id;
            }
            DB::table('merchant_bank_detail')
                ->where('merchant_id', $merchant_id)
                ->update([
                    'stripe_status' => $status,
                    'international_payment' => 1,
                    'stripe_account_id' => $account_id,
                    'payoneer_account_id' => $payoneer_id
                ]);
        } else {
            DB::table('merchant_bank_detail')->insertGetId(
                [
                    'merchant_id' => $merchant_id,
                    'account_no' => '',
                    'ifsc_code' => '',
                    'account_type' => 1,
                    'bank_name' => '',
                    'stripe_status' => $status,
                    'international_payment' => 1,
                    'stripe_account_id' => $account_id,
                    'payoneer_account_id' => $payoneer_id,
                    'created_by' => $merchant_id,
                    'last_update_by' => $merchant_id,
                    'created_date' => date('Y-m-d H:i:s')
                ]
            );
        }
    }

    public function addPaymentGateway($response, $merchant_id)
    {
        $merchant_landing = DB::table('merchant_landing')->where('merchant_id', $merchant_id)->first();
        $merchant = DB::table('merchant')->where('merchant_id', $merchant_id)->first();
        if (!empty($merchant_landing)) {
            if (str_contains($merchant_landing->logo, 'landingpage/default-logo')) {
                $image = env('APP_URL') . '/images/features/invoice.png';
            } else {
                if (str_contains($merchant_landing->logo, 'landingpage')) {
                    if (!is_file(public_path($merchant_landing->logo))) {
                        $image = env('APP_URL') . '/images/features/invoice.png';
                    } else {
                        $image = env('APP_URL') . $merchant_landing->logo;
                    }
                } else {
                    if (!is_file(public_path('uploads/images/landing/' . $merchant_landing->logo))) {
                        $image = env('APP_URL') . '/images/features/invoice.png';
                    } else {
                        $image = env('APP_URL') . '/uploads/images/landing/' . $merchant_landing->logo;
                    }
                }
            }
        } else {
            $image = env('APP_URL') . '/images/features/invoice.png';
        }
        $payment_gateway = DB::table('payment_gateway')->insertGetId([
            'pg_name' => 'STRIPE-' . $merchant->company_name,
            'pg_type' => 11,
            'is_active' => 1,
            'pg_val1' => $response->stripe_user_id,
            'pg_val2' => $response->refresh_token,
            'pg_val4' => $response->secret,
            'pg_val3' => 'Live',
            'pg_val5' => $image,
            'nodal_settlement' => 0,
            'status_url' => env('APP_URL') . '/secure/striperesponse',
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

        DB::table('merchant')->where('merchant_id', $merchant_id)->update(['is_legal_complete' => 1]);
    }

    public function addPayoneerGateway($company_name, $merchant_id)
    {

        $payment_gateway = DB::table('payment_gateway')->insertGetId([
            'pg_name' => 'Payoneer-' . substr($company_name, 0, 30),
            'pg_type' => 12,
            'is_active' => 1,
            'pg_val1' => $merchant_id,
            'pg_val2' => env('PAYONEER_PARTNER_ID'),
            'pg_val3' => 'Live',
            'pg_val5' => env('PAYONEER_API_URL'),
            'pg_val6' => env('PAYONEER_USERNAME'),
            'pg_val7' => env('PAYONEER_API_PASSWORD'),
            'type' => 1,
            'ret_tname' => 'pg_ret_bank12',
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

        DB::table('merchant')->where('merchant_id', $merchant_id)->update(['is_legal_complete' => 1]);
    }

    function getActivePG($merchant_id = null, $all_PG = 0)
    {
        if ($all_PG == 1) {
            $pg_types = [7, 10];
        } else {
            $pg_types = [7, 10, 11];
        }

        $getActivePGDetails = DB::table('merchant_fee_detail')
            ->join('payment_gateway', 'payment_gateway.pg_id', '=', 'merchant_fee_detail.pg_id')
            ->where('merchant_fee_detail.merchant_id', $merchant_id)
            ->where('merchant_fee_detail.is_active', 1)->WhereIn('payment_gateway.pg_type', $pg_types)->select(DB::raw('payment_gateway.pg_id,pg_type,pg_val1,pg_val2,pg_val4'))->get();
        $array = array();
        if (!empty($getActivePGDetails)) {
            foreach ($getActivePGDetails as $row) {
                $array[$row->pg_type] = array('pg_id' => $row->pg_id, 'pg_val1' => $row->pg_val1, 'pg_val2' => $row->pg_val2, 'pg_val4' => $row->pg_val4, 'pg_val4' => $row->pg_val4);
            }
        }
        return $array;
    }
}
