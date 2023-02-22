<?php

namespace App\Helpers\Merchant;

use App\Model\Invoice;
use App\Notifications\InvoiceApprovalNotification;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * @author Nitish
 */
class InvoiceHelper
{
    public function sendInvoiceForApprovalNotification($paymentRequestID)
    {
        $paymentRequestDetail = DB::table('payment_request')
                                    ->where('payment_request_id', $paymentRequestID)
                                    ->first();

        if (!empty($paymentRequestDetail)) {
            $invoiceNumber = $paymentRequestDetail->invoice_number;
            if (substr($paymentRequestDetail->invoice_number, 0, 16) == 'System generated') {
                $invoiceNumber = (new Invoice())->getAutoInvoiceNo((substr($paymentRequestDetail->invoice_number, 16)));
                //$info['invoice_number'] = $this->invoiceModel->getAutoInvoiceNo(substr($info['invoice_number'], 16));
            }


            //from privileges table fetch records where type is invoice and access is approve and admin
            $userWithApprovalAccessIDs = DB::table('briq_privileges')
                ->where('is_active', 1)
                ->where('type', 'invoice')
                ->whereIn('access', ['full', 'edit', 'approve'])
                ->pluck('user_id')
                ->toArray();

            $adminRoleUserIDs = DB::table('briq_user_roles')
                ->where('role_name', 'Admin')
                ->pluck('user_id')
                ->toArray();

            $uniqueUserIDs = array_unique(array_merge($userWithApprovalAccessIDs, $adminRoleUserIDs));

            $Users = User::query()
                            ->whereIn('user_id', $uniqueUserIDs)
                            ->get();

            foreach ($Users as $User) {

                    if(!empty($User->fcm_token)) {
                        //Notification::route('broadcast')->notify(new InvoiceApprovalNotification($paymentRequestDetail->invoice_number, $paymentRequestID, $User));
//                    Notification::send(null,new InvoiceApprovalNotification($paymentRequestDetail->invoice_number, $paymentRequestID, $User));
                        //\Notification::send(null,new InvoiceApprovalNotification($paymentRequestDetail->invoice_number, $paymentRequestID, $User));
                        $User->notify(new InvoiceApprovalNotification($invoiceNumber, $paymentRequestID, $User));
                    }
//                }
            }
        }

    }
}