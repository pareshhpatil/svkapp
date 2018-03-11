<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardController
 *
 * @author Paresh
 */

namespace App\Http\Controllers;

use App\Model\Bill;
use App\Model\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class BillController extends Controller {

    private $bill_model;
    private $master_model;

    function __construct() {
        parent::__construct();
        $this->validateSession(1);
        $this->bill_model = new Bill();
        $this->master_model = new Master();
    }

    public function bill() {
        $bill_list = $this->bill_model->getBillList($this->admin_id);
        $int = 0;
        foreach ($bill_list as $item) {
            $link = $this->encrypt->encode($item->{'bill_id'});
            $bill_list[$int]->link = $link;
            $int++;
        }
        $data['title'] = 'Pending Bills';
        $data['list'] = $bill_list;
        $data['addnewlink'] = '/admin/bill/new';
        return view('bill.list', $data);
    }

    public function transaction() {
        $list = $this->bill_model->getTransactionList($this->admin_id);
        $int = 0;
        foreach ($list as $item) {
            $link = $this->encrypt->encode($item->{'transaction_id'});
            $list[$int]->link = $link;
            $int++;
        }
        $data['title'] = 'Transaction List';
        $data['list'] = $list;
        return view('bill.transaction', $data);
    }

    public function billcreate() {
        $vendor_list = $this->master_model->getMaster('vendor', $this->admin_id);
        $paymentsource_list = $this->master_model->getMaster('paymentsource', $this->admin_id);
        $vehicle_list = $this->master_model->getMaster('vehicle', $this->admin_id);
        $data['vendor_list'] = $vendor_list;
        $data['paymentsource_list'] = $paymentsource_list;
        $data['vehicle_list'] = $vehicle_list;
        $data['title'] = 'Create Bill';
        return view('bill.create', $data);
    }

    public function billpayment($type, $link) {
        $id = $this->encrypt->decode($link);
        if ($type == 'salary') {
            $detail = $this->master_model->getMasterDetail('salary', 'salary_id', $id);
            $emp_detail = $this->master_model->getMasterDetail('employee', 'employee_id', $detail->employee_id);
            $detail->payee_name = $emp_detail->name;
            $detail->type = 2;
            $detail->vendor_id = 0;
            $detail->vehicle_id = 0;
            $detail->category = 'Employee Salary';
            $detail->amount = $detail->paid_amount;
            $detail->note = date('M-Y', strtotime($detail->salary_month)) . ' Salary';
        } else {
            $detail = $this->master_model->getMasterDetail('bill', 'bill_id', $id);
            $emp_detail = $this->master_model->getMasterDetail('vendor', 'vendor_id', $detail->vendor_id);
            $detail->payee_name = $emp_detail->business_name;
            $detail->type = 1;
            $detail->employee_id = 0;
        }
        $detail->bill_id = $id;
        $paymentsource_list = $this->master_model->getMaster('paymentsource', $this->admin_id);
        $data['det'] = $detail;
        $data['paymentsource_list'] = $paymentsource_list;
        $data['title'] = ucfirst($type) . ' Payment';
        return view('bill.payment', $data);
    }

    public function billsave(Request $request) {
        $date = date('Y-m-d', strtotime($request->date));
        if ($request->source_id != '' && $request->payment_mode != '') {
            $is_paid = 1;
        } else {
            $is_paid = 0;
        }
        $bill_id = $this->bill_model->saveBill($request->vendor_id, $request->vehicle_id, $request->category, $date, $request->amount, $request->remark, $is_paid, $this->user_id, $this->admin_id);
        if ($is_paid == 1) {
            $this->bill_model->saveTransaction($request->vendor_id, 0, $request->vehicle_id, $bill_id, $request->category, $date, $request->amount, $request->payment_mode, $request->remark, $request->ref_no, $request->source_id, 1, $this->user_id, $this->admin_id);
            $this->setSuccess('Transaction has been save successfully');
            header('Location: /admin/transaction');
        } else {
            $this->setSuccess('Bill has been save successfully');
            header('Location: /admin/bill');
        }
        exit;
    }

    public function paymentsave(Request $request) {
        $date = date('Y-m-d', strtotime($request->date));
        $this->bill_model->saveTransaction($request->vendor_id, $request->employee_id, $request->vehicle_id, $request->bill_id, $request->category, $date, $request->amount, $request->payment_mode, $request->remark, $request->ref_no, $request->source_id, $request->type, $this->user_id, $this->admin_id);
        if ($request->type == 1) {
            $this->master_model->updateTableColumn('bill', 'is_paid', 1, 'bill_id', $request->bill_id, $this->user_id);
        } else {
            $this->master_model->updateTableColumn('salary', 'is_paid', 1, 'salary_id', $request->bill_id, $this->user_id);
        }
        $this->setSuccess('Transaction has been save successfully');
        header('Location: /admin/transaction');
        exit;
    }

}
