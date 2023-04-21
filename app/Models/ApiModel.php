<?php

namespace App\Models;

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
use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;
use Exception;

class ApiModel extends ParentModel
{
    public function saveOtp($mobile, $otp, $user_id)
    {
        DB::table('otp')->insertGetId(
            [
                'otp' => $otp,
                'mobile' => $mobile,
                'user_id' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
    }
}
