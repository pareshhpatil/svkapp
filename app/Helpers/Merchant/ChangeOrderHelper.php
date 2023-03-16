<?php

namespace App\Helpers\Merchant;

use App\Helpers\RuleEngine\RuleEngineManager;
use App\Jobs\ProcessChangeOrderForApproveJob;
use App\Libraries\Encrypt;
use App\User;
use Illuminate\Support\Facades\DB;
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
                        ->where('is_active', 1)
                        ->where('contract_id', $orderDetail->contract_id)
                        ->first();

            $contractID = $Contract->contract_id;
            $customerID = $Contract->customer_id;
            $projectID = $Contract->project_id;

            $data = DB::table('briq_privileges')
                ->where('merchant_id', $merchantID)
                ->where('is_active', 1)
                ->where('type', '!=', 'invoice')
                ->whereIn('access', ['full','approve'])
                ->get()->collect();

            $customerUsers = clone $data->where('type', 'customer')
                ->where('type_id', $customerID)->pluck('user_id');

            $customerUsersWithFullAccess = $customerUsers->toArray();

            $contractUsers = clone $data->where('type', 'contract')
                ->where('type_id', $contractID)->pluck('user_id');

            $contractUsersWithFullAccess = $contractUsers->toArray();

            $projectUsers = clone $data->where('type', 'project')
                ->where('type_id', $projectID)->pluck('user_id');

            $projectUsersWithFullAccess = $projectUsers->toArray();

            $ChangeOrderCollect = clone $data->where('type', 'change-order')
                ->whereIn('type_id', [$orderDetail->order_id, 'all'])->values();

            $changeOrderUsersWithFullAccess = $ChangeOrderCollect->map(function ($ChangeOrder) use($orderDetail) {

                if($ChangeOrder->type_id == $orderDetail->order_id || $ChangeOrder->type_id == 'all') {
                    if(!empty($ChangeOrder->rule_engine_query)) {
                        $ruleEngineQuery = json_decode($ChangeOrder->rule_engine_query, true);
                        $ids = (new RuleEngineManager('order_id', $ChangeOrder->type_id, $ruleEngineQuery))->run();

                        if(!empty($ids)) {
                            return $ChangeOrder->user_id;
                        }
                    } else {
                        return $ChangeOrder->user_id;
                    }
                }

                return '';
            });


            $adminRole = DB::table('briq_roles')
                ->where('merchant_id', $merchantID)
                ->where('name', 'Admin')
                ->first();

            $adminRoleUserIDs = DB::table('briq_user_roles')
                ->where('role_id', $adminRole->id)
                ->where('role_name', 'Admin')
                ->pluck('user_id')
                ->toArray();

            $uniqueUserIDs = array_unique(array_merge($adminRoleUserIDs, $customerUsersWithFullAccess, $contractUsersWithFullAccess, $projectUsersWithFullAccess, $changeOrderUsersWithFullAccess->toArray()));

            $Users = User::query()
                ->whereIn('user_id', $uniqueUserIDs)
                ->get();

            foreach ($Users as $User) {
                ProcessChangeOrderForApproveJob::dispatch($orderDetail, $User)->onQueue('promotion-sms-dev');
            }
        }
    }
}