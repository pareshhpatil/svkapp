<?php

namespace App\Http\Controllers;

use App\Helpers\RuleEngine\RuleEngineManager;
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

    public function getUserPrivileges() {
        $user_id = 'U000005235';
        $privilegesCollect = DB::table('briq_privileges')
            ->where('user_id', $user_id)
            ->where('is_active', 1)
            ->select(['type', 'type_id', 'access', 'rule_engine_query'])
            ->get()
            ->collect();

        $customerPrivilegesCollect = clone $privilegesCollect->where('type', 'customer')
                                                            ->pluck('access', 'type_id');



        $customerRuleEngineInvoices = clone $privilegesCollect->where('type', 'customer')->values();

        $customerInvoiceIDs = $customerRuleEngineInvoices->map(function ($customerPrivilege) {
            $invoiceIDs = [];
            $typeID = $customerPrivilege->type_id;
            $ruleEngineQuery = json_decode($customerPrivilege->rule_engine_query, true);

            $ids = (new RuleEngineManager('customer_id', $typeID, $ruleEngineQuery))->run();

            if(!empty($ids)) {
                foreach ($ids as $id) {
                    $invoiceIDs[$id] = $customerPrivilege->access;
                }
            }

            return $invoiceIDs;
        });
        $customerInvoiceIDs = array_merge(...$customerInvoiceIDs);

        $customerPrivilegesArray = $customerPrivilegesCollect->toArray();

        $projectPrivilegesCollect = clone $privilegesCollect->where('type', 'project')
            ->pluck('access', 'type_id');

        $projectPrivilegesArray = $projectPrivilegesCollect->toArray();

        $projectRuleEngineInvoices = clone $privilegesCollect->where('type', 'project')->values();

        $projectInvoiceIDs = $projectRuleEngineInvoices->map(function ($projectPrivilege) {
            $invoiceIDs = [];
            $typeID = $projectPrivilege->type_id;

            $ContractIDs = DB::table('contract')
                            ->where('project_id', $typeID)
                            ->pluck('contract_id');

            $ruleEngineQuery = json_decode($projectPrivilege->rule_engine_query, true);

            foreach ($ContractIDs as $ContractID) {
                $ids = (new RuleEngineManager('contract_id', $ContractID, $ruleEngineQuery))->run();
                if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $invoiceIDs[$id] = $projectPrivilege->access;
                    }
                }
            }

            return $invoiceIDs;
        });



        $contractPrivilegesCollect = clone $privilegesCollect->where('type', 'contract')
            ->pluck('access', 'type_id');

        $contractPrivilegesArray = $contractPrivilegesCollect->toArray();

        $contractRuleEngineInvoices = clone $privilegesCollect->where('type', 'contract')->values();

        $contractInvoiceIDs = $contractRuleEngineInvoices->map(function ($contractPrivilege) {
            $invoiceIDs = [];
            $typeID = $contractPrivilege->type_id;


            $ruleEngineQuery = json_decode($contractPrivilege->rule_engine_query, true);

            $ids = (new RuleEngineManager('contract_id', $typeID, $ruleEngineQuery))->run();

            if(!empty($ids)) {
                foreach ($ids as $id) {
                    $invoiceIDs[$id] = $contractPrivilege->access;
                }
            }

            return $invoiceIDs;
        });


        $invoicePrivilegesCollect = clone $privilegesCollect->where('type', 'invoice')
            ->pluck('access', 'type_id');

        $invoicePrivilegesArray = $invoicePrivilegesCollect->toArray();

        $invoiceRuleEngineInvoices = clone $privilegesCollect->where('type', 'invoice')->values();

        $invoiceIDs = $invoiceRuleEngineInvoices->map(function ($invoicePrivilege) {
            $invoiceIDs = [];
            $typeID = $invoicePrivilege->type_id;


            $ruleEngineQuery = json_decode($invoicePrivilege->rule_engine_query, true);

            $ids = (new RuleEngineManager('payment_request_id', $typeID, $ruleEngineQuery))->run();

            if(!empty($ids)) {
                foreach ($ids as $id) {
                    $invoiceIDs[$id] = $invoicePrivilege->access;
                }
            }

            return $invoiceIDs;
        });

        $changeOrderPrivilegesCollect = clone $privilegesCollect->where('type', 'contract')->values();;

        $changeOrderInvoiceIDs = $changeOrderPrivilegesCollect->map(function ($changeOrderPrivilege) {
            $invoiceIDs = [];
            $typeID = $changeOrderPrivilege->type_id;

            $paymentRequestIds = DB::table('payment_request')
                ->where('is_active', 1)
                ->where('change_order_id', 'like', '%'.$typeID.'%')
                ->pluck('payment_request_id');

            $ruleEngineQuery = json_decode($changeOrderPrivilege->rule_engine_query, true);

            foreach ($paymentRequestIds as $paymentRequestId) {
                $ids = (new RuleEngineManager('payment_request_id', $paymentRequestId, $ruleEngineQuery))->run();
                if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $invoiceIDs[$id] = $changeOrderPrivilege->access;
                    }
                }
            }

            return $invoiceIDs;
        });


        $finalArray = [];

        foreach ($invoiceIDs as $key => $invoiceID){
            $finalArray[$key] = $invoiceID;
        }

        foreach ($projectInvoiceIDs as $key => $projectInvoiceID){

            if(!isset($finalArray[$key])) {
                $finalArray[$key] = $projectInvoiceID;
            }
        }

        foreach ($contractInvoiceIDs as $key => $contractInvoiceID){
            if(!isset($finalArray[$key])) {
                $finalArray[$key] = $contractInvoiceID;
            }
        }

        foreach ($customerInvoiceIDs as $key => $customerInvoiceID){
            if(!isset($finalArray[$key])) {
                $finalArray[$key] = $customerInvoiceID;
            }
        }

        dd($customerInvoiceIDs, $projectInvoiceIDs, $contractInvoiceIDs, $finalArray);

        $orderPrivilegesCollect = clone $privilegesCollect->where('type', 'change-order')
            ->pluck('access', 'type_id');

        $orderPrivilegesArray = $orderPrivilegesCollect->toArray();

        if(!empty($customerPrivilegesArray)) {
            $projectPrivilegesArray = $this->createProjectPrivilegesAccess($customerPrivilegesArray, $projectPrivilegesArray);
        }

        if (!empty($projectPrivilegesArray)) {
            $contractPrivilegesArray = $this->createContractPrivilegesAccess($projectPrivilegesArray, $contractPrivilegesArray);
        }

        if(!empty($contractPrivilegesArray)) {
            $invoicePrivilegesArray = $this->createInvoicePrivilegesAccess($user_id, $contractPrivilegesArray, $invoicePrivilegesArray);
        }
        //check here bcz we are already fetching invoices from customer, contract and project

        if(!empty($contractPrivilegesArray)) {
            $orderPrivilegesArray = $this->createOrderPrivilegesAccess($contractPrivilegesArray, $orderPrivilegesArray);
        }

        return [
            'customer_privileges' => $customerPrivilegesArray,
            'project_privileges' => $projectPrivilegesArray,
            'contract_privileges' => $contractPrivilegesArray,
            'invoice_privileges' => $invoicePrivilegesArray,
            'change_order_privileges' => $orderPrivilegesArray
        ];
    }

    public function createProjectPrivilegesAccess($customerPrivilegesArray, $projectPrivilegesArray) {

        $projectIDs = DB::table('project')
            ->where('is_active', 1)
            ->whereIn('customer_id', array_keys($customerPrivilegesArray))
            ->whereNotIn('id', array_keys($projectPrivilegesArray))
            ->select(['id', 'customer_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($projectIDs as $projectID) {
            $tempArr[$projectID->id] = $customerPrivilegesArray[$projectID->customer_id];
        }

        return $projectPrivilegesArray + $tempArr;
    }

    public function createContractPrivilegesAccess($projectPrivilegesArray, $contractPrivilegesArray) {
        $tempArr= [];

        $contractIDs = DB::table('contract')
            ->where('is_active', 1)
            ->whereIn('project_id', array_keys($projectPrivilegesArray))
            ->whereNotIn('contract_id', array_keys($contractPrivilegesArray))
            ->select(['contract_id', 'project_id'])
            ->get()
            ->toArray();

        foreach ($contractIDs as $contractID) {
            $tempArr[$contractID->contract_id] = $projectPrivilegesArray[$contractID->project_id];
        }

        return $contractPrivilegesArray + $tempArr;
    }

    public function createOrderPrivilegesAccess($contractPrivilegesArray, $orderPrivilegesArray) {
        $orderIDs = DB::table('order')
            ->where('is_active', 1)
            ->whereIn('contract_id', array_keys($contractPrivilegesArray))
            ->whereNotIn('order_id', array_keys($orderPrivilegesArray))
            ->select(['order_id', 'contract_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($orderIDs as $orderID) {
            $tempArr[$orderID->order_id] = $contractPrivilegesArray[$orderID->contract_id];
        }

        return $orderPrivilegesArray + $tempArr;
    }

    /**
     * @param $user_id
     * @param $contractPrivilegesArray
     * @param $invoicePrivilegesArray
     * @return array
     */
    public function createInvoicePrivilegesAccess($user_id, $contractPrivilegesArray, $invoicePrivilegesArray): array
    {
        $invoiceIDs = DB::table('payment_request')
            ->where('is_active', 1)
            ->whereIn('contract_id', array_keys($contractPrivilegesArray))
            ->whereNotIn('payment_request_id', array_keys($invoicePrivilegesArray))
            ->orWhere('created_by', $user_id)
            ->select(['payment_request_id', 'contract_id'])
            ->get()
            ->toArray();

        $tempArr= [];
        foreach ($invoiceIDs as $invoiceID) {
            if(!isset($contractPrivilegesArray[$invoiceID->contract_id])) {
                $tempArr[$invoiceID->payment_request_id] = 'full';
            } else {
                $tempArr[$invoiceID->payment_request_id] = $contractPrivilegesArray[$invoiceID->contract_id];
            }
        }

        return $invoicePrivilegesArray + $tempArr;
    }
}