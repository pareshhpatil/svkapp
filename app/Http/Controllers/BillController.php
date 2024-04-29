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
use App\Model\Logsheet;


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
            $bill_list[$int]->amount = $this->moneyFormatIndia($item->{'amount'}, 2);
            $int++;
        }
        $data['total_amount'] = $this->moneyFormatIndia($amount, 2);
        $data['title'] = 'Pending Bills';
        $data['list'] = $bill_list;
        $data['addnewlink'] = '/admin/bill/new';
        return view('bill.list', $data);
    }

    public function billgroup()
    {
        $bill_list = $this->bill_model->getBillListGroup($this->admin_id);
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

    public function list()
    {
        $list = $this->bill_model->getIncomeList($this->admin_id);
        $int = 0;
        foreach ($list as $item) {
            $link = $this->encrypt->encode($item->{'id'});
            $list[$int]->link = $link;
            $int++;
        }
        $data['title'] = 'Income List';
        $data['addnewlink'] = '/admin/income/create';
        $data['list'] = $list;
        return view('payment.list', $data);
    }

    public function expensePending()
    {
        if (isset($_POST['company_id'])) {
            $company_id = $_POST['company_id'];
        } else {
            $company_id = 0;
        }

        if (isset($_POST['SaveButton'])) {
            $logsheet = new Logsheet();
            if ($_POST['expense_amount'] > 0 && $_POST['invoice_id'] > 0) {
                $expense_amount = $this->master_model->getMasterValue('logsheet_invoice', 'invoice_id', $_POST['invoice_id'], 'expense_amount');
                foreach ($_POST['rcheck'] as $req_id) {
                    $amount = $_POST['req_' . $req_id];
                    $logsheet->saveInvoiceExpense($_POST['invoice_id'], $req_id, $amount, $this->user_id);
                    $reqdet = $this->master_model->getMasterDetail('request', 'request_id', $req_id);
                    $amount = $reqdet->pending_amount - $amount;
                    $this->master_model->updateTableColumn('request', 'pending_amount', $amount, 'request_id', $req_id, $this->user_id);
                    if ($amount < 1) {
                        $this->master_model->updateTableColumn('request', 'adjust_status', 1, 'request_id', $req_id, $this->user_id);
                    }
                }
                $expense_amount = $expense_amount + $expense_amount;
                $this->master_model->updateTableColumn('logsheet_invoice', 'expense_amount', $expense_amount, 'invoice_id', $_POST['invoice_id'], $this->user_id);
            }

            $this->setSuccess('Expense adjustment has been save successfully');
            header('Location: /admin/expense/pending');
            exit;
        }

        $invoice_list = [];
        $expense_list = $this->bill_model->getPendingRequest(0, $this->admin_id);
        $data['title'] = 'Pending Expense List';
        $data['expense_list'] = $expense_list;
        $company_list = $this->master_model->getMaster('company', $this->admin_id);
        if ($company_id > 0) {
            $logsheet = new Logsheet();
            $invoice_list = $logsheet->getLogsheetBill($this->admin_id, 'is_active', 1, $company_id, '2024-02-01');
        }
        $data['company_id'] = $company_id;
        $data['company_list'] = $company_list;
        $data['invoice_list'] = $invoice_list;
        return view('bill.adjust', $data);
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
        $company_list = $this->master_model->getMaster('company', $this->admin_id);
        $paymentsource_list = $this->master_model->getMaster('paymentsource', $this->admin_id);
        $data['employee_list'] = $employee_list;
        $data['company_list'] = $company_list;
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

    public function paymentEntry()
    {
        if (isset($_POST['company_id'])) {
            $company_id = $_POST['company_id'];
        } else {
            $company_id = 0;
        }
        if (isset($_POST['SaveButton'])) {
            $date = date('Y-m-d', strtotime($_POST['date']));
            $income_id = $this->bill_model->saveIncome($company_id, $_POST['source_id'], $_POST['amount'], $date, $_POST['payment_mode'], $_POST['remark'], $this->user_id, $this->admin_id);
            $this->master_model->updateBankBalance($_POST['amount'], $_POST['source_id'], 0);

            if (!empty($_POST['rcheck'])) {
                foreach ($_POST['rcheck'] as $invoice_id) {
                    $this->bill_model->invoicePayment($income_id, $invoice_id, $_POST['tds_' . $invoice_id], $_POST['req_' . $invoice_id], $this->user_id);
                    $this->master_model->updateTableColumn('logsheet_invoice', 'is_paid', 1, 'invoice_id', $invoice_id, $this->user_id);
                }
            }

            $this->setSuccess('Income has been save successfully');
            header('Location: /admin/income/list');
            die();
        }


        $invoice_list = [];
        $logsheet = new Logsheet();
        $paymentsource_list = $this->master_model->getMaster('paymentsource', $this->admin_id);
        $company_list = $this->master_model->getMaster('company', $this->admin_id);
        if ($company_id > 0) {
            $invoice_list = $logsheet->getLogsheetBill($this->admin_id, 'is_paid', 0, $company_id);
        }
        $data['company_id'] = $company_id;
        $data['company_list'] = $company_list;
        $data['invoice_list'] = $invoice_list;
        $data['paymentsource_list'] = $paymentsource_list;
        $data['title'] = 'Income payment';
        return view('payment.create', $data);
    }

    public function savecredit()
    {
        $date = date('Y-m-d', strtotime($_POST['date']));
        $id = $this->bill_model->saveCredit($_POST['from_id'], $_POST['source_id'], $_POST['remark'], $date, $_POST['amount'], $this->user_id, $this->admin_id);
        if ($_POST['from_id'] > 0) {
            $this->master_model->updateBankBalance($_POST['amount'], $_POST['from_id']);
            $from_source_name = $this->master_model->getMasterValue('paymentsource', 'paymentsource_id', $_POST['from_id'], 'name');
            $to_source_name = $this->master_model->getMasterValue('paymentsource', 'paymentsource_id', $_POST['source_id'], 'name');
            $this->master_model->savePaymentStatement($_POST['from_id'], $id, $date, $_POST['amount'], 'Debit', 'Transfer', "Transfer to :" . $to_source_name . " for " . $_POST['remark'], $this->user_id);
        }
        $this->master_model->updateBankBalance($_POST['amount'], $_POST['source_id'], 0);
        $this->master_model->savePaymentStatement($_POST['source_id'], $id, $date, $_POST['amount'], 'Credit', 'Transfer', "Transfer from :" . $from_source_name . " for " . $_POST['remark'], $this->user_id);
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
        $bill_month = date('Y-m-d', strtotime($request->date));
        if ($request->source_id != '' && $request->payment_mode != '') {
            $is_paid = 1;
        } else {
            $is_paid = 0;
            $request->payment_mode = '';
            $request->source_id = 0;
        }
        if ($_POST['type'] != 3) {
            $this->bill_model->saveBill($request->employee_id, $request->category, $request->company_id, 0, $request->amount, $request->remark, $date, $request->amount, $this->user_id, $this->admin_id, $bill_month);
            $this->master_model->updateEmployeeBalance($request->amount, $request->employee_id, 0);
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

    function callapi($url, $header, $post_data = '')
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, 1);
    }






    public function paymentsave(Request $request)
    {
        $transaction = $this->master_model->getMasterDetail('transaction', 'transaction_id', $request->bill_id);
        $narrative = $transaction->narrative;
        if ($transaction->status != 0) {
            $this->setSuccess('Transaction Already paid');
            header('Location: /admin/bill');
            exit;
        }

        $success = true;
        $date = date('Y-m-d', strtotime($request->date));
        $employee = $this->master_model->getMasterDetail('employee', 'employee_id', $request->employee_id);

        if ($request->source_id == 2) {
            $mode = ($request->payment_mode == 'upi') ? 'upi' : 'neft';
            $transaction_id = $this->master_model->saveTransaction($request->bill_id, $request->amount);


            $data = $this->callapi('https://payout-api.cashfree.com/payout/v1/authorize', array(
                'accept: application/json',
                'x-client-id: ' . env('CF_KEY'),
                'x-client-secret: ' . env('CF_SECRET')
            ));
            if (isset($data['data']['token'])) {
                $email = ($employee->email != '') ? $employee->email : 'contact@siddhivinayaktravelshouse.in';
                $mobile = ($employee->mobile != '') ? $employee->mobile : '8879391658';
                $post_data = '{
					  "beneDetails": {
						"beneId": "' . $employee->employee_id . '",
						"name": "' . $employee->account_holder_name . '",
						"email": "' . $email . '",
						"phone": "' . $mobile . '",
						"address1": "NA Address",
						"bankAccount": "' . $employee->account_no . '",
						"ifsc": "' . $employee->ifsc_code . '"
					  },
					  "amount": ' . $request->amount . ',
					  "transferId": "' . $transaction_id . '",
					  "transferMode": "' . $mode . '",
					  "remarks": "Payment"
					}';
                $data = $this->callapi('https://payout-api.cashfree.com/payout/v1.2/directTransfer', array(
                    'accept: application/json',
                    'content-type: application/json',
                    'Authorization: Bearer ' . $data['data']['token']
                ), $post_data);
                $status = $data['status'];
                if (isset($data['data']['referenceId'])) {
                    $referenceId = $data['data']['referenceId'];
                    $utr = $data['data']['utr'];
                } else {
                    echo $post_data;
                    dd($data);
                }
                $json = json_encode($data);
                $this->master_model->updateTransaction($transaction_id, $status, $referenceId, $utr, $json);

                if ($data['status'] != 'SUCCESS' && $data['status'] != 'PENDING') {
                    $success = false;
                    dd($data);
                }
            } else {
                dd($data);
            }
        }
        if ($success == true) {
            $this->bill_model->updateTransaction(1, $request->bill_id, $request->amount, $request->payment_mode, $request->source_id, $date, $this->user_id);

            $this->master_model->updateEmployeeBalance($request->amount, $request->employee_id);
            $this->master_model->updateBankBalance($request->amount, $request->source_id);
            $this->master_model->savePaymentStatement($request->source_id, $request->bill_id, $date, $request->amount, 'Debit', 'Expense', "Paid to :" . $employee->name . ' for ' . $narrative, $this->user_id);
            $this->setSuccess('Transaction has been save successfully');
            header('Location: /admin/bill');
            exit;
        }
    }



    public function subscription()
    {
        $this->validateSession(array(1, 2));
        $subscription_list = $this->bill_model->getEMPSubscriptionList($this->admin_id);
        $int = 0;
        foreach ($subscription_list as $item) {
            $link = $this->encrypt->encode($item->subscription_id);
            $subscription_list[$int]->link = $link;
            $int++;
        }
        $data['list'] = $subscription_list;
        $data['title'] = 'Employee Subscription';
        $data['addnewlink'] = '/admin/bill/subscription/create';
        return view('master.subscription.list', $data);
    }

    public function subscriptioncreate()
    {
        $this->validateSession(array(1, 2));
        $data['title'] = 'Create Subscription';
        $employee_list = $this->master_model->getMaster('employee', $this->admin_id);
        $company_list = $this->master_model->getMaster('company', $this->admin_id);
        $data['company_list'] = $company_list;
        $data['employee_list'] = $employee_list;

        return view('master.subscription.create', $data);
    }

    public function subscriptionsave(Request $request)
    {
        $this->validateSession(array(1, 2));
        $employee_id = $this->bill_model->saveSubscription($request->employee_id, $request->company_id, $request->type, $request->category, 1, 1, $request->day, $request->amount, $request->note, $this->admin_id, $this->user_id);
        $this->setSuccess('Bill has been save successfully');
        header('Location: /admin/bill/subscription/create');
        exit;
    }

    public function subscriptionrequest()
    {
        $this->bill_model = new Bill();
        $list = $this->master_model->getMaster('subscription', 1, 'mode');
        foreach ($list as $item) {
            if (date('d') == $item->day && $item->last_date != date('Y-m-d')) {
                $note = date('M Y', strtotime("last month")) . ' ' . $item->note;
                $this->bill_model->saveBill($item->employee_id, $item->category, 0, 0, $item->amount, $note, date('Y-m-d'), $item->amount, 1000, $item->admin_id);
                $this->master_model->updateEmployeeBalance($item->amount, $item->employee_id, 0);
                if ($item->type == 2) {
                    $this->bill_model->saveTransaction(0, $item->employee_id, date('Y-m-d'), $item->amount, '', $note, 'NA', 0, 1, $item->user_id, 1);
                }
                $this->master_model->updateTableColumn('subscription', 'last_date', date('Y-m-d'), 'subscription_id', $item->subscription_id, 1000);
            }
        }
        echo 'done';
    }
}
