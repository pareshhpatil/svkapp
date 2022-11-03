<?php

namespace App\Http\Controllers;

use App\ContractParticular;
use App\Libraries\Helpers;
use App\Model\Contract;
use App\Model\Invoice;
use App\Model\Master;
use App\Libraries\Encrypt;
use Validator;
use Illuminate\Support\Facades\Session;
use Log;
use PHPExcel;
use Illuminate\Http\Request;

class ContractController extends Controller
{

    private $contract_model = null;
    private $masterModel = null;
    private $invoiceModel = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->contract_model = new Contract();
        $this->masterModel = new Master();
        $this->invoiceModel = new Invoice();

        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
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
            $cust_data->customer_code =  $cust_data->company_name . ' | ' . $cust_data->customer_code;
        }
        $data["cust_list"] = $cust_list;
        $data["project_id"] = 0;
        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id);

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
        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id);
        $data['datatablejs'] = 'table-no-export';
        $data['hide_first_col'] = 1;
        $data['customer_name'] = 'Customer name';
        $data['customer_code'] = 'Customer code';

        if (Session::has('customer_default_column')) {
            $default_column = Session::get('customer_default_column');
            $data['customer_name'] = isset($default_column['customer_name']) ? $default_column['customer_name'] : 'Customer name';
            $data['customer_code'] = isset($default_column['customer_code']) ? $default_column['customer_code'] : 'Customer code';
        }

        return view('app/merchant/contract/list', $data);
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
        $model = new Master();
        $row = $model->getTableRow('contract', 'contract_id', $id);
        $row->json_particulars = json_decode($row->particulars, true);
        $cust_list = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
        foreach ($cust_list as $cust_data) {
            $cust_data->customer_code =  $cust_data->company_name == null ? $cust_data->customer_code :  $cust_data->company_name . ' | ' . $cust_data->customer_code;
        }
        if ($row->version != '') {
            $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract', 'product', 'template', 'invoiceformat2'], [3]);
        } else {
            $data = Helpers::setBladeProperties(ucfirst($title) . ' contract', ['expense', 'contract', 'product', 'template', 'invoiceformat'], [3]);
        }

        $data["cust_list"] = $cust_list;
        $data["project_list"] = $this->masterModel->getProjectList($this->merchant_id);

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
}
