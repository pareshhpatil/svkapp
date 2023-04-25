<?php

namespace App\Http\Controllers;

use App\Constants\Models\IColumn;
use App\ContractParticular;
use App\CsiCode;
use App\Customer;
use App\Libraries\Helpers;
use App\Merchant;
use App\MerchantBillingProfile;
use App\Model\Contract;
use App\Model\Merchant\CostType;
use App\Model\Invoice;
use App\Model\Master;
use App\Model\InvoiceFormat;
use App\Libraries\Encrypt;
use App\Project;
use App\Traits\Contract\ContractParticulars;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Validator;
use Illuminate\Support\Facades\Session;
use Log;
use PHPExcel;
use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use Illuminate\Support\Str;
use App\Http\Controllers\InvoiceFormatController;

class ContractController extends Controller
{

    private $contract_model = null;
    private $masterModel = null;
    private $invoiceModel = null;
    private $merchant_id = null;
    private $user_id = null;
    private $apiController = null;
    private $costTypeModel = null;
    //    use ContractParticulars;

    public function __construct()
    {
        $this->contract_model = new Contract();
        $this->masterModel = new Master();
        $this->invoiceModel = new Invoice();
        $this->costTypeModel = new CostType();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
        $this->apiController = new APIController();
    }

    public function create($version = null)
    {
        Helpers::hasRole(2, 27);
        $title = 'create';

        $particulars = null;

        Session::put('valid_ajax', 'expense');
        if ($version != null) {
            $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract2', 'product', 'template', 'invoiceformat2'], [3, 179]);
        } else {
            $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract', 'product', 'template', 'invoiceformat2'], [3, 179]);
        }
        $data['gst_type'] = 'intra';
        $data['button'] = 'Save';

        //get last inserted ID below
        $data['prefix_id'] = 0;
        $data['contract_number'] = 'CNTRCT/' . $data['prefix_id'];

        $data['particulars'] = $particulars;
        $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
        foreach ($cust_list as $cust_data) {
            $cust_data->customer_code =  $cust_data->company_name ?? null . ' | ' . $cust_data->customer_code;
        }
        $data["cust_list"] = $cust_list;
        $data["project_id"] = 0;

        $userRole = Session::get('user_role');

        if ($userRole == 'Admin') {
            $projectPrivilegesIDs = ['all' => 'full'];
        } else {
            $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($projectPrivilegesIDs as $key => $privilegesID) {
            if ($privilegesID == 'full' || $privilegesID == 'edit' || $privilegesID == 'approve') {
                $whereProjectIDs[] = $key;
            }
        }

        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);

        // $data['csi_code'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'csi_code');

        // $data['csi_code_json'] = json_encode($data['csi_code']);

        $data['mode'] = 'create';
        $data['title'] = 'Create Contract';
        $data['contract_id'] = '';
        $data['project_id'] = '';
        $data['particulars'] = '';
        $data['contract_date'] = '';
        $data['contract_code'] = '';
        $data['bill_date'] = '';
        $data["particular_column"] = array('bill_code' => 'Bill Code', 'bill_type' => 'Bill Type', 'original_contract_amount' => 'Original Contract Amount', 'retainage_percent' => 'Retainage %', 'retainage_amount' => 'Retainage amount', 'project_code' => 'Project id', 'cost_code' => 'Cost Code', 'cost_type' => 'Cost Type', 'group' => 'Sub total group', 'bill_code_detail' => 'Bill code detail');

        if ($version != null) {
            $data['contract_id'] = '';
            $data['project_id'] = '';
            $data['particulars'] = '';
            $data['contract_date'] = '';
            $data['contract_code'] = '';
            $data['bill_date'] = '';
            $data["particular_column"] = array('bill_code' => 'Bill Code', 'bill_type' => 'Bill Type', 'original_contract_amount' => 'Original Contract Amount', 'retainage_percent' => 'Retainage %', 'retainage_amount' => 'Retainage amount', 'project_code' => 'Project id', 'cost_code' => 'Cost Code', 'cost_type' => 'Cost Type', 'group' => 'Sub total group', 'bill_code_detail' => 'Bill code detail');
            return view('app/merchant/contract/' . $title . $version, $data);
        }
        return view('app/merchant/contract/' . $title, $data);
    }


    public function loadContract($step = 1, $contract_id = null, $bulk_id = null)
    {
        $userRole = Session::get('user_role');

        if ($userRole == 'Admin') {
            $privilegesIDs = ['all' => 'full'];
        } else {
            $privilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($privilegesIDs as $key => $privilegesID) {
            if ($privilegesID == 'full' || $privilegesID == 'edit' || $privilegesID == 'approve') {
                $whereProjectIDs[] = $key;
            }
        }

        Helpers::hasRole(2, 27);
        $project_list = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);
        if (Route::getCurrentRoute()->getName() == 'contract.create.new') {
            $title = "Create";
            $needValidationOnStep2 = false;
        } else {
            $title = "Update";
            $needValidationOnStep2 = true;
        }

        $contract = null;
        $project = null;

        $contract_id = Encrypt::decode($contract_id);
        if ($contract_id != '') {
            $contract = ContractParticular::find($contract_id);
            $project = $this->getProject($contract->project_id);
        }

        if (old('project_id'))
            $project = $this->getProject(old('project_id'));

        $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract2', 'product', 'template', 'invoiceformat2'], [3, 179]);

        $data['project_list'] = $project_list;
        $data['title'] = $title;
        $data['contract'] = $contract;
        $data['step'] = $step;
        $data['contract_id'] = Encrypt::encode($contract_id);
        $data['project'] = $project;
        $data['merchant_id'] = $this->merchant_id;
        $data['needValidationOnStep2'] = $needValidationOnStep2;
        $data['bulk_id'] = 0;
        if ($step == 2 || $step == 4) {
            $data = $this->step2Data($data, $contract, $project->project_id ?? '', $step);
        }
        if ($step == 3) {
            $model = new InvoiceFormat();
            $invoiceSeq = $model->getInvoiceSequence($this->merchant_id);
            $invoiceSeq = json_decode(json_encode($invoiceSeq), 1);
            $data['invoiceSeq'] = $invoiceSeq;

            $data['project_data'] = $this->masterModel->getTableRow('project', 'id', $contract->project_id);

            $data['coveringNotes'] = $this->contract_model->getMerchantValues($this->merchant_id, 'covering_note');

            $plugins = $this->contract_model->getColumnValue('invoice_template', 'template_id', $contract->template_id, 'plugin');
            $data['template_id'] = $contract->template_id;
            $data['plugins'] = json_decode($plugins, 1);

            //find internal reminders plugin data if plugin is on
            if($data['plugins']['has_internal_reminder']==1) {
                $internal_reminders = $this->contract_model->getTableList('internal_reminders','contract_id',$contract_id);
                $data['plugins']['internal_reminders'] = json_decode(json_encode($internal_reminders), 1);
            }
            //dd($data['plugins']['internal_reminders']);
            $data['sub_users'] = json_encode($this->contract_model->getSubUsers($this->merchant_id),1);
            //$data['sub_users_array'] = $this->getKeyArrayJson(json_decode($data['sub_users'],1), 'value');
            $data['current_day'] = now()->format('l');
            $data['current_date'] = now()->format('d');
            $data['current_month_1st_date'] = now()->startOfMonth()->format('Y-m-d');
            $data['current_month_last_date'] = now()->lastOfMonth()->format('Y-m-d');
            //$data['plugins'] = json_decode('{"has_upload":1,"upload_file_label":"View document","has_signature":1,"has_cc":"1","cc_email":[],"has_partial":"1","partial_min_amount":"50","has_covering_note":"1","default_covering_note":0,"save_revision_history":"1","invoice_output":"1","has_aia_license":"1","has_watermark":"1","watermark_text":"DRAFT"}', 1);
        }

        if ($bulk_id != null) {
            $bulk_id = Encrypt::decode($bulk_id);
            $particulars = $this->contract_model->getColumnValue('staging_contract', 'bulk_id', $bulk_id, 'particulars');
            $data['particulars'] = json_decode($particulars, 1);
            $total = 0;
            foreach ($data['particulars'] as $row) {
                if ($row['original_contract_amount'] > 0) {
                    $total = $total + $row['original_contract_amount'];
                }
            }
            $data['total'] = $total;
            $data['bulk_id'] = $bulk_id;
        }
        
        $data['post_url'] = '/merchant/contract/store';

        return view('app/merchant/contract/createv6', $data);
    }

    public function getCostTypes(): array
    {
        return CostType::where(IColumn::MERCHANT_ID, $this->merchant_id)
            ->select(['id as value', DB::raw('CONCAT(abbrevation, " - ", name) as label')])
            ->get()->toArray();
    }

    public function store(Request $request)
    {
        $step = $request->step;
        $contract = null;
        if ($request->contract_id) {
            $contract = ContractParticular::find(Encrypt::decode($request->contract_id));
            $bulk_id = $contract->bulk_id;
        }
        switch ($step) {
            case 1:
                $response = $this->step1Store($request, $contract);

                if ($response instanceof ContractParticular) {
                    $contractPrivilegesAccess = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
                    $projectPrivilegesAccess = json_decode(Redis::get('project_privileges_' . $this->user_id), true);

                    if (isset($projectPrivilegesAccess[$response->project_id])) {
                        $contractPrivilegesAccess[$response->contract_id] = $projectPrivilegesAccess[$response->project_id];
                    } else {
                        $contractPrivilegesAccess[$response->contract_id] = 'edit';
                    }

                    Redis::set('contract_privileges_' . $this->user_id, json_encode($contractPrivilegesAccess));

                    $contract = $response;
                } else {
                    return $response;
                }

                $step++;
                break;
            case 2:
                $contract = $this->step2Store($request, $contract);
                $step++;
                break;
            case 3:
                //dd($request->all());
                if ($request->template_id != '') {
                    $template_id = $request->template_id;
                } else {
                    $template_id = $this->saveInvoiceFormat($contract->contract_code);
                    $this->contract_model->updateTable('contract', 'contract_id', $contract->contract_id, 'template_id', $template_id);
                }
                if (isset($request->sequence_number)) {
                    $this->contract_model->updateTable('project', 'id', $contract->project_id, 'sequence_number', $request->sequence_number);
                }
                $invoice_format = new InvoiceFormatController();
                $_POST = json_decode(json_encode($request->all()), 1);
                $plugins = $invoice_format->getPlugins();

                if (isset($request->has_schedule_value)) {
                    $plugins = json_decode($plugins, 1);
                    $plugins['has_schedule_value'] = "1";
                    $plugins = json_encode($plugins);
                }
               
                $this->contract_model->updateTable('invoice_template', 'template_id', $template_id, 'plugin', $plugins);
                $step++;
                break;
            case 4:
                $contract->update(['status' => 1]);
                if ($bulk_id > 0) {
                    $this->contract_model->updateTable('bulk_upload', 'bulk_upload_id', $bulk_id, 'status', 5);
                }
                return redirect()->route('contract.list.new');
                break;
        }

        return redirect()->route('contract.' . strtolower($request->title) . '.new', ['step' => $step, 'contract_id' => Encrypt::encode($contract->contract_id)]);
    }

    public function step1Store(Request $request, $contract)
    {
        $validator = Validator::make($request->all(), $this->informationRules());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $data = $validator->validated();

        $data['contract_date'] = Helpers::sqlDate($data['contract_date']);
        $data['bill_date'] = Helpers::sqlDate($data['bill_date']);
        $data['created_by'] = $this->user_id;
        $data['last_update_by'] = $this->user_id;
        $data['created_date'] = date('Y-m-d H:i:s');

        if (is_null($contract)) {
            $template_id = $this->saveInvoiceFormat($data['contract_code']);
            $data['template_id'] = $template_id;
            $contract = ContractParticular::create($data);
        } else {
            $data = $this->checkIfProjectIsChanged($data, $contract);
            $contract->update($data);
        }
        return $contract;
    }

    private function saveInvoiceFormat($name)
    {
        $invoice_format_json = '{"design_name":null,"design_color":null,"template_name":"' . $name . '","billingProfile_id":"0","main_header_id":["5","6","7","8"],"main_header_datatype":["text","number","email","textarea"],"main_header_column_id":["0","0","0","0"],"main_header_name":["Company name","Merchant contact","Merchant email","Merchant address"],"hcheck":["0","1","2","3"],"cust_column_id":["0","0","0","0"],"customer_column_id":["1","2","3","4"],"customer_column_type":["customer","customer","customer","customer"],"customer_column_name":["Customer code","Contact person name","Email ID","Mobile no"],"customer_datatype":["primary","text","email","mobile"],"ccheck":["0","1","2","3"],"column_config":["{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"metadata\",\"headermandatory\":0,\"headercolumnposition\":1,\"function_id\":9,\"function_param\":\"system_generated\",\"function_val\":\"\",\"headerisdelete\":1,\"headerdatatype\":\"text\"}","{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"request\",\"headermandatory\":1,\"headercolumnposition\":4,\"function_id\":0,\"function_param\":\"\",\"function_val\":\"\",\"headerisdelete\":0,\"headerdatatype\":\"text\"}","{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"request\",\"headermandatory\":1,\"headercolumnposition\":5,\"function_id\":0,\"function_param\":\"\",\"function_val\":\"\",\"headerisdelete\":0,\"headerdatatype\":\"date\"}","{\"position\":\"R\",\"column_type\":\"H\",\"headertablesave\":\"request\",\"headermandatory\":1,\"headercolumnposition\":6,\"function_id\":0,\"function_param\":\"\",\"function_val\":\"\",\"headerisdelete\":0,\"headerdatatype\":\"date\"}"],"column_id":["0","0","0","0"],"headercolumn":["Invoice Number","Billing cycle name","Bill date","Due date"],"pc_sr_no":"#","particular_col":["item","description","total_amount"],"pc_item":"Item","pc_annual_recurring_charges":"Annual recurring charges","pc_sac_code":"Sac Code","pc_description":"Desc","pc_product_number":"Product number","pc_product_expiry_date":"Product Expiry date","pc_qty":"Quantity","pc_unit_type":"Unit type","pc_mrp":"MRP","pc_rate":"Rate","pc_gst":"GST","pc_tax_amount":"Tax amount","pc_discount_perc":"Discount %","pc_discount":"Discount","pc_total_amount":"Amount","pc_narrative":"Narrative","tax_total":"Tax total","tnc":null,"upload_file_label":"View document","has_upload":"1","partial_min_amount":"50","has_watermark":"1","watermark_text":"DRAFT","is_covering":"1","default_covering":"0","custom_subject":"Payment request from %COMPANY_NAME%","custom_sms":"You have received a payment request from %COMPANY_NAME% for amount %TOTAL_AMOUNT%. To make an online payment, access your bill via %SHORT_URL%","reminder":["3","1","0"],"reminder_subject":[null,null,null],"reminder_sms":[null,null,null],"has_online_payments":"0","enable_payments":"0","is_revision":"1","template_type":"construction","template_id":null,"custmized_receipt_fields":null}';
        $format_request = json_decode($invoice_format_json);
        $_POST = json_decode($invoice_format_json, 1);
        $invoice_format = new InvoiceFormatController();

        $template_id = $invoice_format->saveInvoiceFormat($format_request, '');
        $invoice_format->saveMetadata($format_request, $template_id);
        return $template_id;
    }

    private function checkIfProjectIsChanged($data, $contract)
    {
        if ($data['project_id'] != $contract->project_id) {
            $particulars = json_decode($contract->particulars, true);
            $bill_codes = CsiCode::where('project_id', $data['project_id'])->get()->pluck('code')->toArray();
            $newParticulars = [];
            foreach ($particulars as $particular) {
                if (!in_array($particular['bill_code'], $bill_codes))
                    $particular['bill_code'] = '';
                $newParticulars[] = $particular;
            }
            $data['particulars'] = $newParticulars;
        }
        return $data;
    }

    public function step2Store(Request $request, $contract)
    {
        return $contract;
    }

    public function step2Data($data, $contract, $project_id, $step)
    {
        [$total, $groups, $particulars] = $contract->calculateTotal();

        if ($step == 3) {
            $data['particulars'] = ($contract != null && !empty($particulars)) ? $particulars : [];
        } else {
            $data['particulars'] = ($contract != null && !empty($particulars)) ? $particulars : ContractParticular::initializeParticulars($project_id);
        }
        $data['bill_codes'] = $this->getBillCodes($contract->project_id);
        $data['cost_types'] = $this->getCostTypes();

        $merchant_cost_types_array = $this->getKeyArrayJson($data['cost_types'], 'value');
        $data['cost_types_array'] = $merchant_cost_types_array;
        $data['csi_codes_array'] = $this->getKeyArrayJson($data['bill_codes'], 'value');

        $data['project_id'] = $contract->project_id;
        $data['total'] = $total;
        $data['groups'] = $groups;
        $data['row'] = ContractParticular::$row;

        return $data;
    }

    private function getKeyArrayJson($array, $key)
    {
        $data = [];
        foreach ($array as $row) {
            $data[$row[$key]] = $row;
        }
        return json_encode($data);
    }


    public function getBillCodes($project_id)
    {
        return CsiCode::where('project_id', $project_id)
            ->select(['id as value', DB::raw('CONCAT(code, " | ", title) as label'), 'description'])
            ->where('merchant_id', $this->merchant_id)
            ->where('is_active', 1)
            ->get()->toArray();
    }

    public function getProject($id)
    {
        return Project::where('id', $id)->where('project.is_active', 1)
            ->join('customer', 'customer.customer_id', 'project.customer_id')
            ->select([
                'id', 'project_id',  'project_name', 'project.customer_id', 'project.merchant_id',
                'company_name', 'sequence_number',
                DB::raw("concat(project.customer_id, ' | ' ,company_name) as customer_company_code"),
                'customer.email', 'customer.mobile', DB::raw("concat(first_name,' ', last_name) as name")
            ])
            ->first();
    }

    private function informationRules(): array
    {
        return [
            'contract_code' => 'required',
            'merchant_id' => 'required',
            'version' => 'required',
            'contract_amount' => 'required',
            'customer_id' => 'required',
            'project_id' => 'required',
            'contract_date' => 'required',
            'bill_date' => 'required',
            'billing_frequency' => 'required',
            'project_address' => 'required',
            'owner_address' => 'required',
            'contractor_address' => 'required',
            'architect_address' => 'required'
        ];
    }

    public function fetchProject(Request $request): \Illuminate\Http\JsonResponse
    {
        $project = $this->getProject($request->project_id);

        $contract = null;
        $owner_address = null;
        $project_address = null;
        $contractor_address = null;
        $architect_address = null;

        if (!is_null($request->contract_id))
            $contract = ContractParticular::find(Encrypt::decode($request->contract_id));

        if (is_null($request->contract_id) || $contract->project_id != $request->project_id)
            $contract = $this->getLastContractWithSameProject($request->project_id);

        if (is_null($contract) && $request->route_name == 'contract.create.new') {
            $customer = Customer::find($project->customer_id);
            $owner_address = $customer->address;
            $contractor = MerchantBillingProfile::where('merchant_id', $project->merchant_id)->first();
            $contractor_address = $contractor->address ?? null;
        }
        if (!is_null($contract)) {
            $owner_address = $contract->owner_address;
            $contractor_address = $contract->contractor_address;
            $project_address = $contract->project_address;
            $architect_address = $contract->architect_address;
        }

        return response()->json(array(
            'project' => $project, 'owner_address' => $owner_address,
            'project_address' => $project_address, 'contractor_address' => $contractor_address,
            'architect_address' => $architect_address
        ), 200);
    }

    public function getLastContractWithSameProject($project_id)
    {
        return ContractParticular::where('project_id', $project_id)
            ->where('is_active', 1)
            ->where('status', 1)->orderBy('created_date', 'desc')->first();
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_date' => 'required',
            'contract_date' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $main_array = [];
            $request->totalcost = str_replace(',', '', $request->totalcost);
            $request->contract_amount = str_replace(',', '', $request->contract_amount);
            foreach ($request->bill_code as $skey => $bill_code) {
                if (isset($request->bill_type[$skey])) {
                    $row_array = [];
                    $row_array["bill_code"] = $bill_code;
                    $row_array["description"] = $request->description[$skey];
                    $row_array["bill_type"] = $request->bill_type[$skey];
                    $row_array["original_contract_amount"] = str_replace(',', '', $request->original_contract_amount[$skey]);
                    if ($row_array["original_contract_amount"] ==  null || $row_array["original_contract_amount"] ==  '') {
                        $row_array["original_contract_amount"] = 0;
                    }
                    $row_array["retainage_percent"] = $request->retainage_percent[$skey];
                    if ($row_array["retainage_percent"] ==  null || $row_array["retainage_percent"] ==  '') {
                        $row_array["retainage_percent"] = 0;
                    }
                    $row_array["retainage_amount"] = str_replace(',', '', $request->retainage_amount[$skey]);
                    if ($row_array["retainage_amount"] ==  null || $row_array["retainage_amount"] ==  '') {
                        $row_array["retainage_amount"] = 0;
                    }
                    $row_array["project"] = $request->project[$skey];
                    $row_array["cost_code"] = $request->cost_code[$skey];
                    $row_array["cost_type"] = $request->cost_type[$skey];
                    $row_array["group_code1"] = $request->group_code1[$skey];
                    $row_array["group_code2"] = $request->group_code2[$skey];
                    $row_array["group_code3"] = $request->group_code3[$skey];
                    $row_array["group_code4"] = $request->group_code4[$skey];
                    $row_array["group_code5"] = $request->group_code5[$skey];
                    $row_array["calculated_perc"] = $request->calculated_perc[$skey];
                    $row_array["calculated_row"] = $request->calculated_row[$skey];
                    $row_array["pint"] = $request->pint[$skey];
                    array_push($main_array, $row_array);
                }
            }
            $request->particulars = json_encode($main_array);
            $request->bill_date = Helpers::sqlDate($request->bill_date);
            $request->contract_date = Helpers::sqlDate($request->contract_date);
            $id = $this->contract_model->saveNewContract($request, $this->merchant_id, $this->user_id);
            return redirect('merchant/contract/list')->with('success', "Contract has been created");
        }
    }

    public function saveV4(Request $request)
    {
        $id = $request->link;
        $contract = ContractParticular::find($id);
        $contract->update(['status' => 1]);

        return redirect('merchant/contract/list')->with('success', "Contract has been created");
    }

    public function saveV5(Request $request)
    {
        $id = Encrypt::decode($request->link);
        $contract = ContractParticular::find($id);
        $contract->update(['status' => 1]);

        return redirect('merchant/contract/list')->with('success', "Contract has been created");
    }

    public function list(Request $request)
    {
        $dates = Helpers::setListDates();
        $title = 'Contract list';
        $data = Helpers::setBladeProperties($title,  [],  [5, 179]);
        $data['cancel_status'] = isset($request->cancel_status) ? $request->cancel_status : 0;
        $data['project_id'] = isset($request->project_id) ? $request->project_id : '';
        $list = $this->contract_model->getContractList($this->merchant_id, $dates['from_date'],  $dates['to_date'],  $data['project_id']);
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->contract_id);
        }
        $data['list'] = $list;
        $userRole = Session::get('user_role');

        if ($userRole == 'Admin') {
            $projectPrivilegesIDs = ['all' => 'full'];
        } else {
            $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($projectPrivilegesIDs as $key => $privilegesID) {
            if ($privilegesID == 'full') {
                $whereProjectIDs[] = $key;
            }
        }

        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);
        $data['datatablejs'] = 'table-no-export';
        $data['hide_first_col'] = 1;
        $data['customer_name'] = 'Contact person name';
        $data['customer_code'] = 'Customer code';

        if (Session::has('customer_default_column')) {
            $default_column = Session::get('customer_default_column');
            $data['customer_name'] = isset($default_column['customer_name']) ? $default_column['customer_name'] : 'Contact person name';
            $data['customer_code'] = isset($default_column['customer_code']) ? $default_column['customer_code'] : 'Customer code';
        }

        return view('app/merchant/contract/list', $data);
    }

    public function listContracts(Request $request)
    {
        $dates = Helpers::setListDates();
        $title = 'Contract list';
        $data = Helpers::setBladeProperties($title,  [],  [5, 179]);
        $data['cancel_status'] = isset($request->cancel_status) ? $request->cancel_status : 0;
        $data['project_id'] = isset($request->project_id) ? $request->project_id : '';
        $userRole = Session::get('user_role');

        //store last search criteria into Redis
        $redis_items = $this->getSearchParamRedis('contract_list', $this->merchant_id);

        //find last search criteria into Redis 
        if (isset($redis_items['contract_list']['search_param']) && $redis_items['contract_list']['search_param'] != null) {
            $data['from_date'] = $dates['from_date'] = Helpers::sqlDate($redis_items['contract_list']['search_param']['from_date']);
            $data['to_date'] = $dates['to_date'] = Helpers::sqlDate($redis_items['contract_list']['search_param']['to_date']);
            $data['project_id'] = $redis_items['contract_list']['search_param']['project_id'];
        }
        //$data['showLastRememberSearchCriteria'] = true;
        //get contract privileges from redis
        if ($userRole == 'Admin') {
            $privilegesIDs = ['all' => 'full'];
        } else {
            $privilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        }

        $list = $this->contract_model->getPrivilegesContractList($this->merchant_id, $dates['from_date'],  $dates['to_date'], $data['project_id'], array_keys($privilegesIDs));
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->contract_id);
        }
        $data['list'] = $list;
        $userRole = Session::get('user_role');

        if ($userRole == 'Admin') {
            $projectPrivilegesIDs = ['all' => 'full'];
        } else {
            $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($projectPrivilegesIDs as $key => $privilegesID) {
            if ($privilegesID == 'full' || $privilegesID == 'edit' || $privilegesID == 'approve' || $privilegesID == 'view-only') {
                $whereProjectIDs[] = $key;
            }
        }

        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);
        $data['datatablejs'] = 'table-no-export-tablestatesave';  //table-no-export old value
        $data['hide_first_col'] = 1;
        $data['list_name'] = 'contract_list';
        $data['customer_name'] = 'Contact person name';
        $data['customer_code'] = 'Customer code';
        $data['privileges'] = $privilegesIDs;

        if (Session::has('customer_default_column')) {
            $default_column = Session::get('customer_default_column');
            $data['customer_name'] = isset($default_column['customer_name']) ? $default_column['customer_name'] : 'Contact person name';
            $data['customer_code'] = isset($default_column['customer_code']) ? $default_column['customer_code'] : 'Customer code';
        }

        return view('app/merchant/contract/list-contract', $data);
    }

    public function delete($link)
    {
        if ($link) {
            $id = Encrypt::decode($link);
            $this->masterModel->deleteTableRow('contract', 'contract_id', $id, $this->merchant_id, $this->user_id);
            return redirect('merchant/contract/list')->with('success', "Record has been deleted");
        } else {
            return redirect('merchant/contract/list')->with('error', "Record code can not be deleted");
        }
    }

    public function update($link)
    {
        $title = 'Update';
        $id = Encrypt::decode($link);
        if ($id != '') {
            $model = new Master();
            $row = $model->getTableRow('contract', 'contract_id', $id);
            $row->json_particulars = json_decode($row->particulars, true);
            $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
            foreach ($cust_list as $cust_data) {
                $cust_data->customer_code =  (is_null($cust_data->company_name ?? null)) ? $cust_data->customer_code :  $cust_data->company_name ?? null . ' | ' . $cust_data->customer_code;
            }
            if ($row->version != '') {
                $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract', 'product', 'template', 'invoiceformat2'], [3]);
            } else {
                $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [3]);
            }

            $data["cust_list"] = $cust_list;
            $userRole = Session::get('user_role');

            if ($userRole == 'Admin') {
                $projectPrivilegesIDs = ['all' => 'full'];
            } else {
                $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
            }

            $whereProjectIDs = [];
            foreach ($projectPrivilegesIDs as $key => $privilegesID) {
                if ($privilegesID == 'full') {
                    $whereProjectIDs[] = $key;
                }
            }
            $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);

            $data["default_particulars"] = [];
            $data["default_particulars"]["bill_code"] = 'Bill Code';
            $data["default_particulars"]["bill_type"] = 'Bill Type (% Complete or Unit)';
            $data["default_particulars"]["original_contract_amount"] = 'Original Contract Amount';
            $data["default_particulars"]["retainage_percent"] = 'Retainage %';
            $data["default_particulars"]["retainage_amount"] = 'Retainage Amount';
            $data["default_particulars"]["project"] = 'Project';
            $data["default_particulars"]["cost_code"] = 'Cost Code';
            $data["default_particulars"]["cost_type"] = 'Cost Type';
            $data["default_particulars"]["group_code1"] = 'Group Code 1';
            $data["default_particulars"]["group_code2"] = 'Group Code 2';
            $data["default_particulars"]["group_code3"] = 'Group Code 3';
            $data["default_particulars"]["group_code4"] = 'Group Code 4';
            $data["default_particulars"]["group_code5"] = 'Group Code 5';

            $data['csi_code'] = $this->invoiceModel->getMerchantValues($this->merchant_id, 'csi_code');

            $data['csi_code_json'] = json_encode($data['csi_code']);
            $data['detail'] = $row;
            $data['contract_id'] = $row->contract_id;
            $data['contract_code'] = $row->contract_code;
            $data['project_id'] = $row->project_id;
            $data['particulars'] = $row->particulars;
            $data['contract_date'] = Helpers::htmlDate($row->contract_date);
            $data['bill_date'] = Helpers::htmlDate($row->bill_date);
            $data['link'] = $link;
            $data['mode'] = 'update';
            $data["particular_column"] = array('bill_code' => 'Bill Code', 'bill_type' => 'Bill Type', 'original_contract_amount' => 'Original Contract Amount', 'retainage_percent' => 'Retainage %', 'retainage_amount' => 'Retainage amount', 'project_code' => 'Project id', 'cost_code' => 'Cost Code', 'cost_type' => 'Cost Type', 'group' => 'Sub total group', 'bill_code_detail' => 'Bill code detail');

            if ($row->version != '') {
                return view('app/merchant/contract/create' . $row->version, $data);
            }

            return view('app/merchant/contract/update', $data);
        } else {
            return redirect('/404');
        }
    }

    public function updatesave(Request $request)
    {
        $id = Encrypt::decode($request->link);
        $main_array = [];
        $retain_amount = 0;

        foreach ($request->bill_code as $skey => $bill_code) {

            if (isset($request->original_contract_amount[$skey])) {
                $row_array = [];
                $request->totalcost = str_replace(',', '', $request->totalcost);
                $row_array["bill_code"] = $bill_code;
                $row_array["description"] = $request->description[$skey];
                $row_array["bill_type"] = $request->bill_type[$skey];
                $row_array["original_contract_amount"] = (isset($request->original_contract_amount[$skey])) ? str_replace(',', '', $request->original_contract_amount[$skey]) : 0;
                $row_array["retainage_percent"] = $request->retainage_percent[$skey] ?? 0;
                $row_array["retainage_amount"] = isset($request->retainage_amount[$skey]) ? str_replace(',', '', $request->retainage_amount[$skey]) : "";
                $row_array["project"] = $request->project_code[$skey] ?? '';
                $row_array["cost_code"] = $request->cost_code[$skey] ?? '';
                $row_array["cost_type"] = $request->cost_type[$skey] ?? '';
                $row_array["group"] = $request->group[$skey] ?? '';

                $row_array["calculated_perc"] = $request->calculated_perc[$skey] ?? 0;
                $row_array["calculated_row"] = $request->calculated_row[$skey] ?? 0;
                $row_array["pint"] = $request->pint[$skey] ?? '';
                array_push($main_array, $row_array);
                $retain_amount = ($row_array["original_contract_amount"] * $row_array["retainage_percent"]) + $retain_amount;
            }
        }

        $request->particulars = json_encode($main_array);
        $request->bill_date = Helpers::sqlDate($request->bill_date);
        $request->contract_date = Helpers::sqlDate($request->contract_date);
        $this->contract_model->updateContract($request, $this->merchant_id, $this->user_id, $id);
        return redirect('merchant/contract/list')->with('success', "Contract has been updated");
    }

    public function updatesaveV4(Request $request)
    {
        $id = $request->link;
        $main_array = [];
        $retain_amount = 0;
        $formData = $request->form_data;

        $contract = ContractParticular::find($id);
        $contract->update([
            'particulars' => $formData, 'project_id' => $request->project_id,
            'contract_amount' => str_replace(',', '', $request->totalcost),
            'contract_code' => $request->contract_code, 'billing_frequency' => $request->billing_frequency,
            'contract_date' => Helpers::sqlDate($request->contract_date), 'bill_date' => Helpers::sqlDate($request->bill_date)
        ]);

        return redirect('merchant/contract/list')->with('success', "Contract has been updated");
    }

    public function updatesaveV5(Request $request)
    {
        $id = Encrypt::decode($request->link);
        $main_array = [];
        $retain_amount = 0;
        $formData = $request->form_data;

        $contract = ContractParticular::find($id);
        $contract->update([
            'particulars' => $formData, 'project_id' => $request->project_id,
            'contract_amount' => str_replace(',', '', $request->totalcost),
            'contract_code' => $request->contract_code, 'billing_frequency' => $request->billing_frequency,
            'contract_date' => Helpers::sqlDate($request->contract_date), 'bill_date' => Helpers::sqlDate($request->bill_date)
        ]);

        return redirect('merchant/contract/list')->with('success', "Contract has been updated");
    }

    public function updatesavev6(Request $request)
    {
        $id = Encrypt::decode($request->link);
        $formData = $request->form_data;
        $contract = ContractParticular::find($id);

        $particulars = json_decode($formData, true);
        $particulars = json_decode($particulars, true);
        $array = ['bill_code', 'bill_type', 'original_contract_amount', 'retainage_percent', 'retainage_amount', 'project', 'project_code', 'cost_code', 'cost_type', 'group', 'bill_code_detail'];
        foreach ($particulars as $k => $v) {
            foreach ($array as $a) {
                if (isset($v['show' . $a])) {
                    unset($particulars[$k]['show' . $a]);
                }
            }
        }
        $contract->update(['particulars' => json_encode($particulars), 'contract_amount' => $request->contract_amount, 'bulk_id' => $request->bulk_id]);

        return response()->json(array('message' => 'Particulars saved properly'), 200);
    }

    public function getprojectdetails($project_id)
    {
        $data = $this->contract_model->getAllProjectDetails($project_id);
        return $data;
    }

    public function billcodesave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $id = $this->contract_model->saveNewBillCode($request, $this->merchant_id, $this->user_id);
            $data  = [$request->bill_code, $request->bill_description, $id];


            return $data;
        }
    }

    public function newBillCode(Request $request)
    {
        $billCode = CsiCode::create([
            'code' => $request->bill_code,
            'title' => $request->bill_description,
            'description' => $request->bill_description,
            'project_id' => $request->project_id,
            'merchant_id' => $this->merchant_id
        ]);

        return response()->json(['message' => 'Bill code is created successfully', 'billCode' => $billCode]);
    }

    public function billcodeupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $id = $this->contract_model->updateBillcode($request, $this->merchant_id, $this->user_id);
            // $data  = [$request->bill_code, $request->bill_description, $request->bill_id,];
            $id = Encrypt::encode($request->project_id);
            return redirect('/merchant/code/list/' . $id)->with('success', "Record has been updated");
        }
    }

    /*api function - delete contract  */

    public function deleteContract(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'contract_id' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json($this->apiController->APIResponse(0, '', $validator->errors()), 422);
            }
            $this->masterModel->deleteTableRow('contract', 'contract_id', $request->contract_id, $request->merchant_id, $request->user_id);
            $response['contract_id'] = $request->contract_id;
            return response()->json($this->apiController->APIResponse('', $response), 200);
        } catch (Exception $e) {
            Log::error('Error while deleting contract :' . $e->getMessage());
        }
    }

    public function getContractList(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'start' => 'numeric',
            'limit' => 'numeric',
            'project_id' => 'numeric'
        ]);
        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0, '', $validator->errors()), 422);
        }
        $start = ($request->start > 0) ? $request->start : -1;
        $limit = ($request->limit > 0) ? $request->limit : 15;
        $from_date = isset($request->from_date) ? Helpers::sqlDate($request->from_date) : date('Y-m-d', strtotime(date('01 M Y')));
        $to_date = isset($request->to_date) ? Helpers::sqlDate($request->to_date) : date('Y-m-d', strtotime(date('d M Y')));

        $list = $this->contract_model->getContractList($request->merchant_id, $from_date,  $to_date,  $request->project_id, $start, $limit);
        $response['lastno'] = count($list) + $start;
        $response['list'] = $list;
        return response()->json($this->apiController->APIResponse('', $response), 200);
    }

    public function getContractDetails($contract_id)
    {
        if ($contract_id != null) {
            $contractDetails = $this->contract_model->getContractData($contract_id);
            $contractDetails->particulars = json_decode($contractDetails->particulars, true);
            return response()->json($this->apiController->APIResponse('', $contractDetails), 200);
        }
    }

    public function createContract(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_code' => 'required',
            'project_id' => 'required',
            'contract_date' => 'required',
            'bill_date' => 'required',
            'billing_frequency' => 'required',
            'project_address' => 'required',
            'owner_address' => 'required',
            'contractor_address' => 'required',
            'architect_address' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0, '', $validator->errors()), 422);
        }

        $data = $validator->validated();

        $project = $this->getProject($request->project_id);

        $data['customer_id'] = $project->customer_id;
        $data['contract_date'] = Helpers::sqlDate($data['contract_date']);
        $data['bill_date'] = Helpers::sqlDate($data['bill_date']);
        $data['created_by'] = $request->user_id;
        $data['last_update_by'] = $request->user_id;
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['merchant_id'] = $request->merchant_id;
        $data['particulars'] = $request->particulars;

        $contract_amt = 0;
        if (!empty($data['particulars'])) {
            foreach ($data['particulars'] as $p => $particular) {
                //dd($particular);
                if ($particular['bill_code'] == null || $particular['cost_type'] == null || $particular['original_contract_amount'] == null || $particular['bill_type'] == null) {
                    return response()->json($this->apiController->APIResponse('ER02061'), 422);
                } else if (!in_array($particular['bill_type'], array('% Complete', 'Unit', 'Calculated', 'Cost'))) {
                    return response()->json($this->apiController->APIResponse('ER02062'), 422);
                } else {
                    $billCode =  $this->contract_model->getTableRow('csi_code', 'id', $particular['bill_code']);
                    $data['particulars'][$p]['description'] = $billCode->description;
                    $data['particulars'][$p]['introw'] = $p;
                    $data['particulars'][$p]['pint'] = $p;
                    $data['particulars'][$p]['project'] = $project->project_id;

                    //find cost type and fetch id or save as new cost type
                    $data['particulars'][$p]['cost_type'] = $this->costTypeModel->createCostType($particular['cost_type'], $request->merchant_id, $request->user_id);

                    if ($particular['bill_type'] != 'Calculated') {
                        $data['particulars'][$p]['calculated_perc'] = null;
                        $data['particulars'][$p]['calculated_row'] = null;
                    } else {
                    }
                    if ($particular['retainage_percent'] > 0) {
                        $data['particulars'][$p]['retainage_amount'] = $particular['original_contract_amount'] * $particular['retainage_percent'] / 100;
                    } else {
                        $data['particulars'][$p]['retainage_amount'] = 0;
                    }
                    $data['particulars'][$p]['show'] = false;
                    $contract_amt = $contract_amt + $particular['original_contract_amount'];
                }
            }
        }

        $data['contract_amount'] = $contract_amt;
        $data['particulars'] = json_encode($data['particulars']);
        $data['status'] = 1;
        $data['version'] = "v1";
        $contract = ContractParticular::create($data);
        if ($contract != null) {
            return response()->json($this->apiController->APIResponse('', $contract), 200);
        } else {
            return response()->json($this->apiController->APIResponse('ER02057'), 422);
        }
    }

    public function updateContract(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required',
            'contract_code' => 'required',
            'project_id' => 'required',
            'contract_date' => 'required',
            'bill_date' => 'required',
            'billing_frequency' => 'required',
            'project_address' => 'required',
            'owner_address' => 'required',
            'contractor_address' => 'required',
            'architect_address' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0, '', $validator->errors()), 422);
        }

        $data = $validator->validated();
        $project = $this->getProject($request->project_id);
        $data['customer_id'] = $project->customer_id;
        $data['contract_date'] = Helpers::sqlDate($data['contract_date']);
        $data['bill_date'] = Helpers::sqlDate($data['bill_date']);
        $data['created_by'] = $request->user_id;
        $data['last_update_by'] = $request->user_id;
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['merchant_id'] = $request->merchant_id;
        $data['particulars'] = $request->particulars;

        if ($request->contract_id) {
            $contract = ContractParticular::find($request->contract_id);

            $contract_amt = 0;
            if (!empty($data['particulars'])) {
                foreach ($data['particulars'] as $p => $particular) {
                    //dd($particular);
                    if ($particular['bill_code'] == null || $particular['cost_type'] == null || $particular['original_contract_amount'] == null || $particular['bill_type'] == null) {
                        return response()->json($this->apiController->APIResponse('ER02061'), 422);
                    } else if (!in_array($particular['bill_type'], array('% Complete', 'Unit', 'Calculated', 'Cost'))) {
                        return response()->json($this->apiController->APIResponse('ER02062'), 422);
                    } else {
                        $billCode =  $this->contract_model->getTableRow('csi_code', 'id', $particular['bill_code']);
                        if ($billCode->project_id == $request->project_id) {
                            $data['particulars'][$p]['description'] = $billCode->description;
                            $data['particulars'][$p]['introw'] = $p;
                            $data['particulars'][$p]['pint'] = $p;
                            $data['particulars'][$p]['project'] = $project->project_id;

                            //find cost type and fetch id or save as new cost type
                            $data['particulars'][$p]['cost_type'] = $this->costTypeModel->createCostType($particular['cost_type'], $request->merchant_id, $request->user_id);

                            if ($particular['bill_type'] != 'Calculated') {
                                $data['particulars'][$p]['calculated_perc'] = null;
                                $data['particulars'][$p]['calculated_row'] = null;
                            } else {
                            }
                            if ($particular['retainage_percent'] > 0) {
                                $data['particulars'][$p]['retainage_amount'] = $particular['original_contract_amount'] * $particular['retainage_percent'] / 100;
                            } else {
                                $data['particulars'][$p]['retainage_amount'] = 0;
                            }
                            $data['particulars'][$p]['show'] = false;
                            $contract_amt = $contract_amt + $particular['original_contract_amount'];
                        } else {
                            $p = $p + 1;
                            $res = $this->apiController->APIResponse('ER02063');
                            $res['errmsg'] = $res['errmsg'] . ' for ' . $p . ' particulars row';
                            return response()->json($res, 422);
                        }
                    }
                }
            }

            $data['contract_amount'] = $contract_amt;
            $data['particulars'] = json_encode($data['particulars']);
            $contract->update($data);
            if ($contract != null) {
                return response()->json($this->apiController->APIResponse('', $contract), 200);
            } else {
                return response()->json($this->apiController->APIResponse('ER02057'), 422);
            }
        }
    }
    public function custom_internal_reminder(Request $request) {
        $response = array();
        $response['status'] = 0;
        if($request->all()) {
            if($request->repeat_occurences!='') {
                $response['row_id'] = $request->rowId;
                $response['repeat_every'] = $request->repeat_occurences;
                $response['repeat_type'] = $request->repeat_type;

                $summary = 'Repeat every '. $request->repeat_occurences. ' '. $request->repeat_type;

                if($request->repeat_type=='week') {
                    $week=[];
                    foreach($request->week_days_selected as $wk=>$val) {
                        if($val!=null) {
                            $week[]=$val;
                        }
                    }
                    $response['repeat_on'] = json_encode($week,1);
                    $summary.= '<br/>' .implode(",",$week);
                } else if($request->repeat_type=='month') {
                    $response['repeat_on'] = $request->repeat_on;
                    $summary.= '<br/>'. str_replace('_',' ',$request->repeat_on);
                }
                $response['status'] = 1;
                $response['summary'] = $summary;
            } else {
                $response['status'] = 0;
            }
        }
        
        return json_encode($response);
    }
}
