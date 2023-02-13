<?php

namespace App\Http\Controllers;

use App\Model\Master;
use App\Model\InvoiceFormat;
use App\Model\Invoice;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AppController;

class MasterController extends AppController
{

    private $masterModel;

    public function __construct()
    {
        $this->masterModel = new Master();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($type)
    {
        $title = ucfirst(str_replace("-", " ", $type)) . ' list';
        $data = Helpers::setBladeProperties($title,  [],  []);
        $model = new Master();
        $list = $model->getMerchantValues($this->merchant_id, 'food_delivery_partner_comission');
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->id);
        }
        $data['list'] = $list;
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/master/deliverypartnerlist', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $title = 'Create ' . ucfirst(str_replace("-", " ", $type));
        $data = Helpers::setBladeProperties($title,  [],  []);
        return view('app/merchant/master/deliverypartnercreate', $data);
    }

    /**
     * Master a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save($type, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'start_date' => 'required',
            'end_date' => 'required',
            'commission' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $model = new Master();
            $request->start_date = Helpers::sqlDate($request->start_date);
            $request->end_date = Helpers::sqlDate($request->end_date);
            $model->saveFoodDeliveryPartner($request, $this->merchant_id, $this->user_id);
            return redirect('merchant/master/delivery-partner/list')->with('success', "Delivery partner has been created");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hsnsaccode  $hsnsaccode
     * @return \Illuminate\Http\Response
     */
    public function update($type, $link)
    {
        $title = 'Update ' . ucfirst(str_replace("-", " ", $type));
        $data = Helpers::setBladeProperties($title,  [],  []);
        $id = Encrypt::decode($link);
        $model = new Master();
        $row = $model->getTableRow('food_delivery_partner_comission', 'id', $id);
        $data['detail'] = $row;
        return view('app/merchant/master/deliverypartneredit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hsnsaccode  $hsnsaccode
     * @return \Illuminate\Http\Response
     */
    public function updatesave($type, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'start_date' => 'required',
            'end_date' => 'required',
            'commission' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            //$updateCode = $request->except('_token');
            $model = new Master();
            $request->start_date = Helpers::sqlDate($request->start_date);
            $request->end_date = Helpers::sqlDate($request->end_date);
            $model->updateFoodDeliveryPartner($request, $this->user_id);
            return redirect('merchant/master/delivery-partner/list')->with('success', "Delivery partner has been updated");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hsnsaccode  $hsnsaccode
     * @return \Illuminate\Http\Response
     */
    public function delete($type, $link)
    {
        if ($link) {
            $id = Encrypt::decode($link);
            $model = new Master();
            $model->deleteTableRow('food_delivery_partner_comission', 'id', $id, $this->merchant_id, $this->user_id);
            return redirect('merchant/master/delivery-partner/list')->with('success', "Record has been deleted");
        } else {
            return redirect('merchant/master/delivery-partner/list')->with('error', "Record code can not be deleted");
        }
    }

    public function projectlist()
    {
        $title = 'Project list';
        $data = Helpers::setBladeProperties($title,  [],  []);
        $list = $this->masterModel->getProjectList($this->merchant_id);
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->id);
        }
        $data['list'] = $list;
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/project/list', $data);
    }

    public function projectdelete($link)
    {
        if ($link) {
            $id = Encrypt::decode($link);
            $this->masterModel->deleteTableRow('project', 'id', $id, $this->merchant_id, $this->user_id);
            return redirect('merchant/project/list')->with('success', "Record has been deleted");
        } else {
            return redirect('merchant/project/list')->with('error', "Record code can not be deleted");
        }
    }

    public function projectcreate()
    {
        $title = 'Create Project';
        $data = Helpers::setBladeProperties($title,  ['invoiceformat'],  []);
        $data["date"] = date("Y M d");
        $data["cust_list"] = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');
        return view('app/merchant/project/create', $data);
    }

    public function projectsave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|max:100',
            'start_date' => 'required',
            'end_date' => 'required',
            'sequence_number' => 'required',
            'seprator' => 'max:5'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        
        if($request->seprator!='' && $request->prefix=='') {
            return redirect()->back()->withInput()->withErrors('You can not add separator without prefix.');
        }

        $projectID = $request->get('project_id');

        $exists = DB::table('project')
            ->where('merchant_id', $this->merchant_id)
            ->where('project_id', $projectID)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors([
                'project_id' => 'Project ID already exists'
            ]);
        }

        $model = new InvoiceFormat();
        if ($request->sequence_number == '') {
            $request->sequence_number = 0;
        }
        
        $request->sequence_number = $model->saveSequence($this->merchant_id, $request->project_id, ($request->sequence_number - 1), $this->user_id,$request->seprator);
        $request->start_date = Helpers::sqlDate($request->start_date);
        $request->end_date = Helpers::sqlDate($request->end_date);
        $this->masterModel->saveNewProject($request, $this->merchant_id, $this->user_id);

        return redirect('merchant/project/list')->with('success', "Project has been created");
    }

    public function projectupdate($link)
    {
        $title = 'Update Project';
        $data = Helpers::setBladeProperties($title,  ['invoiceformat'],  []);
        $id = Encrypt::decode($link);
        if ($id != '') {
            $data['project_data'] = $this->masterModel->getTableRow('project', 'id', $id);
            $data['project_data']->encrypted_id = Encrypt::encode($data['project_data']->id);
            $data['sequence_data'] = $this->masterModel->getTableRow('merchant_auto_invoice_number', 'auto_invoice_id', $data['project_data']->sequence_number);
            $data['sequence_data']->val = $data['sequence_data']->val + 1;
            $data['project_data']->project_prefix = $data['sequence_data']->prefix;   //$data['project_data']->project_id;
            $data["cust_list"] = $this->masterModel->getCustomerList($this->merchant_id, '', 0, '');

            return view('app/merchant/project/update', $data);
        } else {
            return redirect('/404');
        }
    }

    public function projectupdatestore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|max:100',
            'start_date' => 'required',
            'end_date' => 'required',
            'seprator' => 'max:5'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $model = new InvoiceFormat();

            if($request->seprator!='' && $request->prefix=='') {
                return redirect()->back()->withInput()->withErrors('You can not add separator without prefix.');
            }

            $this->masterModel->updateProjectSequence($this->merchant_id, $request->sequence_id, ($request->sequence_number - 1), $request->seprator, $request->prefix);

            //check invoice sequnce number is already exist or not in payment request for that merchant & customer 
            $lastSequenceData = $this->masterModel->getTableRow('merchant_auto_invoice_number', 'auto_invoice_id', $request->sequence_id);
            $invoice_sequence_prefix = $lastSequenceData->prefix.$lastSequenceData->seprator.$request->sequence_number;
            
            $invoiceModel = new Invoice();
            $existInvoiceNo = $invoiceModel->findInvoiceNumberExist($this->merchant_id,$invoice_sequence_prefix);
            
            if ($existInvoiceNo) {
                return redirect()->back()->withInput()->withErrors('Sequence number already exist');
            }
            if ($request->sequence_number == '') {
                $request->sequence_number = 0;
            }
            
            $request->start_date = Helpers::sqlDate($request->start_date);
            $request->end_date = Helpers::sqlDate($request->end_date);
            $this->masterModel->updateProject($request, $this->merchant_id, $this->user_id);
            return redirect('merchant/project/list')->with('success', "Project has been updated");
        }
    }

    //code list
    public function codeList($link)
    {
        $project_id = Encrypt::decode($link);
        if ($project_id != '') {
            $title =  'Bill code list';
            $data = Helpers::setBladeProperties($title,  [],  []);
            $model = new Master();
            $list = $model->getProjectCodeList($this->merchant_id, $project_id);
            foreach ($list as $ck => $row) {
                $list[$ck]->encrypted_id = Encrypt::encode($row->id);
            }
            $data['project_id'] = $project_id;
            $data['list'] = $list;
            $data['datatablejs'] = 'table-no-export';

            return view('app/merchant/master/code/list', $data);
        } else {
            return redirect('/404');
        }
    }
    public function getbillcode($project_id)
    {
        //$project_id = Encrypt::decode($link);
        if ($project_id > 0) {
            $model = new Master();
            $list = $model->getProjectCodeList($this->merchant_id, $project_id);

            $list = json_decode($list, 1);

            $array = array();
            foreach ($list as $row) {
                $array[$row['code']] = $row;
            }
            $array = json_decode(json_encode($array), 1);
            return $list;
        }
        return [];
    }
    public function projectCodeDelete($project_id, $link)
    {

        if ($link) {
            $id = $link;
            $project_id = Encrypt::encode($project_id);
            $this->masterModel->deleteTableRow('csi_code', 'id', $id, $this->merchant_id, $this->user_id);

            return redirect('/merchant/code/list/' . $project_id)->with('success', "Record has been deleted");
        } else {
            return redirect('/merchant/code/list/' . $project_id)->with('error', "Record code can not be deleted");
        }
    }
    public function billedtransactionDelete($id)
    {
        $project_id = $this->masterModel->getColumnValue('billed_transaction', 'id', $id, 'project_id');
        $project_id = Encrypt::encode($project_id);
        $this->masterModel->deleteTableRow('billed_transaction', 'id', $id, $this->merchant_id, $this->user_id);
        return redirect('/merchant/billedtransaction/list/' . $project_id)->with('success', "Record has been deleted");
    }

    public function billedtransactionList($link)
    {
        $project_id = Encrypt::decode($link);
        if ($project_id != '') {
            $title =  'Billed transactions';
            $data = Helpers::setBladeProperties($title,  [],  []);
            $model = new Master();
            $list = $model->getProjectBillTransactionList($this->merchant_id, $project_id);
            foreach ($list as $ck => $row) {
                $list[$ck]->encrypted_id = Encrypt::encode($row->id);
            }
            $code_list = $model->getProjectCodeList($this->merchant_id, $project_id);
            $cost_type = $model->getCostTypes($this->merchant_id);
            $data['cost_type'] = $cost_type;
            $data['project_id'] = $project_id;
            $data['list'] = $list;
            $data['code_list'] = $code_list;
            $data['datatablejs'] = 'table-no-export';

            return view('app/merchant/master/billedtransaction/list', $data);
        } else {
            return redirect('/404');
        }
    }


    public function billedtransactionUpdate(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['date'] = Helpers::sqlDate($request->date);
        $data['merchant_id'] = $this->merchant_id;
        $this->masterModel->saveBilledTransaction($data, $this->user_id);
        return redirect('/merchant/billedtransaction/list/' . Encrypt::encode($request->project_id))->with('success', "Bill transaction detail saved");
    }
}
