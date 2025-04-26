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

    public function saveToken($token, $user_id)
    {
        DB::table('firebase_token')->insertGetId(
            [
                'token' => $token,
                'user_id' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
    }

    public function saveWhatsapp($mobile, $name, $type, $status, $message_type, $message, $message_id)
    {
        $id = DB::table('whatsapp_messages')->insertGetId(
            [
                'mobile' => $mobile,
                'name' => $name,
                'type' => $type,
                'status' => $status,
                'message_type' => $message_type,
                'message' => $message,
                'message_id' => $message_id,
                'last_update_date' => date('Y-m-d H:i:s'),
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
        return $id;
    }
}
