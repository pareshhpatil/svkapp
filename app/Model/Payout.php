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

class Payout extends ParentModel
{

    public function getBeneficiaryList($merchant_id)
    {
        $retObj = DB::table('beneficiary')
            ->select(DB::raw('*'))
            ->where('status', 1)
            ->where('is_active', 1)
            ->where('merchant_id', $merchant_id)
            ->get();
        return $retObj;
    }

    public function getWithdrawalList($merchant_id)
    {
        $retObj = DB::table('withdraw')
            ->select(DB::raw('*'))
            ->where('status', 1)
            ->where('is_active', 1)
            ->where('type', 2)
            ->where('merchant_id', $merchant_id)
            ->get();
        return $retObj;
    }

    public function getTransactionList($merchant_id, $from_date, $to_date)
    {
        $retObj = DB::table('vendor_transfer as t')
            ->select(DB::raw('t.*,b.name,b.beneficiary_code,b.bank_account_no,b.ifsc_code,b.upi,t.cashfree_transfer_id,t.reference_id'))
            ->join('beneficiary as b', 'b.beneficiary_id', '=', 't.beneficiary_id')
            ->where('t.beneficiary_type', 3)
            ->where('t.is_active', 1)
            ->where('t.merchant_id', $merchant_id)
            ->whereDate('t.created_date', '>=', $from_date)
            ->whereDate('t.created_date', '<=', $to_date)
            ->get();
        return $retObj;
    }

    public function deleteBeneficiary($id, $merchant_id, $user_id)
    {
        DB::table('beneficiary')
            ->where('beneficiary_id', $id)
            ->where('merchant_id', $merchant_id)
            ->update([
                'is_active' => 0,
                'last_update_by' => $user_id
            ]);
    }
}
