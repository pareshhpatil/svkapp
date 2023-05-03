<?php

namespace App\Helpers\Merchant;

use App\Helpers\RuleEngine\RuleEngineManager;
use App\Jobs\ProcessChangeOrderForApproveJob;
use App\Jobs\ProcessChangeOrderMailForApproveJob;
use App\Libraries\Encrypt;
use App\Notifications\ChangeOrderNotification;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

/**
 * @author Nitish
 */
class ChangeOrderHelper
{
    public function sendChangeOrderForApprovalNotification($orderId, $type = null)
    {
        $merchantID = Encrypt::decode(Session::get('merchant_id'));
        $authUserID = Encrypt::decode(Session::get('userid'));
        $authUserRole = Session::get('user_role');
        $table = 'order';
        if ($type == 'subcontract') {
            $table = 'subcontract_change_order';
        }
        $orderDetail = DB::table($table)
            ->where('order_id', $orderId)
            ->first();

        if (!empty($orderDetail)) {
            if ($type == 'subcontract') {
                $Contract = DB::table('sub_contract')
                    ->where('is_active', 1)
                    ->where('sub_contract_id', $orderDetail->contract_id)
                    ->first();
                $Contract->contract_id = $Contract->sub_contract_id;
            } else {
                $Contract = DB::table('contract')
                    ->where('is_active', 1)
                    ->where('contract_id', $orderDetail->contract_id)
                    ->first();
            }


            $contractID = $orderDetail->contract_id;

            $projectID = $Contract->project_id;


            //Update Change Order Privileges array
            $privilegesContractIDs = json_decode(Redis::get('contract_privileges_' . $authUserID), true);
            $privilegesChangeOrderIDs = json_decode(Redis::get('change_order_privileges_' . $authUserID), true);

            if ($authUserRole == 'Admin') {
                $privilegesChangeOrderIDs[$orderDetail->order_id] = 'full';
            } else {
                if (isset($privilegesContractIDs[$contractID])) {
                    if ($privilegesContractIDs[$contractID] == 'full') {
                        $privilegesChangeOrderIDs[$orderDetail->order_id] = 'full';
                    }

                    if ($privilegesContractIDs[$contractID] == 'approve') {
                        $privilegesChangeOrderIDs[$orderDetail->order_id] = 'approve';
                    }

                    if ($privilegesContractIDs[$contractID] == 'edit') {
                        $privilegesChangeOrderIDs[$orderDetail->order_id] = 'edit';
                    }
                } else {
                    $privilegesChangeOrderIDs[$orderDetail->order_id] = 'edit';
                }
            }

            Redis::set('change_order_privileges_' . $authUserID, json_encode($privilegesChangeOrderIDs));


            $data = DB::table('briq_privileges')
                ->where('merchant_id', $merchantID)
                ->where('is_active', 1)
                ->where('type', '!=', 'invoice')
                ->whereIn('access', ['full', 'approve'])
                ->get()->collect();
            if ($type == 'subcontract') {
                $customerID = $Contract->vendor_id;
                $customerUsers = clone $data->where('type', 'customer')
                    ->where('type_id', $customerID)->pluck('user_id');
                $customerUsersWithFullAccess = [];
            } else {
                $customerID = $Contract->customer_id;
                $customerUsers = clone $data->where('type', 'customer')
                    ->where('type_id', $customerID)->pluck('user_id');
                $customerUsersWithFullAccess = $customerUsers->toArray();
            }



            $contractUsers = clone $data->where('type', 'contract')
                ->where('type_id', $contractID)->pluck('user_id');

            $contractUsersWithFullAccess = $contractUsers->toArray();

            $projectUsers = clone $data->where('type', 'project')
                ->where('type_id', $projectID)->pluck('user_id');

            $projectUsersWithFullAccess = $projectUsers->toArray();

            $ChangeOrderCollect = clone $data->where('type', 'change-order')
                ->whereIn('type_id', [$orderDetail->order_id, 'all'])->values();

            $changeOrderUsersWithFullAccess = $ChangeOrderCollect->map(function ($ChangeOrder) use ($orderDetail) {

                if ($ChangeOrder->type_id == $orderDetail->order_id || $ChangeOrder->type_id == 'all') {
                    if (!empty($ChangeOrder->rule_engine_query)) {
                        $ruleEngineQuery = json_decode($ChangeOrder->rule_engine_query, true);
                        $ids = (new RuleEngineManager('order_id', $ChangeOrder->type_id, $ruleEngineQuery))->run();

                        if (!empty($ids)) {
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
                ->whereIn('user_status', [20, 15, 12, 16])
                ->get();

            foreach ($Users as $User) {
                $User->notify(new ChangeOrderNotification($orderDetail->order_id, $orderDetail->order_no, $User));
                //Different queue for mail bcz mails fails sometimes if email not verified
                ProcessChangeOrderMailForApproveJob::dispatch($orderDetail, $User)->onQueue(env('SQS_USER_NOTIFICATION'));
            }
        }
    }
}
