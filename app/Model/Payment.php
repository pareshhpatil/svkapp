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
use App\Model\ParentModel;

class Payment extends ParentModel
{

    public function getPaymentStatus($merchant_id, $user_id, $transaction_id, $type)
    {
        $retObj = DB::select("call get_transaction_status('" . $merchant_id . "','" . $user_id . "','" . $transaction_id . "','" . $type . "');");
        $data = json_decode(json_encode($retObj), true);
        return $data;
    }
}
