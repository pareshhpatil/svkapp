<?php

namespace App\Http\Controllers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Helpers\Merchant\SubUserHelper;
use App\Helpers\RuleEngine\RuleEngineManager;
use App\Http\Controllers\AppController;
use App\Http\Requests\StoreUserRequest;
use App\Jobs\ProcessInvoiceForApprove;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use App\Model\Invoice;
use App\Model\Merchant\SubUser\SubUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;

class SubUserController extends AppController
{
    protected $subUserHelper;

    public function __construct()
    {
        $this->subUserHelper = new SubUserHelper();

        parent::__construct();
    }

    /***
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function index()
    {
        $title = 'Team Members';

        $data = Helpers::setBladeProperties($title);

        $data['subUsers'] = $this->subUserHelper->indexTableData($this->user_id);

        $data['datatablejs'] = 'table-no-export';
        $data['auth_user_role'] = Session::get('user_role');

        return view('app/merchant/subuser/index', $data);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function create()
    {
        $title = 'Create sub-user';
        $data = Helpers::setBladeProperties($title);

        $data['briqRoles'] = $this->subUserHelper->getRoles($this->merchant_id);

        return view('app/merchant/subuser/create', $data);
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function store(StoreUserRequest $request)
    {
        $status = $this->subUserHelper->storeUser($this->user_id, $request);

        if(isset($status['email_exist']) && $status['email_exist'] === true) {
            return back()->with('error', "User email already exists")->withInput();
        }

        return redirect()->to('merchant/subusers')->with('success', "Sub merchant has been created");
    }

    /**
     * Show the form for edit user.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function edit($userID)
    {
        $title = 'Edit sub-user';
        $data = Helpers::setBladeProperties($title);

        $data['briqRoles'] = $this->subUserHelper->getRoles($this->merchant_id);

        /** @var \App\User $User */
        $User = \App\User::query()
                    ->where(IColumn::USER_ID, Encrypt::decode($userID))
                    ->first();

        $data['selected_role_id'] = $User->role()->id ?? '';

        $User->user_id = Encrypt::encode($User->user_id);

        $data['user'] = $User;

        return view('app/merchant/subuser/edit', $data);
    }

    /**
     * @param $userID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function update($userID, Request $request)
    {
        $rules = [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'role' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        $this->subUserHelper->updateUser($this->user_id, $userID, $request);

        return redirect()->to('merchant/subusers')->with('success', "Sub Merchant has been updated");
    }

    /**
     * @param $userID
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function delete($userID)
    {
        $countAdminUsers = DB::table(ITable::USER)
            ->join('briq_user_roles', 'user.user_id', '=', 'briq_user_roles.user_id')
            ->where('briq_user_roles.role_name', 'Admin')
            ->select('user.user_id', 'briq_user_roles.role_name')
            ->count();

        $userID = Encrypt::decode($userID);

        /** @var \App\User $User */
        $User = \App\User::query()
                        ->where(IColumn::USER_ID, $userID)
                        ->first();

        if(!$User) {
            return redirect()->to('merchant/subusers')->with('error', "Unable to find this User!");
        }

        if($User->role()->name == 'Admin' && $countAdminUsers == 1) {
            return redirect()->to('merchant/subusers')->with('error', "At least One Active Admin is required in the system!");
        }

        $this->updateMerchantUserStatus(21, $userID);

        return redirect()->to('merchant/subusers')->with('success', "Sub Merchant has been deleted");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function verifyMail(Request $request)
    {
        $token = $request->get('token');
        $decodeToken = Encrypt::decode($token);
        $explodeDecodeToken = explode("_", $decodeToken);
        $userID = $explodeDecodeToken[0];
        $createdDate = Carbon::createFromTimestamp($explodeDecodeToken[1])->toDateTimeString();

        $checkUser = SubUser::where(IColumn::USER_ID, $userID)
                            ->where('created_date', $createdDate)
                            ->first();

        $title = 'Email Verification Successful';

        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $status = '';
        if (!empty($checkUser)) {
            if ($checkUser->user_status == 11) {
                $this->updateMerchantUserStatus(15, $userID);
                $status = 'activated';
            } else if ($checkUser->user_status == 19) {
                $this->updateMerchantUserStatus(20, $userID);
            }
        }

        $data['status'] = $status;

        return view('app/merchant/subuser/thankyou', $data);
    }

    public function updatePrivileges(Request $request)
    {
        try {
            $userID = Encrypt::decode($request->get('user_id'));
            $customerPrivileges = $request->get('customers_privileges');
            $projectsPrivileges = $request->get('projects_privileges');
            $contractsPrivileges = $request->get('contracts_privileges');
            $invoicesPrivileges = $request->get('invoices_privileges');
            $changeOrdersPrivileges = $request->get('change_orders_privileges');

            $customerPrivilegesDecode = json_decode($customerPrivileges);
            $projectsPrivilegesDecode = json_decode($projectsPrivileges);
            $contractsPrivilegesDecode = json_decode($contractsPrivileges);
            $invoicesPrivilegesDecode = json_decode($invoicesPrivileges);
            $changeOrdersPrivilegesDecode = json_decode($changeOrdersPrivileges);

            if(!empty($customerPrivilegesDecode)) {
                $existCustomers = DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'customer')
                    ->where('user_id', $userID)
                    ->pluck('type_id');

                $existCustomerTypeIDs = $existCustomers->toArray();

                $requestCustomerIDs = [];

                foreach ($customerPrivilegesDecode as $customer) {
                    $requestCustomerIDs[] = $customer->value;

                    $ruleEngine = '';
                    if($customer->access == 'full' || $customer->access == 'approve' && !empty($customer->rule_engine_query)) {
                        if(count($customer->rule_engine_query) > 0) {
                            $ruleEngine = json_encode($customer->rule_engine_query);
                        }
                    }

                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->updateOrInsert([
                            'type' => 'customer',
                            'type_id' => $customer->value,
                            'user_id' => $userID
                        ],
                            [
                                'type_label' => $customer->label,
                                'merchant_id' => $this->merchant_id,
                                'access' => $customer->access,
                                'rule_engine_query' => $ruleEngine,
                                'is_active' => 1,
                                'created_at' => Carbon::now()->toDateTimeString(),
                                'updated_at' => Carbon::now()->toDateTimeString(),
                            ]);
                }

                $customerIDsToBeDisabled = array_diff($existCustomerTypeIDs, $requestCustomerIDs);

                foreach ($customerIDsToBeDisabled as $customerIDToBeDisabled) {
                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->where('type', 'customer')
                        ->where('user_id', $userID)
                        ->where('type_id', $customerIDToBeDisabled)
                        ->update([
                            'is_active' => 0,
                            'rule_engine_query' => '',
                            'updated_at' => Carbon::now()->toDateTimeString()
                        ]);
                }

            } else {
                DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'customer')
                    ->where('user_id', $userID)
                    ->update([
                        'is_active' => 0,
                        'rule_engine_query' => '',
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            }

            if(!empty($projectsPrivilegesDecode)) {
                $existProjects = DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'project')
                    ->where('user_id', $userID)
                    ->pluck('type_id');

                $existProjectTypeIDs = $existProjects->toArray();

                $requestProjectIDs = [];
                foreach ($projectsPrivilegesDecode as $project) {
                    $requestProjectIDs[] = $project->value;

                    $ruleEngine = '';
                    if($project->access == 'full' || $project->access == 'approve' && !empty($project->rule_engine_query)) {
                        if(count($project->rule_engine_query) > 0) {
                            $ruleEngine = json_encode($project->rule_engine_query);
                        }
                    }

                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->updateOrInsert([
                            'type_id' => $project->value,
                            'user_id' => $userID,
                            'type' => 'project',
                        ],[
                            'type_label' => $project->label,
                            'merchant_id' => $this->merchant_id,
                            'access' => $project->access,
                            'rule_engine_query' => $ruleEngine,
                            'is_active' => 1,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                }

                $projectIDsToBeDisabled = array_diff($existProjectTypeIDs, $requestProjectIDs);

                foreach ($projectIDsToBeDisabled as $projectIDToBeDisabled) {
                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->where('type', 'project')
                        ->where('user_id', $userID)
                        ->where('type_id', $projectIDToBeDisabled)
                        ->update([
                            'is_active' => 0,
                            'rule_engine_query' => '',
                            'updated_at' => Carbon::now()->toDateTimeString()
                        ]);
                }
            } else {
                DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'project')
                    ->where('user_id', $userID)
                    ->update([
                        'is_active' => 0,
                        'rule_engine_query' => '',
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            }

            if(!empty($contractsPrivilegesDecode)) {
                $existContracts = DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'contract')
                    ->where('user_id', $userID)
                    ->pluck('type_id');

                $existContractTypeIDs = $existContracts->toArray();

                $requestContractIDs = [];

                foreach ($contractsPrivilegesDecode as $contract) {
                    $requestContractIDs[] = $contract->value;
                    $ruleEngine = '';
                    if($contract->access == 'full' || $contract->access == 'approve' && !empty($contract->rule_engine_query)) {
                        if(count($contract->rule_engine_query) > 0) {
                            $ruleEngine = json_encode($contract->rule_engine_query);
                        }
                    }

                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->updateOrInsert([
                            'type_id' => $contract->value,
                            'user_id' => $userID,
                            'type' => 'contract',
                        ],[
                            'type_label' => $contract->label,
                            'merchant_id' => $this->merchant_id,
                            'access' => $contract->access,
                            'rule_engine_query' => $ruleEngine,
                            'is_active' => 1,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                }

                $contractIDsToBeDisabled = array_diff($existContractTypeIDs, $requestContractIDs);

                foreach ($contractIDsToBeDisabled as $contractIDToBeDisabled) {
                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->where('type', 'contract')
                        ->where('user_id', $userID)
                        ->where('type_id', $contractIDToBeDisabled)
                        ->update([
                            'is_active' => 0,
                            'rule_engine_query' => '',
                            'updated_at' => Carbon::now()->toDateTimeString()
                        ]);
                }
            } else {
                DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'contract')
                    ->where('user_id', $userID)
                    ->update([
                        'is_active' => 0,
                        'rule_engine_query' => '',
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            }

            if(!empty($invoicesPrivilegesDecode)) {
                $existInvoices = DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'invoice')
                    ->where('user_id', $userID)
                    ->pluck('type_id');

                $existInvoiceTypeIDs = $existInvoices->toArray();

                $requestInvoiceIDs = [];
//                $notifyInvoicePrivilegesTypeIDs = [];
                foreach ($invoicesPrivilegesDecode as $invoice) {
                    $requestInvoiceIDs[] = $invoice->value;
                    $ruleEngine = '';
                    if($invoice->access == 'full' || $invoice->access == 'approve') {
//                        $notifyInvoicePrivilegesTypeIDs[] = $invoice->value;
                        if(count($invoice->rule_engine_query) > 0) {
                            $ruleEngine = json_encode($invoice->rule_engine_query);
                        }
                    }

                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->updateOrInsert([
                            'type_id' => $invoice->value,
                            'user_id' => $userID,
                            'type' => 'invoice',
                        ],[
                            'type_label' => $invoice->label,
                            'merchant_id' => $this->merchant_id,
                            'access' => $invoice->access,
                            'rule_engine_query' => $ruleEngine,
                            'is_active' => 1,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                }

                $invoiceIDsToBeDisabled = array_diff($existInvoiceTypeIDs, $requestInvoiceIDs);
//                $notifyInvoicePrivilegesTypeIDs = array_diff($notifyInvoicePrivilegesTypeIDs, $existInvoiceTypeIDs);
               
                foreach ($invoiceIDsToBeDisabled as $invoiceIDToBeDisabled) {
                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->where('type', 'invoice')
                        ->where('user_id', $userID)
                        ->where('type_id', $invoiceIDToBeDisabled)
                        ->update([
                            'is_active' => 0,
                            'rule_engine_query' => '',
                            'updated_at' => Carbon::now()->toDateTimeString()
                        ]);
                }

//                if(!empty($notifyInvoicePrivilegesTypeIDs)) {
//                    //Notify User If access is full or approve
//                    $this->invoicesTobeNotify($notifyInvoicePrivilegesTypeIDs, $userID);
//                }
            } else {
                DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'invoice')
                    ->where('user_id', $userID)
                    ->update([
                        'is_active' => 0,
                        'rule_engine_query' => '',
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            }

            if (!empty($changeOrdersPrivilegesDecode)) {
                $existChangeOrder = DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'change-order')
                    ->where('user_id', $userID)
                    ->pluck('type_id');

                $existChangeOrderTypeIDs = $existChangeOrder->toArray();

                $requestChangeOrderIDs = [];
                foreach ($changeOrdersPrivilegesDecode as $changeOrder) {
                    $requestChangeOrderIDs[] = $changeOrder->value;
                    $ruleEngine = '';
                    if($changeOrder->access == 'full' || $changeOrder->access == 'approve' && !empty($changeOrder->rule_engine_query)) {
                        if(count($changeOrder->rule_engine_query) > 0) {
                            $ruleEngine = json_encode($changeOrder->rule_engine_query);
                        }
                    }

                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->updateOrInsert([
                            'type_id' => $changeOrder->value,
                            'user_id' => $userID,
                            'type' => 'change-order',
                        ],[
                            'type_label' => $changeOrder->label,
                            'merchant_id' => $this->merchant_id,
                            'access' => $changeOrder->access,
                            'rule_engine_query' => $ruleEngine,
                            'is_active' => 1,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                }
                $changeOrderIDsToBeDisabled = array_diff($existChangeOrderTypeIDs, $requestChangeOrderIDs);

                foreach ($changeOrderIDsToBeDisabled as $changeOrderIDToBeDisabled) {
                    DB::table(ITable::BRIQ_PRIVILEGES)
                        ->where('type', 'change-order')
                        ->where('user_id', $userID)
                        ->where('type_id', $changeOrderIDToBeDisabled)
                        ->update([
                            'is_active' => 0,
                            'rule_engine_query' => '',
                            'updated_at' => Carbon::now()->toDateTimeString()
                        ]);
                }
            } else {
                DB::table(ITable::BRIQ_PRIVILEGES)
                    ->where('type', 'change-order')
                    ->where('user_id', $userID)
                    ->update([
                        'is_active' => 0,
                        'rule_engine_query' => '',
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            }

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getPrivileges($userID)
    {
        $Privileges = DB::table(ITable::BRIQ_PRIVILEGES)
                        ->where('is_active', 1)
                        ->where('user_id', Encrypt::decode($userID))
                        ->get()
                        ->collect();

        $customerPrivileges = clone $Privileges->where('type', 'customer')->values();

        $projectPrivileges = clone $Privileges->where('type', 'project')->values();
        $contractPrivileges = clone $Privileges->where('type', 'contract')->values();
        $invoicePrivileges = clone $Privileges->where('type', 'invoice')->values();
        $changeOrderPrivileges = clone $Privileges->where('type', 'change-order')->values();

        return response()->json([
            'customers_privileges' => $customerPrivileges ?? [],
            'projects_privileges' => $projectPrivileges ?? [],
            'contracts_privileges' => $contractPrivileges ?? [],
            'invoices_privileges' => $invoicePrivileges ?? [],
            'change_orders_privileges' => $changeOrderPrivileges ?? [],
        ]);
    }

    /**
     * @param $status
     * @param $userID
     * @return void
     * @author Nitish
     */
    private function updateMerchantUserStatus($status, $userID)
    {
        /** @var SubUser $SubUser */
        $SubUser = SubUser::query()
                        ->where('user_id', $userID)
                        ->first();

        $SubUser->prev_status = $SubUser->user_status;
        $SubUser->user_status = $status;

        $SubUser->save();
    }

    /**
     * @param $notifyInvoicePrivilegesTypeIDs
     * @param $userID
     * @return void
     * @author Nitish
     */
    private function invoicesTobeNotify($notifyInvoicePrivilegesTypeIDs, $userID)
    {
        $InvoicesTobeNotify = DB::table(ITable::BRIQ_PRIVILEGES)
            ->where('type', 'invoice')
            ->where('is_active', 1)
            ->where('user_id', $userID)
            ->where('merchant_id', $this->merchant_id)
            ->whereIn('type_id', $notifyInvoicePrivilegesTypeIDs)
            ->select(['user_id', 'type_id', 'rule_engine_query'])
            ->get();

        $paymentRequestIDs = [];
        foreach ($InvoicesTobeNotify as $InvoiceTobeNotify) {

            if(!empty($InvoiceTobeNotify->rule_engine_query)) {
                $ruleEngineQuery = json_decode($InvoiceTobeNotify->rule_engine_query, true);
                $ids = (new RuleEngineManager('payment_request_id', $InvoiceTobeNotify->type_id, $ruleEngineQuery))->run();

                if(!empty($ids)) {
                    if(in_array($InvoiceTobeNotify->type_id, $ids)) {
                        $paymentRequestIDs[] = $InvoiceTobeNotify->type_id;

                    }
                }
            } else {
                $paymentRequestIDs[] = $InvoiceTobeNotify->type_id;
            }
        }

        if(!empty($paymentRequestIDs)) {
            $merchant = DB::table('merchant')
                ->where('merchant_id', $this->merchant_id)
                ->first();

            $User = User::query()
                ->where('user_id', $userID)
                ->where('group_id', $merchant->group_id)
                ->first();

            foreach ($paymentRequestIDs as $paymentRequestID) {
                $paymentRequestDetail =  (new Invoice())->getInvoiceInfo($paymentRequestID, $this->merchant_id);
                ProcessInvoiceForApprove::dispatch($paymentRequestDetail->invoice_number, $paymentRequestDetail->payment_request_id, $User)->onQueue('promotion-sms-dev');
            }

        }
    }

    public function getPrivilegesPage($userID)
    {
        $title = 'Edit Team Members';

        $data = Helpers::setBladeProperties($title);

        /** @var User $User */
        $User = User::query()
            ->where('user_id', Encrypt::decode($userID))
            ->first();

        $User->user_id = Encrypt::encode($User->user_id);

        $data['user'] = $User;

        return view('app/merchant/subuser/privileges', $data);

    }

    private function changeOrderTobeNotify($notifyChangeOrderPrivilegesTypeIDs, $userID)
    {
        $ChangeOrdersTobeNotify = DB::table(ITable::BRIQ_PRIVILEGES)
            ->where('type', 'change-order')
            ->where('is_active', 1)
            ->where('user_id', $userID)
            ->where('merchant_id', $this->merchant_id)
            ->whereIn('type_id', $notifyChangeOrderPrivilegesTypeIDs)
            ->select(['user_id', 'type_id', 'rule_engine_query'])
            ->get();

        $changeOrderIds = [];
        foreach ($ChangeOrdersTobeNotify as $ChangeOrderTobeNotify) {

            if(!empty($ChangeOrderTobeNotify->rule_engine_query)) {
                $ruleEngineQuery = json_decode($ChangeOrderTobeNotify->rule_engine_query, true);
                $ids = (new RuleEngineManager('payment_request_id', $ChangeOrderTobeNotify->type_id, $ruleEngineQuery))->run();

                if(!empty($ids)) {
                    if(in_array($ChangeOrderTobeNotify->type_id, $ids)) {
                        $changeOrderIds[] = $ChangeOrderTobeNotify->type_id;

                    }
                }
            } else {
                $changeOrderIds[] = $ChangeOrderTobeNotify->type_id;
            }
        }

        if(!empty($changeOrderIds)) {
            $merchant = DB::table('merchant')
                ->where('merchant_id', $this->merchant_id)
                ->first();

            $User = User::query()
                ->where('user_id', $userID)
                ->where('group_id', $merchant->group_id)
                ->first();

            foreach ($changeOrderIds as $changeOrderId) {
//                $paymentRequestDetail =  (new Invoice())->getInvoiceInfo($paymentRequestID, $this->merchant_id);
//                ProcessInvoiceForApprove::dispatch($paymentRequestDetail->invoice_number, $paymentRequestDetail->payment_request_id, $User)->onQueue('promotion-sms-dev');
            }

        }
    }
}
