<?php

namespace App\Http\Controllers;

use App\Libraries\Encrypt;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;

class SelectController extends AppController
{
    public function searchModule($type)
    {
        $search = request()->input('query');
        $userID = request()->input('user_id');

        $User = DB::table('user')
                    ->where('user_id', Encrypt::decode($userID))
                    ->first();

        if ($User->user_status == 20) {
            $merchant = (new ParentModel())->getTableRow('merchant', 'group_id', $User->group_id);
        } else {
            $merchant = (new ParentModel())->getTableRow('merchant', 'user_id', $User->user_id);
        }

        $merchantID = $merchant->merchant_id;

        $data = [];
        switch ($type) {
            case 'customer':
                $data = DB::table('customer')
                    ->where('merchant_id', $merchantID)
                    ->where('is_active', 1)
                    ->whereNotNull('company_name')
                    ->where('company_name', 'LIKE', '%'.$search.'%')
                    ->limit(10)
                    ->select(['customer_id', 'company_name'])
                    ->get();
                break;
            case 'project':
                $data = DB::table('project')
                    ->where('merchant_id', $merchantID)
                    ->where('is_active', 1)
                    ->where('project_name', 'LIKE', '%'.$search.'%')
                    ->limit(10)
                    ->select(['id', 'project_id', 'project_name'])
                    ->get();
                break;
            case 'contract':
                $data = DB::table('contract')
                    ->where('merchant_id', $merchantID)
                    ->where('is_active', 1)
                    ->where('status', 1)
                    ->where('contract_code', 'LIKE', '%'.$search.'%')
                    ->limit(10)
                    ->select(['contract_id', 'contract_code'])
                    ->get();
                break;
            case 'invoice':
                $data = DB::table('payment_request')
                    ->where('merchant_id', $merchantID)
                    ->where('is_active', 1)
                    ->where('invoice_number', 'LIKE', '%'.$search.'%')
                    ->limit(10)
                    ->select(['payment_request_id', 'invoice_number'])
                    ->get();
                break;
            case 'change-order':
                $data = DB::table('order')
                    ->where('merchant_id', $merchantID)
                    ->where('is_active', 1)
                    ->where('order_no', 'LIKE', '%'.$search.'%')
                    ->limit(10)
                    ->select(['order_id', 'order_no'])
                    ->get();
                break;
        }

        return $data;
    }
}