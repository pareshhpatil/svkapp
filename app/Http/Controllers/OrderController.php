<?php

namespace App\Http\Controllers;

use App\Helpers\Merchant\ChangeOrderHelper;
use App\Libraries\Helpers;
use App\Model\Contract;
use App\Model\Invoice;
use App\Model\Master;
use App\Model\Order;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Redis;
use Validator;
use Illuminate\Support\Facades\Session;
use Log;
use PHPExcel;
use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;

class OrderController extends Controller
{

    private $contract_model = null;
    private $masterModel = null;
    private $invoiceModel = null;
    private $orderModel = null;
    private $merchant_id = null;
    private $user_id = null;
    private $apiController = null;

    public function __construct()
    {
        $this->contract_model = new Contract();
        $this->masterModel = new Master();
        $this->invoiceModel = new Invoice();
        $this->orderModel = new Order();
        $this->apiController = new APIController();

        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    public function create($version = '', $errors = null, $link = null, $type = null, Request $request)
    {
        Helpers::hasRole(2, 27);
        $title = 'create';

        $particulars = null;

        Session::put('valid_ajax', 'expense');
        $userRole = Session::get('user_role');

        $data = Helpers::setBladeProperties(ucfirst($title) . ' change order', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [3, 180]);
        $data['gst_type'] = 'intra';
        $data['button'] = 'Save';

        $data['particulars'] = $particulars;
        $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
        foreach ($cust_list as $cust_data) {
            $cust_data->customer_code =  $cust_data->company_name ?? null . ' | ' . $cust_data->customer_code ?? null;
        }
        $data["cust_list"] = $cust_list;

        $userRole = Session::get('user_role');

        if($userRole == 'Admin') {
            $projectPrivilegesIDs = ['all' => 'full'];
        } else {
            $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($projectPrivilegesIDs as $key => $privilegesID) {
            if($privilegesID == 'full') {
                $whereProjectIDs[] = $key;
            }
        }

        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);

        if($userRole == 'Admin') {
            $contractPrivilegesIDs = ['all' => 'full'];
        } else {
            //get privileges from redis
            $contractPrivilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        }

        //contracts from privileges
        $whereContractIDs = [];
        foreach ($contractPrivilegesIDs as $key => $contractPrivilegesID) {
            if($contractPrivilegesID == 'full') {
                $whereContractIDs[] = $key;
            }
        }

        $data['contract'] = $this->invoiceModel->getContract($this->merchant_id, $whereContractIDs, $userRole);

        $data['project_id'] = 0;
        if (isset($request->contract_id)) {
            $data['contract_id'] = $request->contract_id;
            $model = new Master();
            $row = $model->getTableRow('contract', 'contract_id', $request->contract_id);
            $row->json_particulars = json_decode($row->particulars, true);
            $data['detail'] = $row;

            $group_codes = [];
            foreach($row->json_particulars as $row_particular){
                if(isset($row_particular['group'])){
                    if (!in_array($row_particular['group'], $group_codes)){
                        if($row_particular['group'] !=''){
                            array_push($group_codes,$row_particular['group']);
                        }
                    }
                }
            }
            $data['group_codes'] = $group_codes;
            $data['group_codes_json'] =  json_encode($data['group_codes']);

            $data['project_details'] = $model->getTableRow('project', 'id', $row->project_id);
            $data['project_id'] = $row->project_id;

            $data['csi_code'] = $model->getProjectCodeList($this->merchant_id, $row->project_id);
            $data['csi_code_json'] = json_encode($data['csi_code']);

            $data['cost_type_list'] = $this->orderModel->getCostTypeList($this->merchant_id);
            $data['cost_type_list_json'] = json_encode($data['cost_type_list']);
        } else {
            $data['contract_id'] = '';
        }

        $data["default_particulars"] = [];
        $data["default_particulars"]["bill_code"] = 'Bill Code';
        $data["default_particulars"]["cost_type"] = 'Cost Type';
        $data["default_particulars"]["original_contract_amount"] = 'Original Contract Amount';
        $data["default_particulars"]["retainage_percent"] = 'Retainage Percentage';
        $data["default_particulars"]["unit"] = 'Unit';
        $data["default_particulars"]["rate"] = 'Rate';
        $data["default_particulars"]["change_order_amount"] = 'Change Order Amount';
        $data["default_particulars"]["order_description"] = 'Description';
        $data["default_particulars"]["group"] = 'Group';
        $data["default_particulars"]["sub_group"] = 'Sub Group';

        $data['mode'] = 'create';
        $data['title'] = 'Change Order';

        return view('app/merchant/order/createv2', $data);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_no' => 'required',
            'order_date' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $main_array = [];
            $request->totalcost = str_replace(',', '', $request->totalcost);
            $request->contract_amount = str_replace(',', '', $request->contract_amount);
            $request->total_original_contract_amount = str_replace(',', '', $request->total_original_contract_amount);
            $request->total_change_order_amount = str_replace(',', '', $request->total_change_order_amount);
            if (!empty($request->bill_code)) {
                foreach ($request->bill_code as $skey => $bill_code) {
                    if ($request->original_contract_amount[$skey] == '') {
                        $request->original_contract_amount[$skey] = 0;
                    }
                    $row_array = [];
                    $row_array["bill_code"] = $bill_code;
                    $row_array["description"] = $request->description[$skey];
                    $row_array["original_contract_amount"] = str_replace(',', '', $request->original_contract_amount[$skey]);
                    $row_array["unit"] = str_replace(',', '', $request->unit[$skey]);
                    $row_array["rate"] = str_replace(',', '', $request->rate[$skey]);
                    $row_array["change_order_amount"] = str_replace(',', '', $request->change_order_amount[$skey]);
                    $row_array["order_description"] = $request->order_description[$skey];
                    $row_array["cost_type"] = $request->cost_type[$skey];
                    $row_array["retainage_percent"] = $request->retainage_percent[$skey];
                    $row_array["pint"] = $request->pint[$skey];
                    $row_array["group"] = $request->group[$skey];
                    $row_array["sub_group"] = $request->sub_group[$skey];
                    array_push($main_array, $row_array);
                }
            }
            $request->particulars = json_encode($main_array);
            $request->order_date = Helpers::sqlDate($request->order_date);
            $id = $this->orderModel->saveNewOrder($request, $this->merchant_id, $this->user_id);

            $InvoiceHelper = new ChangeOrderHelper();

            $InvoiceHelper->sendChangeOrderForApprovalNotification($id);

            return redirect('merchant/order/list')->with('success', "Change Order has been created");
        }
    }

    public function list(Request $request)
    {
        $dates = Helpers::setListDates();
        $title = 'Change Order list';
        $data = Helpers::setBladeProperties($title,  [],  [5, 180]);
        $data['cancel_status'] = isset($request->cancel_status) ? $request->cancel_status : 0;
        $data['contract_id'] = isset($request->contract_id) ? $request->contract_id : '';
        $userRole = Session::get('user_role');

        //find last search criteria into Redis
        $redis_items = $this->getSearchParamRedis('change_order_list', $this->merchant_id);

        if (isset($redis_items['change_order_list']['search_param']) && $redis_items['change_order_list']['search_param'] != null) {
            $data['from_date'] = $dates['from_date'] = Helpers::sqlDate($redis_items['change_order_list']['search_param']['from_date']);
            $data['to_date'] = $dates['to_date'] = Helpers::sqlDate($redis_items['change_order_list']['search_param']['to_date']);
            $data['contract_id'] = $redis_items['change_order_list']['search_param']['contract_id'];
        }

        if($userRole == 'Admin') {
            $privilegesIDs = ['all' => 'full'];
            $contractPrivilegesIDs = ['all' => 'full'];
        } else {
            //get privileges from redis
            $privilegesIDs = json_decode(Redis::get('change_order_privileges_' . $this->user_id), true);
            $contractPrivilegesIDs = json_decode(Redis::get('contract_privileges_' . $this->user_id), true);
        }

        $list = $this->orderModel->getOrderList($this->merchant_id, $dates['from_date'],  $dates['to_date'],  $data['contract_id'], array_keys($privilegesIDs));
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->order_id);
        }
        $data['list'] = $list;
        $userRole = Session::get('user_role');

        if($userRole == 'Admin') {
            $projectPrivilegesIDs = ['all' => 'full'];
        } else {
            $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($projectPrivilegesIDs as $key => $privilegesID) {
            if($privilegesID == 'full') {
                $whereProjectIDs[] = $key;
            }
        }

        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);
        $data['datatablejs'] = 'table-no-export-tablestatesave';  //table-no-export old value
        $data['hide_first_col'] = 1;
        $data['list_name'] = 'change_order_list';
        $data['customer_name'] = 'Contact person name';
        $data['customer_code'] = 'Customer code';
        $data['privileges'] = $privilegesIDs;

        //contracts from privileges
        $whereContractIDs = [];
        foreach ($contractPrivilegesIDs as $key => $contractPrivilegesID) {
            if($contractPrivilegesID == 'full' || $contractPrivilegesID == 'edit' || $contractPrivilegesID == 'view-only' || $contractPrivilegesID == 'approve') {
                $whereContractIDs[] = $key;
            }
        }

        $data['contract'] = $this->invoiceModel->getContract($this->merchant_id, $whereContractIDs, $userRole);

        if (Session::has('customer_default_column')) {
            $default_column = Session::get('customer_default_column');
            $data['customer_name'] = isset($default_column['customer_name']) ? $default_column['customer_name'] : 'Contact person name';
            $data['customer_code'] = isset($default_column['customer_code']) ? $default_column['customer_code'] : 'Customer code';
        }

        return view('app/merchant/order/list', $data);
    }

    public function delete($link)
    {
        if ($link) {
            $id = Encrypt::decode($link);
            $this->masterModel->deleteTableRow('order', 'order_id', $id, $this->merchant_id, $this->user_id);
            return redirect('merchant/order/list')->with('success', "Record has been deleted");
        } else {
            return redirect('merchant/order/list')->with('error', "Record code can not be deleted");
        }
    }

    public function approve(Request $request)
    {
        if (isset($request->link)) {
            $id = Encrypt::decode($request->link);
            $request->approved_date = Helpers::sqlDate($request->approved_date);
            $this->orderModel->changeOrderApproveStatus($id, $request->approved_date, '1', '');
            return redirect('merchant/order/list')->with('success', "Change order has been Approved");
        } else {
            return redirect('merchant/order/list')->with('error', "Change order code can not be Approved");
        }
    }

    public function unapprove(Request $request)
    {
        if (isset($request->link)) {
            $id = Encrypt::decode($request->link);
            $this->orderModel->changeOrderApproveStatus($id, '', '0', $request->unapprove_message);
            return redirect('merchant/order/list')->with('success', "Change order has been Unapproved");
        } else {
            return redirect('merchant/order/list')->with('error', "Change order code can not be Unapproved");
        }
    }

    public function update($link)
    {
        $title = 'Update';
        $data = Helpers::setBladeProperties(ucfirst($title) . ' change order', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [3]);
        $id = Encrypt::decode($link);
        if ($id != '') {
            $model = new Master();
            $row = $model->getTableRow('order', 'order_id', $id);
            $row->json_particulars = json_decode($row->particulars, true);
            foreach ($row->json_particulars as &$row_data) {
                if (!isset($row_data["cost_type"])) {
                    $row_data["cost_type"] = null;
                }
            }
            $group_codes = [];
            foreach($row->json_particulars as $row_particular){
                if(isset($row_particular['group'])){
                    if (!in_array($row_particular['group'], $group_codes)){
                        if($row_particular['group'] !=''){
                            array_push($group_codes,$row_particular['group']);
                        }
                    }
                }
                
            }
            $data['group_codes'] = $group_codes;
            $data['group_codes_json'] =  json_encode($data['group_codes']);

            $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
            foreach ($cust_list as $cust_data) {
                $cust_data->customer_code =  $cust_data->company_name == null ? $cust_data->customer_code :  $cust_data->company_name . ' | ' . $cust_data->customer_code;
            }
            $data["cust_list"] = $cust_list;
            $userRole = Session::get('user_role');

            if($userRole == 'Admin') {
                $projectPrivilegesIDs = ['all' => 'full'];
            } else {
                $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
            }

            $whereProjectIDs = [];
            foreach ($projectPrivilegesIDs as $key => $privilegesID) {
                if($privilegesID == 'full') {
                    $whereProjectIDs[] = $key;
                }
            }

            $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);

            $data["default_particulars"] = [];
            $data["default_particulars"]["bill_code"] = 'Bill Code';
            $data["default_particulars"]["cost_type"] = 'Cost Type';
            $data["default_particulars"]["original_contract_amount"] = 'Original Contract Amount';
            $data["default_particulars"]["retainage_percent"] = 'Retainage Percentage';
            $data["default_particulars"]["unit"] = 'Unit';
            $data["default_particulars"]["rate"] = 'Rate';
            $data["default_particulars"]["change_order_amount"] = 'Change Order Amount';
            $data["default_particulars"]["order_description"] = 'Description';
            $data["default_particulars"]["group"] = 'Group';
            $data["default_particulars"]["sub_group"] = 'Sub Group';

            $row2 = $model->getTableRow('contract', 'contract_id', $row->contract_id);
            $data['csi_code'] = $model->getProjectCodeList($this->merchant_id, $row2->project_id);
            $data['csi_code_json'] = json_encode($data['csi_code']);

            $data['cost_type_list'] = $this->orderModel->getCostTypeList($this->merchant_id);
            $data['cost_type_list_json'] = json_encode($data['cost_type_list']);

            $data['project_details'] = $model->getTableRow('project', 'id', $row2->project_id);
            $data['project_id'] = $row2->project_id;

            $data['detail'] = $row;
            $data['detail2'] = $row2;
            $data['link'] = $link;
            $data['mode'] = 'update';
            return view('app/merchant/order/update', $data);
        } else {
            return redirect('/404');
        }
    }

    public function approved($link)
    {
        $title = 'Approved';
        $data = Helpers::setBladeProperties(ucfirst($title) . ' change order', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [3]);
        $id = Encrypt::decode($link);
        if ($id != '') {
            $model = new Master();
            $row = $model->getTableRow('order', 'order_id', $id);
            $row->json_particulars = json_decode($row->particulars, true);
            $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
            foreach ($cust_list as $cust_data) {
                $cust_data->customer_code =  $cust_data->company_name == null ? $cust_data->customer_code :  $cust_data->company_name . ' | ' . $cust_data->customer_code;
            }
            $data["cust_list"] = $cust_list;
            $userRole = Session::get('user_role');

            if($userRole == 'Admin') {
                $projectPrivilegesIDs = ['all' => 'full'];
            } else {
                $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
            }

            $whereProjectIDs = [];
            foreach ($projectPrivilegesIDs as $key => $privilegesID) {
                if($privilegesID == 'full') {
                    $whereProjectIDs[] = $key;
                }
            }
            $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);

            $data["default_particulars"] = [];
            $data["default_particulars"]["bill_code"] = 'Bill Code';
            $data["default_particulars"]["cost_type"] = 'Cost Type';
            $data["default_particulars"]["original_contract_amount"] = 'Original Contract Amount';
            $data["default_particulars"]["retainage_percent"] = 'Retainage Percentage';
            $data["default_particulars"]["unit"] = 'Unit';
            $data["default_particulars"]["rate"] = 'Rate';
            $data["default_particulars"]["change_order_amount"] = 'Change Order Amount';
            $data["default_particulars"]["order_description"] = 'Description';
            $data["default_particulars"]["group"] = 'Group';
            $data["default_particulars"]["sub_group"] = 'Sub Group';

            $row2 = $model->getTableRow('contract', 'contract_id', $row->contract_id);
            $data['csi_code'] = $model->getProjectCodeList($this->merchant_id, $row2->project_id);
            $data['csi_code_json'] = json_encode($data['csi_code']);

            $data['cost_type_list'] = $this->orderModel->getCostTypeList($this->merchant_id);
            $data['cost_type_list_json'] = json_encode($data['cost_type_list']);

            $data['project_details'] = $model->getTableRow('project', 'id', $row2->project_id);
            $data['project_id'] = 0;
            $data['detail'] = $row;
            $data['detail2'] = $row2;
            $data['link'] = $link;
            $data['mode'] = 'update';
            return view('app/merchant/order/approved', $data);
        } else {
            return redirect('/404');
        }
    }

    public function updatesave(Request $request)
    {
        $id = Encrypt::decode($request->link);
        $main_array = [];
        $request->totalcost = str_replace(',', '', $request->totalcost);
        $request->contract_amount = str_replace(',', '', $request->contract_amount);
        $request->total_original_contract_amount = str_replace(',', '', $request->total_original_contract_amount);
        $request->total_change_order_amount = str_replace(',', '', $request->total_change_order_amount);
        foreach ($request->bill_code as $skey => $bill_code) {
            $row_array = [];
            if ($request->original_contract_amount[$skey] == '') {
                $request->original_contract_amount[$skey] = 0;
            }
            $row_array = [];
            $row_array["bill_code"] = $bill_code;
            $row_array["description"] = $request->description[$skey];
            $row_array["original_contract_amount"] = str_replace(',', '', $request->original_contract_amount[$skey]);
            $row_array["unit"] = str_replace(',', '', $request->unit[$skey]);
            $row_array["rate"] = str_replace(',', '', $request->rate[$skey]);
            $row_array["change_order_amount"] = str_replace(',', '', $request->change_order_amount[$skey]);
            $row_array["order_description"] = $request->order_description[$skey];
            $row_array["cost_type"] = $request->cost_type[$skey];
            $row_array["retainage_percent"] = $request->retainage_percent[$skey];
            $row_array["group"] = $request->group[$skey];
            $row_array["sub_group"] = $request->sub_group[$skey];
            $row_array["pint"] = $request->pint[$skey];
            array_push($main_array, $row_array);
        }
        $request->particulars = json_encode($main_array);
        $request->order_date = Helpers::sqlDate($request->order_date);
        $this->orderModel->updateOrder($request, $this->merchant_id, $this->user_id, $id);
        return redirect('merchant/order/list')->with('success', "Change Order has been updated");
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

    public function getchangeOrderList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'numeric',
            'limit' => 'numeric',
            'contract_id' => 'numeric'
        ]);
        if ($validator->fails()) {
            return response()->json($this->apiController->APIResponse(0, '', $validator->errors()), 422);
        }
        $start = ($request->start > 0) ? $request->start : -1;
        $limit = ($request->limit > 0) ? $request->limit : 15;
        $from_date = isset($request->from_date) ? Helpers::sqlDate($request->from_date) : date('Y-m-d', strtotime(date('01 M Y')));
        $to_date = isset($request->to_date) ? Helpers::sqlDate($request->to_date) : date('Y-m-d', strtotime(date('d M Y')));

        $list = $this->orderModel->getOrderList($request->merchant_id, $from_date, $to_date, $request->contract_id, $start, $limit);
        foreach ($list as $ck => $row) {
            if($row->status==0) {
                $list[$ck]->status = 'Pending';
            } else {
                $list[$ck]->status = 'Approved';
            }
        }
        //$list = $this->contract_model->getContractList($request->merchant_id, $from_date,  $to_date,  $request->project_id,$start,$limit);
        $response['lastno'] = count($list) + $start;
        $response['list'] = $list;
        return response()->json($this->apiController->APIResponse('', $response), 200);
    }

    public function getOrderDetails($order_id) {
        if ($order_id != null) {
            $info =  $this->orderModel->getOrderData($order_id);
            $info->particulars = json_decode($info->particulars,true);
            if($info->status==0) {
                $info->status = 'Pending';
            } else {
                $info->status = 'Approved';
            }
            return response()->json($this->apiController->APIResponse('', $info), 200);
        }
    }
}
