<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Home
 *
 * @author Paresh
 */

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Home extends Model
{

    public function getCampaignId($text)
    {
        $retObj = DB::table('registration_campaigns')
            ->select(DB::raw('id'))
            ->where('campaign_text_id', $text)
            ->first();
        if (!empty($retObj)) {
            return $retObj->id;
        } else {
            return false;
        }
    }

    public function getTNC($merchant_id, $type)
    {
        $retObj = DB::table('merchant_landing')
            ->select(DB::raw('terms_condition,cancellation_policy'))
            ->where('merchant_id', $merchant_id)
            ->first();
        if (!empty($retObj)) {
            if ($type == 1) {
                return $retObj->terms_condition;
            } else {
                return $retObj->cancellation_policy;
            }
        } else {
            return false;
        }
    }
}
