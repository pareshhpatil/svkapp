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
use Log;

class BillController extends Controller
{

    private $bill_model;
    private $master_model;

    function __construct()
    {
        parent::__construct();
        $this->validateSession(array(1));
        $this->bill_model = new Bill();
        $this->master_model = new Master();
    }

    public function bill()
    {
        $bill_list = $this->bill_model->getBillList($this->admin_id);
        $int = 0;
        $amount = 0;
        foreach ($bill_list as $item) {
            $emplink = $this->encrypt->encode($item->{'employee_id'});
            $link = $this->encrypt->encode($item->{'transaction_id'});
            $bill_list[$int]->link = $link;
            $bill_list[$int]->emplink = $emplink;
            $amount = $amount + $item->{'amount'};
            $int++;
        }
        $data['total_amount'] = $amount;
        $data['title'] = 'Pending Bills';
        $data['list'] = $bill_list;
        $data['addnewlink'] = '/admin/bill/new';
        return view('bill.list', $data);
    }

    public function transaction()
    {
        $list = $this->bill_model->getTransactionList($this->admin_id);
        $int = 0;
        foreach ($list as $item) {
            $link = $this->encrypt->encode($item->{'transaction_id'});
            $emplink = $this->encrypt->encode($item->{'employee_id'});
            $list[$int]->link = $link;
            $list[$int]->emplink = $emplink;
            $int++;
        }
        $data['title'] = 'Transaction List';
        $data['list'] = $list;
        return view('bill.transaction', $data);
    }

    public function request()
    {
        $list = $this->bill_model->getRequestList($this->admin_id);
        $int = 0;
        foreach ($list as $item) {
            $link = $this->encrypt->encode($item->{'request_id'});
            $emplink = $this->encrypt->encode($item->{'employee_id'});
            $list[$int]->link = $link;
            $list[$int]->emplink = $emplink;
            $int++;
        }
        $data['title'] = 'Request List';
        $data['list'] = $list;
        return view('bill.request', $data);
    }

    public function billcreate()
    {
        $employee_list = $this->master_model->getMaster('employee', $this->admin_id);
        $paymentsource_list = $this->master_model->getMaster('paymentsource', $this->admin_id);
        $data['employee_list'] = $employee_list;
        $data['paymentsource_list'] = $paymentsource_list;
        $data['title'] = 'Create Bill';
        return view('bill.create', $data);
    }

    public function credit()
    {
        $paymentsource_list = $this->master_model->getMaster('paymentsource', $this->admin_id);
        $data['paymentsource_list'] = $paymentsource_list;
        $data['title'] = 'Payment credit';
        return view('bill.credit', $data);
    }

    public function savecredit()
    {
        $date = date('Y-m-d', strtotime($_POST['date']));
        $this->bill_model->saveCredit($_POST['from_id'], $_POST['source_id'], $_POST['remark'], $date, $_POST['amount'], $this->user_id, $this->admin_id);
        if ($_POST['from_id'] > 0) {
            $this->master_model->updateBankBalance($_POST['amount'], $_POST['from_id']);
        }
        $this->master_model->updateBankBalance($_POST['amount'], $_POST['source_id'], 0);

        $this->setSuccess('Transaction has been save successfully');
        header('Location: /admin/bill/new');
    }
    

    public function creditlist()
    {
        $c_list = $this->master_model->getMaster('payment_credit', $this->admin_id);
        $data['clist'] = $c_list;
        $data['title'] = 'Credit list';
        return view('bill.creditlist', $data);
    }


    public function statement($link)
    {
        $id = $this->encrypt->decode($link);
        $list = $this->bill_model->getStatement($id);
        $int = 0;
        foreach ($list as $item) {
            $emplink = $this->encrypt->encode($item->{'employee_id'});
            $list[$int]->emplink = $emplink;
            $int++;
        }
        $c_list = $this->master_model->getMaster('payment_credit', $id, 'source_id');
        $data['list'] = $list;
        $data['clist'] = $c_list;
        $data['title'] = 'Statement';
        return view('bill.statement', $data);
    }

    public function billpayment($type, $link)
    {
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
            $detail = $this->master_model->getMasterDetail('transaction', 'transaction_id', $id);
            $emp_detail = $this->master_model->getMasterDetail('employee', 'employee_id', $detail->employee_id);
            $detail->payee_name = $emp_detail->name;
            $detail->type = 2;
            $detail->vendor_id = 0;
        }
        $detail->bill_id = $id;
        $paymentsource_list = $this->master_model->getMaster('paymentsource', $this->admin_id);
        $data['det'] = $detail;
        $data['paymentsource_list'] = $paymentsource_list;
        $data['title'] = ucfirst($type) . ' Payment';
        return view('bill.payment', $data);
    }

    public function billsave(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->date));
        if ($request->source_id != '' && $request->payment_mode != '') {
            $is_paid = 1;
        } else {
            $is_paid = 0;
            $request->payment_mode = '';
            $request->source_id = 0;
        }
        if ($_POST['type'] != 3) {
            $this->bill_model->saveBill($request->employee_id,$request->category,0,0,$request->amount, $request->remark, $date, $request->amount, $this->user_id, $this->admin_id);
            $this->master_model->updateEmployeeBalance($request->amount, $request->employee_id,0);
        }
        if ($_POST['type'] != 1) {
            $this->bill_model->saveTransaction($is_paid, $request->employee_id, $date, $request->amount, $request->payment_mode, $request->remark, 'NA', $request->source_id, 1, $this->user_id, $this->admin_id);
            if ($is_paid == 1) {
                $this->master_model->updateEmployeeBalance($request->amount, $request->employee_id);
            }
            $this->setSuccess('Transaction has been save successfully');
            header('Location: /admin/bill/new');
        } else {
            $this->setSuccess('Bill has been save successfully');
            header('Location: /admin/bill/new');
        }
        exit;
    }

    public function paymentsave(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->date));
        $this->bill_model->updateTransaction(1, $request->bill_id, $request->amount, $request->payment_mode, $request->source_id, $date, $this->user_id);
        
        $this->master_model->updateEmployeeBalance($request->amount, $request->employee_id);
        $this->master_model->updateBankBalance($request->amount, $request->source_id);
        $this->setSuccess('Transaction has been save successfully');
        header('Location: /admin/bill');
        exit;
    }

    
}
