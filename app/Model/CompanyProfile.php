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

class CompanyProfile extends ParentModel
{
    public function updateMerchantLanding($where, $whvalue, $overview, $publish, $banner_text, $banner_paragraph)
    {
        DB::table('merchant_landing')
            ->where($where, $whvalue)
            ->update([
                'overview' => $overview,
                'publishable' => $publish,
                'banner_text' => $banner_text,
                'banner_paragraph' => $banner_paragraph
            ]);
    }

    public function getIndustry($industry_type)
    {
        $retObj = DB::table('config')
            ->select(DB::raw('*'))
            ->where('config_key', $industry_type)
            ->where('config_type', 'industry_type')
            ->first();
        if (!empty($retObj)) {
            return $retObj->config_value;
        } else {
            return false;
        }
    }

    public function updateRows($table, $where, $whvalue, $values)
    {
        DB::table($table)
            ->where($where, $whvalue)
            ->update($values);
    }
}
