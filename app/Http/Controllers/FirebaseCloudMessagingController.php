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
        $authUser = auth()->user();

        // Get Notifications
        $Notifications = $authUser->unreadNotifications()
                                    ->limit(99)
                                    ->get();

        dd($Notifications);
    }
}