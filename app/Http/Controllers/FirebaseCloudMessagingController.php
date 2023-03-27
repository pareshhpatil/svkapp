<?php

namespace App\Http\Controllers;

use App\Helpers\RuleEngine\RuleEngineManager;
use App\Libraries\Encrypt;
use App\Model\Invoice;
use App\Notifications\InvoiceApprovalNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

    public function invoiceNotifyTest()
    {
        $paymentRequestID = 'R000030221';
        $merchantID = Encrypt::decode(Session::get('merchant_id'));

        $paymentRequestDetail =  (new Invoice())->getInvoiceInfo($paymentRequestID, $merchantID);

        if (!empty($paymentRequestDetail)) {
            $invoiceNumber = $paymentRequestDetail->invoice_number;
            if (substr($paymentRequestDetail->invoice_number, 0, 16) == 'System generated') {
                $invoiceNumber = (new Invoice())->getAutoInvoiceNo((substr($paymentRequestDetail->invoice_number, 16)));
            }

            $Contract = DB::table('contract')
                ->where('is_active', 1)
                ->where('contract_id', $paymentRequestDetail->contract_id)
                ->first();

            $contractID = $Contract->contract_id;
            $customerID = $Contract->customer_id;
            $projectID = $Contract->project_id;

            $data = DB::table('briq_privileges')
                ->where('merchant_id', $merchantID)
                ->where('is_active', 1)
                ->where('type', '!=', 'change-order')
                ->whereIn('access', ['full','approve'])
                ->get()->collect();


            $CustomerCollect = clone $data->where('type', 'customer')
                ->whereIn('type_id', [$customerID, 'all'])
                ->values();

            $customerUsersWithFullAccess = [];
//            if(!empty($CustomerCollect->rule_engine_query)) {
//                $ruleEngineQuery = json_decode($CustomerCollect->rule_engine_query, true);
//                $stat = (new RuleEngineManager('customer_id', $CustomerCollect->type_id, $ruleEngineQuery))->run();
//
//                if(!empty($stat)) {
//                    if(in_array($paymentRequestID, $stat)) {
//                        $customerUsersWithFullAccess[] = $CustomerCollect->user_id;
//                    }
//                }
//            } else {
//                $customerUsersWithFullAccess[] = $CustomerCollect->user_id;
//            }

            $customerUsersWithFullAccess = $CustomerCollect->map(function ($Customer) use($paymentRequestID) {
                $userIDs = [];

                if(!empty($Customer->rule_engine_query)) {
                    $ruleEngineQuery = json_decode($Customer->rule_engine_query, true);
                    $stat = (new RuleEngineManager('customer_id', $Customer->type_id, $ruleEngineQuery))->run();

                    if(!empty($stat)) {
                        if(in_array($paymentRequestID, $stat)) {
                            $userIDs[] = $Customer->user_id;
                        }
                    }
                } else {
                    $userIDs[] = $Customer->user_id;
                }

                return $userIDs;
            });


//            $customerUsers = clone $data->where('type', 'customer')
//                ->where('type_id', $customerID)->pluck('user_id');

//            $customerUsersWithFullAccess = $customerUsers->toArray();

//            $contractUsers = clone $data->where('type', 'contract')
//                ->where('type_id', $contractID)->pluck('user_id');
//
//            $contractUsersWithFullAccess = $contractUsers->toArray();

            $ContractCollect = clone $data->where('type', 'contract')
                ->where('type_id', $contractID)->values();
            $contractUsersWithFullAccess = $ContractCollect->map(function ($Contract)  use($paymentRequestID) {
                $userIDs = [];

                if(!empty($Contract->rule_engine_query)) {
                    $ruleEngineQuery = json_decode($Contract->rule_engine_query, true);
                    $stat = (new RuleEngineManager('contract_id', $Contract->type_id, $ruleEngineQuery))->run();

                    if(!empty($stat)) {
                        if(in_array($paymentRequestID, $stat)) {
                            $userIDs[] = $Contract->user_id;
                        }
                    }
                } else {
                    $userIDs[] = $Contract->user_id;
                }

                return $userIDs;
            });

//            $projectUsers = clone $data->where('type', 'project')
//                ->where('type_id', $projectID)->pluck('user_id');
//
//            $projectUsersWithFullAccess = $projectUsers->toArray();

            $ProjectCollect = clone $data->where('type', 'project')
                ->where('type_id', $projectID)->values();
            $projectUsersWithFullAccess = $ProjectCollect->map(function ($Project) use($paymentRequestID) {
                $userIDs = [];

                if(($Project->access == 'full' || $Project->access == 'approve') && !empty($Project->rule_engine_query)) {
                    $ContractIDs = DB::table('contract')
                        ->where('project_id', $Project->type_id)
                        ->pluck('contract_id');

                    $ruleEngineQuery = json_decode($Project->rule_engine_query, true);

                    $ss = [];
                    foreach ($ContractIDs as $ContractID) {
                        $ids = (new RuleEngineManager('contract_id', $ContractID, $ruleEngineQuery))->run();
                        if(!empty($ids)) {

                            foreach ($ids as $id) {
                                $ss[] = $id;
                            }
                        }
                    }

                    if(!empty($ss)) {
                        if(in_array($paymentRequestID, $ss)) {
                            $userIDs[] = $Project->user_id;
                        }
                    }
                } elseif($Project->access == 'full' || $Project->access == 'approve') {
                    $userIDs[] = $Project->user_id;
                }

                return $userIDs;
            });

//            $invoiceUsers = clone $data->where('type', 'invoice')
//                ->where('type_id', $projectID)->pluck('user_id');
//
//            $invoiceUsersWithFullAccess = $invoiceUsers->toArray();
            $InvoiceCollect = clone $data->where('type', 'invoice')->values();

            $invoiceUsersWithFullAccess = $InvoiceCollect->map(function ($Invoice) use($paymentRequestID) {
                $userIDs = [];

                if(($Invoice->access == 'full' || $Invoice->access == 'approve') && !empty($Invoice->rule_engine_query)) {
//                    $ContractIDs = DB::table('contract')
//                        ->where('project_id', $Invoice->type_id)
//                        ->pluck('contract_id');

                    $ruleEngineQuery = json_decode($Invoice->rule_engine_query, true);

                    $ids = (new RuleEngineManager('payment_request_id', $paymentRequestID, $ruleEngineQuery))->run();


                } elseif($Invoice->access == 'full' || $Invoice->access == 'approve') {
                    $userIDs[] = $Invoice->user_id;
                }

                return $userIDs;
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

            $uniqueUserIDs = array_unique(array_merge($adminRoleUserIDs, $customerUsersWithFullAccess, $contractUsersWithFullAccess, $projectUsersWithFullAccess, $invoiceUsersWithFullAccess));

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
//                    dispatch(new ProcessInvoiceApprove($invoiceNumber, $paymentRequestID, $User));
                    //$User->notify(new InvoiceApprovalNotification($invoiceNumber, $paymentRequestID, $User));
                }
            }
        }
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