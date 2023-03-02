<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Model\Contract;
use App\Model\Invoice;
use App\Model\Master;
use App\Model\Order;
use App\Libraries\Encrypt;
use Validator;
use Illuminate\Support\Facades\Session;
use Log;
use PHPExcel;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $contract_model = null;
    private $masterModel = null;
    private $invoiceModel = null;
    private $orderModel = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->contract_model = new Contract();
        $this->masterModel = new Master();
        $this->invoiceModel = new Invoice();
        $this->orderModel = new Order();



        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    public function create($version = '', $errors = null, $link = null, $type = null, Request $request)
    {
        Helpers::hasRole(2, 27);
        $title = 'create';

        $particulars = null;

        Session::put('valid_ajax', 'expense');
        $data = Helpers::setBladeProperties(ucfirst($title) . ' change order', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [3, 180]);
        $data['gst_type'] = 'intra';
        $data['button'] = 'Save';

        $data['particulars'] = $particulars;
        $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
        foreach ($cust_list as $cust_data) {
            $cust_data->customer_code =  $cust_data->company_name ?? null . ' | ' . $cust_data->customer_code ?? null;
        }
        $data["cust_list"] = $cust_list;
        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id);

        $data['contract'] = $this->invoiceModel->getContract($this->merchant_id);

        $data['project_id'] = 0;
        if (isset($request->contract_id)) {
            $data['contract_id'] = $request->contract_id;
            $model = new Master();
            $row = $model->getTableRow('contract', 'contract_id', $request->contract_id);
            $row->json_particulars = json_decode($row->particulars, true);

            $data['detail'] = $row;

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
                    array_push($main_array, $row_array);
                }
            }
            $request->particulars = json_encode($main_array);
            $request->order_date = Helpers::sqlDate($request->order_date);
            $id = $this->orderModel->saveNewOrder($request, $this->merchant_id, $this->user_id);
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

        //find last search criteria into Redis 
        $redis_items = $this->getSearchParamRedis('change_order_list', $this->merchant_id);

        if (isset($redis_items['change_order_list']['search_param']) && $redis_items['change_order_list']['search_param'] != null) {
            $data['from_date'] = $dates['from_date'] = Helpers::sqlDate($redis_items['change_order_list']['search_param']['from_date']);
            $data['to_date'] = $dates['to_date'] = Helpers::sqlDate($redis_items['change_order_list']['search_param']['to_date']);
            $data['contract_id'] = $redis_items['change_order_list']['search_param']['contract_id'];
        }

        $list = $this->orderModel->getOrderList($this->merchant_id, $dates['from_date'],  $dates['to_date'],  $data['contract_id']);
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->order_id);
        }
        $data['list'] = $list;
        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id);
        $data['datatablejs'] = 'table-no-export-tablestatesave';  //table-no-export old value
        $data['hide_first_col'] = 1;
        $data['list_name'] = 'change_order_list';
        $data['customer_name'] = 'Contact person name';
        $data['customer_code'] = 'Customer code';

        $data['contract'] = $this->invoiceModel->getContract($this->merchant_id);

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
            $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
            foreach ($cust_list as $cust_data) {
                $cust_data->customer_code =  $cust_data->company_name == null ? $cust_data->customer_code :  $cust_data->company_name . ' | ' . $cust_data->customer_code;
            }
            $data["cust_list"] = $cust_list;
            $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id);

            $data["default_particulars"] = [];
            $data["default_particulars"]["bill_code"] = 'Bill Code';
            $data["default_particulars"]["cost_type"] = 'Cost Type';
            $data["default_particulars"]["original_contract_amount"] = 'Original Contract Amount';
            $data["default_particulars"]["retainage_percent"] = 'Retainage Percentage';
            $data["default_particulars"]["unit"] = 'Unit';
            $data["default_particulars"]["rate"] = 'Rate';
            $data["default_particulars"]["change_order_amount"] = 'Change Order Amount';
            $data["default_particulars"]["order_description"] = 'Description';

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
            $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id);

            $data["default_particulars"] = [];
            $data["default_particulars"]["bill_code"] = 'Bill Code';
            $data["default_particulars"]["cost_type"] = 'Cost Type';
            $data["default_particulars"]["original_contract_amount"] = 'Original Contract Amount';
            $data["default_particulars"]["retainage_percent"] = 'Retainage Percentage';
            $data["default_particulars"]["unit"] = 'Unit';
            $data["default_particulars"]["rate"] = 'Rate';
            $data["default_particulars"]["change_order_amount"] = 'Change Order Amount';
            $data["default_particulars"]["order_description"] = 'Description';

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
}
