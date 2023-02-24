<?php

namespace App\Http\Controllers;

use App\Libraries\Encrypt;
use App\Notifications\InvoiceApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FirebaseCloudMessagingController extends AppController
{
    public function updateToken(Request $request)
    {
        $authUser = auth()->user();

        DB::table('user')
            ->where('user_id', $authUser->user_id)
            ->update([
                'fcm_token' => $request->token
            ]);

        return response()->json([
            'success'=>true
        ]);
    }

    public function testEvent()
    {
        $authUser = auth()->user();

        $authUser->notify(new InvoiceApprovalNotification("Lorem Ipsum", "lorem ipsum", $authUser->fcm_token));

        return 'event sent';
    }

    public function getNotifications()
    {
//        $data = DB::table('briq_privileges')
//            ->where('is_active', 1)
//            ->where('type', '!=', 'change-order')
//            ->whereIn('access', ['full','approve'])
//            ->get()->collect();
//
//        $contractUsers = clone $data->where('type', 'contract')
//            ->where('type_id', '313')->pluck('user_id');
//
//        $contractUsersWithFullAccess = $contractUsers->toArray();
//
//        $customerUsers = clone $data->where('type', 'customer')
//            ->where('type_id', '3360')->pluck('user_id');
//
//        $customerUsersWithFullAccess = $customerUsers->toArray();
//
//        $invoiceUsers = clone $data->where('type', 'invoice')
//            ->where('type_id', 'R000029871')->pluck('user_id');
//
//        $invoiceUsersWithFullAccess = $invoiceUsers->toArray();
//        dd($contractUsersWithFullAccess, $customerUsersWithFullAccess, $invoiceUsersWithFullAccess);
        $authUser = auth()->user();

        // Get Notifications
        $Notifications = $authUser->unreadNotifications()
                                    ->limit(99)
                                    ->get();

        dd($Notifications);
    }
}