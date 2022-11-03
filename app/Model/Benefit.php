<?php

namespace App\Model;


/**
 * Description of Benefit
 *
 * @author Paresh
 */

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class Benefit extends ParentModel
{

    public function getBenefits()
    {
        $retObj = DB::table('benefits')
            ->select(DB::raw('*'))
            ->orderBy('display_order')
            ->where('is_active', 1)
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->get();
        return $retObj;
    }

    public function getMerchantBenefits($merchant_id)
    {
        $retObj = DB::table('benefits_tracking')
            ->select(DB::raw('benefit_id'))
            ->where('merchant_id', $merchant_id)
            ->get();
        $array = array();
        if (!$retObj->isEmpty()) {
            foreach ($retObj as $row) {
                $array[] = $row->benefit_id;
            }
        }
        return $array;
    }

    public function saveBenefit($benefit_id, $merchant_id)
    {
        $id = DB::table('benefits_tracking')->insertGetId(
            [
                'benefit_id' => $benefit_id,
                'merchant_id' => $merchant_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }
}
