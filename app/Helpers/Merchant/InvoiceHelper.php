<?php

namespace App\Helpers\Merchant;

use App\Libraries\Encrypt;
use App\Model\Invoice;
use App\Notifications\InvoiceApprovalNotification;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

/**
 * @author Nitish
 */
class InvoiceHelper
{
    public function sendInvoiceForApprovalNotification($paymentRequestID)
    {
        $merchantID = Encrypt::decode(Session::get('merchant_id'));

        $paymentRequestDetail = DB::table('payment_request')
            ->where('payment_request_id', $paymentRequestID)
            ->first();

        if (!empty($paymentRequestDetail)) {
            $invoiceNumber = $paymentRequestDetail->invoice_number;
            if (substr($paymentRequestDetail->invoice_number, 0, 16) == 'System generated') {
                $invoiceNumber = (new Invoice())->getAutoInvoiceNo((substr($paymentRequestDetail->invoice_number, 16)));
            }

            $Contract = DB::table('contract')
                ->where('is_active', 0)
                ->where('contract_id', $paymentRequestDetail->contract_id)
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

            $invoiceUserWithApprovalAccessIDs = DB::table('briq_privileges')
                ->where('is_active', 1)
                ->where('merchant_id', $merchantID)
                ->where('type', 'invoice')
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

            $uniqueUserIDs = array_unique(array_merge($invoiceUserWithApprovalAccessIDs, $adminRoleUserIDs, $contractUserWithFullAccess, $customerUserWithFullAccess, $projectUserWithFullAccess));

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
                    $User->notify(new InvoiceApprovalNotification($invoiceNumber, $paymentRequestID, $User));
                }
            }
        }
    }
}