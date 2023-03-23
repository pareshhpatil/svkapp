<?php

namespace App\Helpers\Merchant;

use App\Helpers\RuleEngine\RuleEngineManager;
use App\Jobs\ProcessInvoiceForApprove;
use App\Libraries\Encrypt;
use App\Model\Invoice;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

/**
 * @author Nitish
 */
class InvoiceHelper
{
    public function sendInvoiceForApprovalNotification($paymentRequestID)
    {
        $merchantID = Encrypt::decode(Session::get('merchant_id'));
        $authUserID = Encrypt::decode(Session::get('userid'));

        $paymentRequestDetail =  (new Invoice())->getInvoiceInfo($paymentRequestID, $merchantID);

        if (!empty($paymentRequestDetail)) {
            $invoiceNumber = $paymentRequestDetail->invoice_number;
            if (substr($paymentRequestDetail->invoice_number, 0, 16) == 'System generated') {
                $invoiceNumber = (new Invoice())->getAutoInvoiceNo((substr($paymentRequestDetail->invoice_number, 16)));
            }

            //Update User Privileges array
            $privilegesInvoiceIDs = json_decode(Redis::get('invoice_privileges_' . $authUserID), true);

            $privilegesInvoiceIDs[$paymentRequestID] = 'edit';

            Redis::set('invoice_privileges_' . $authUserID, json_encode($privilegesInvoiceIDs));

            Session::put('invoice_privileges', json_encode($privilegesInvoiceIDs));

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

            $customerUsersWithFullAccess = $CustomerCollect->map(function ($Customer) use($paymentRequestDetail, $customerID) {

                if($Customer->type_id == $customerID || $Customer->type_id == 'all') {
                    if(!empty($Customer->rule_engine_query)) {
                        $ruleEngineQuery = json_decode($Customer->rule_engine_query, true);
                        $stat = (new RuleEngineManager('customer_id', $Customer->type_id, $ruleEngineQuery))->run();

                        if(!empty($stat)) {
                            return $Customer->user_id;
                        }
                    } else {
                        return $Customer->user_id;
                    }
                }

                return '';
            });

            $customerUsersWithFullAccess = $customerUsersWithFullAccess->filter(function ($value) {
                return !empty($value);
            });

            $ContractCollect = clone $data->where('type', 'contract')
                ->whereIn('type_id', [$contractID, 'all'])
                ->values();

            $contractUsersWithFullAccess = $ContractCollect->map(function ($Contract) use($paymentRequestDetail, $contractID) {

                if($Contract->type_id == $contractID || $Contract->type_id == 'all') {
                    if(!empty($Contract->rule_engine_query)) {
                        $ruleEngineQuery = json_decode($Contract->rule_engine_query, true);
                        $stat = (new RuleEngineManager('contract_id', $Contract->type_id, $ruleEngineQuery))->run();

                        if(!empty($stat)) {
                            return $Contract->user_id;
                        }
                    } else {
                        return $Contract->user_id;
                    }
                }

                return '';
            });

            $contractUsersWithFullAccess = $contractUsersWithFullAccess->filter(function ($value) {
                return !empty($value);
            });

            $ProjectCollect = clone $data->where('type', 'project')
                ->whereIn('type_id', [$projectID,'all'])
                ->values();

            $projectUsersWithFullAccess = $ProjectCollect->map(function ($Project) use($paymentRequestDetail, $projectID) {

                if($Project->type_id == $projectID || $Project->type_id == 'all') {
                    if(!empty($ProjectCollect->rule_engine_query)) {
                        $ContractIDs = DB::table('contract')
                            ->where('project_id', $ProjectCollect->type_id)
                            ->pluck('contract_id');

                        $ruleEngineQuery = json_decode($ProjectCollect->rule_engine_query, true);

                        $contractInvoiceIds = [];
                        foreach ($ContractIDs as $ContractID) {
                            $ids = (new RuleEngineManager('contract_id', $ContractID, $ruleEngineQuery))->run();
                            if(!empty($ids)) {

                                foreach ($ids as $id) {
                                    $contractInvoiceIds[] = $id;
                                }
                            }
                        }

                        if(!empty($contractInvoiceIds)) {
                            return $Project->user_id;
                        }
                    } else {
                        return $Project->user_id;
                    }
                }

                return '';
            });

            $projectUsersWithFullAccess = $projectUsersWithFullAccess->filter(function ($value) {
                return !empty($value);
            });

            $InvoiceCollect = clone $data->where('type', 'invoice')
                ->whereIn('type_id', [$paymentRequestID,'all'])->values();

            $invoiceUsersWithFullAccess = $InvoiceCollect->map(function ($Invoice) use($paymentRequestDetail) {
                $userIDs = [];

                if($Invoice->type_id == $paymentRequestDetail->payment_request_id || $Invoice->type_id == 'all') {
                    if(!empty($Invoice->rule_engine_query)) {
                        $ruleEngineQuery = json_decode($Invoice->rule_engine_query, true);
                        $ids = (new RuleEngineManager('payment_request_id', $Invoice->type_id, $ruleEngineQuery))->run();

                        if(!empty($ids)) {
                            if(in_array($paymentRequestDetail->payment_request_id, $ids)) {
                                $userIDs[] = $Invoice->user_id;
                            }
                        }
                    } else {
                        $userIDs[] = $Invoice->user_id;
                    }
                }

                return $userIDs;
            });

            $invoiceUsersWithFullAccess = $invoiceUsersWithFullAccess->filter(function ($value) {
                return !empty($value);
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

            $uniqueUserIDs = array_unique(array_merge($adminRoleUserIDs, $customerUsersWithFullAccess->toArray(), $contractUsersWithFullAccess->toArray(), $projectUsersWithFullAccess->toArray(), $invoiceUsersWithFullAccess->toArray()));

            $Users = User::query()
                ->whereIn('user_id', $uniqueUserIDs)
                ->whereIn('user_status', [20, 15, 12, 16])
                ->get();

            foreach ($Users as $User) {
                ProcessInvoiceForApprove::dispatch($invoiceNumber, $paymentRequestID, $User)->onQueue(env('SQS_USER_NOTIFICATION'));
            }
        }
    }
}