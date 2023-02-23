<?php

namespace App\Helpers\Merchant;

use App\Libraries\Encrypt;
use App\Model\Invoice;
use App\Notifications\ChangeOrderNotification;
use App\Notifications\InvoiceApprovalNotification;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

/**
 * @author Nitish
 */
class ChangeOrderHelper
{
    public function sendChangeOrderForApprovalNotification($orderId)
    {
        $merchantID = Encrypt::decode(Session::get('merchant_id'));

        $orderDetail = DB::table('order')
            ->where('order_id', $orderId)
            ->first();

        if (!empty($orderDetail)) {
            $Contract = DB::table('contract')
                        ->where('contract_id', $orderDetail->contract_id)
                        ->first();

            $contractID = $Contract->contract_id;
            $customerID = $Contract->customer_id;
            $projectID = $Contract->project_id;

            $contractUserWithFullAccess = DB::table('briq_privileges')
                ->where('is_active', 1)
                ->where('type', 'contract')
                ->where('type_id', $contractID)
                ->where('access', 'full')
                ->pluck('user_id')
                ->toArray();

            $customerUserWithFullAccess = DB::table('briq_privileges')
                ->where('is_active', 1)
                ->where('type', 'customer')
                ->where('type_id', $customerID)
                ->where('access', 'full')
                ->pluck('user_id')
                ->toArray();

            $projectUserWithFullAccess = DB::table('briq_privileges')
                ->where('is_active', 1)
                ->where('type', 'project')
                ->where('type_id', $projectID)
                ->where('access', 'full')
                ->pluck('user_id')
                ->toArray();

            $changeOrderUserWithApprovalAccessIDs = DB::table('briq_privileges')
                ->where('is_active', 1)
                ->where('merchant_id', $merchantID)
                ->where('type', 'change-order')
                ->where('type_id', 'all')
                ->where('access', 'full')
                ->pluck('user_id')
                ->toArray();

            $adminRole = DB::table('briq_roles')
                ->where('merchant_id', $merchantID)
                ->where('name', 'Admin')
                ->first();

            $adminRoleUserIDs = DB::table('briq_user_roles')
                ->where('role_id', $adminRole->id)
                ->where('role_name', 'Admin')
                ->pluck('user_id')
                ->toArray();

            $uniqueUserIDs = array_unique(array_merge($changeOrderUserWithApprovalAccessIDs, $adminRoleUserIDs, $contractUserWithFullAccess, $customerUserWithFullAccess, $projectUserWithFullAccess));

            $Users = User::query()
                ->whereIn('user_id', $uniqueUserIDs)
                ->get();

//                $testUser = User::query()
//                    ->where('email_id', 'nitish.harchand@briq.com')
//                    ->first();
//                //dd($testUser);
//                if(!empty($testUser->fcm_token)) {
//                    $testUser->notify(new InvoiceApprovalNotification($invoiceNumber, $paymentRequestID, $testUser));
//                }

            foreach ($Users as $User) {
                if(!empty($User->fcm_token)) {
                    $User->notify(new ChangeOrderNotification());
                    //$User->notify(new InvoiceApprovalNotification($invoiceNumber, $paymentRequestID, $User));
                }
            }
        }
    }
}