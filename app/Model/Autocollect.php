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
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class Autocollect extends ParentModel
{

    public function deletePlan($id, $merchant_id, $user_id)
    {
        DB::table('autocollect_plans')
            ->where('plan_id', $id)
            ->where('merchant_id', $merchant_id)
            ->update([
                'is_active' => 0,
                'last_update_by' => $user_id
            ]);
    }

    public function autoCollectKeys($merchant_id)
    {
        $retObj =  DB::table('merchant_config_data')
            ->select(DB::raw('id'))
            ->where('key', 'AUTOCOLLECT_KEY_DETAILS')
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->first();
        if (!empty($retObj)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateSubscriptionStatus($subscription_id, $status)
    {
        DB::table('autocollect_subscriptions')
            ->where('subscription_id', $subscription_id)
            ->update([
                'status' => $status
            ]);
    }

    public function getTransactionList($merchant_id, $from_date, $to_date)
    {
        $retObj = DB::table('autocollect_transaction as t')
            ->select(DB::raw('t.transaction_id,t.payment_transaction_id,t.amount,t.status,t.pg_ref,customer_name,email_id,mobile,t.created_date'))
            ->join('autocollect_subscriptions as s', 's.subscription_id', '=', 't.subscription_id')
            ->where('t.merchant_id', $merchant_id)
            ->whereDate('t.created_date', '>=', $from_date)
            ->whereDate('t.created_date', '<=', $to_date)
            ->get();
        return $retObj;
    }

    public function getActiveSubscription()
    {
        $retObj = DB::table('autocollect_subscriptions')
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->whereDate('expiry_date', '>', date('Y-m-d'))
            ->get();
        return $retObj;
    }

    public function handleSubscriptionPayment($subscription_id, $payment_id, $referenceId, $amount, $date, $status)
    {
        $retObj = DB::select("call save_autocollect_transaction('" . $subscription_id . "','" . $payment_id . "','" . $referenceId . "','" . $amount . "','" . $date . "','" . $status . "');");
    }
}
