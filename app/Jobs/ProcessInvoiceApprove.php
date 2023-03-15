<?php

namespace App\Jobs;

use App\Helpers\RuleEngine\RuleEngineManager;
use App\Model\Invoice;
use App\Notifications\InvoiceApprovalNotification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessInvoiceApprove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $merchantID;
    protected $paymentRequestID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paymentRequestID, $merchantID)
    {
        $this->paymentRequestID = $paymentRequestID;
        $this->merchantID = $merchantID;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $paymentRequestDetail =  (new Invoice())->getInvoiceInfo($this->paymentRequestID, $this->merchantID);

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
                ->where('merchant_id', $this->merchantID)
                ->where('is_active', 1)
                ->where('type', '!=', 'change-order')
                ->whereIn('access', ['full','approve'])
                ->get()->collect();


            $CustomerCollect = clone $data->where('type', 'customer')
                ->whereIn('type_id', [$customerID, 'all'])
                ->values();

            $customerUsersWithFullAccess = $CustomerCollect->map(function ($Customer) use($paymentRequestDetail, $customerID) {
//                $userIDs = [];

                if($Customer->type_id == $customerID || $Customer->type_id == 'all') {
                    if(!empty($Customer->rule_engine_query)) {
                        $ruleEngineQuery = json_decode($Customer->rule_engine_query, true);
                        $stat = (new RuleEngineManager('customer_id', $Customer->type_id, $ruleEngineQuery))->run();

                        if(!empty($stat)) {
//                            if(in_array($paymentRequestDetail->payment_request_id, $stat)) {
                            return $Customer->user_id;
//                            }
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
//                $userIDs = [];

                if($Contract->type_id == $contractID || $Contract->type_id == 'all') {
                    if(!empty($Contract->rule_engine_query)) {
                        $ruleEngineQuery = json_decode($Contract->rule_engine_query, true);
                        $stat = (new RuleEngineManager('contract_id', $Contract->type_id, $ruleEngineQuery))->run();

                        if(!empty($stat)) {
                            return $Contract->user_id;
//                            if(in_array($paymentRequestDetail->payment_request_id, $stat)) {
//                                $userIDs[] = $Contract->user_id;
//                            }
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
//                $userIDs = [];

                if($Project->type_id == $projectID || $Project->type_id == 'all') {
                    if(!empty($ProjectCollect->rule_engine_query)) {
                        $ContractIDs = DB::table('contract')
                            ->where('project_id', $Project->type_id)
                            ->pluck('contract_id');

                        $ruleEngineQuery = json_decode($Project->rule_engine_query, true);

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
//                            if(in_array($paymentRequestDetail->payment_request_id, $contractInvoiceIds)) {
//                                $userIDs[] = $Project->user_id;
                                return $Project->user_id;
//                            }
                        }
                    } else {
//                        $userIDs[] = $Project->user_id;
                        return $Project->user_id;
                    }
                }

                return '';
            });

            $projectUsersWithFullAccess = $projectUsersWithFullAccess->filter(function ($value) {
                return !empty($value);
            });

            $InvoiceCollect = clone $data->where('type', 'invoice')
                ->whereIn('type_id', [$this->paymentRequestID,'all'])->values();

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
                ->where('merchant_id', $this->merchantID)
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
                ->get();

            foreach ($Users as $User) {
                    $User->notify(new InvoiceApprovalNotification($invoiceNumber, $this->paymentRequestID, $User));
            }
        }
    }
}
