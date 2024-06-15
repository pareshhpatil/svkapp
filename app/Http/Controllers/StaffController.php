<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use App\Models\StaffModel;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $model = null;
    public $company_access = null;
    public $payment_source = null;
    public $payment_modes = null;
    public $user_id = null;

    public function __construct()
    {
        $this->model = new StaffModel();
        if (Session::has('user_access')) {
            $user_access = Session::get('user_access');
            if ($user_access['company_access'] != '*') {
                $this->company_access = json_decode($user_access['company_access']);
            }
            if ($user_access['payment_source'] != '*') {
                $this->payment_source = json_decode($user_access['payment_source']);
            }
            if ($user_access['payment_modes'] != '*') {
                $this->payment_modes = json_decode($user_access['payment_modes']);
            }
        }

        $this->user_id = Session::get('user_id');
    }


    public function dashboard()
    {


        $user_access = $this->model->getTableRow('user_access', 'user_id',  Session::get('user_id'));
        $user_access =  json_decode(json_encode($user_access), 1);
        $data['menu'] = 0;
        $data['title'] = 'dashboard';

        $bill_list = $this->model->getTransactionList(Session::get('user_id'));
        $int = 0;
        $amount = 0;
        foreach ($bill_list as $item) {
            $amount = $amount + $item->{'amount'};
            $bill_list[$int]->amount = $this->moneyFormatIndia($item->{'amount'}, 2);
            $int++;
        }

        $pending_amount = $this->model->getPendingSum(Session::get('admin_id'));
        $balance_amount = $this->model->getSourceBalance(json_decode($user_access['payment_source']));
        $data['total_amount'] = $this->moneyFormatIndia($amount, 2);
        $data['transaction_list'] = $bill_list;
        $array['total_pending'] = $this->moneyFormatIndia($pending_amount, 2);
        $data['total_balance'] = $this->moneyFormatIndia($balance_amount, 2);
        $array['total_transactions'] = $this->moneyFormatIndia($amount, 2);
        $data['data'] = $array;
        $data['menu'] = 1;
        return view('staff.dashboard', $data);
    }

    public function paymentrequest()
    {
        $data['menu'] = 0;
        $data['title'] = 'Payment request';
        $data['employees'] = $this->model->getTableList('employee', 'admin_id', Session::get('admin_id'), 'employee_id,name');
        if ($this->company_access == null) {
            $data['companies'] = $this->model->getTableList('company', 'admin_id', Session::get('admin_id'), 'company_id,name');
        } else {
            $data['companies'] = $this->model->getTableListInArray('company', 'company_id', $this->company_access, 'company_id,name');
        }
        $data['category'] = (Session::has('category')) ? Session::has('category') : '';
        $data['company_id'] = (Session::has('company_id')) ? Session::has('company_id') : '';
        $data['companies'] = json_decode(json_encode($data['companies']), 1);
        $data['employees'] = json_decode(json_encode($data['employees']), 1);
        $data['date'] = date('Y-m-d');
        $data['data'] = [];
        return view('staff.paymentrequest', $data);
    }

    public function paymentPending()
    {
        $data['menu'] = 0;
        $data['title'] = 'Payment request';
        $bill_list = $this->model->getBillList(Session::get('admin_id'));
        $int = 0;
        $amount = 0;
        foreach ($bill_list as $item) {
            $amount = $amount + $item->{'amount'};
            $bill_list[$int]->amount = $this->moneyFormatIndia($item->{'amount'}, 2);
            $int++;
        }
        $data['total_amount'] = $this->moneyFormatIndia($amount, 2);
        $data['list'] = $bill_list;
        $data['date'] = date('Y-m-d');
        $data['data'] = [];
        $data['menu'] = 2;
        return view('staff.paymentPending', $data);
    }
    public function transactions()
    {
        $data['menu'] = 0;
        $data['title'] = 'Payment transactions';
        $bill_list = $this->model->getTransactionList(Session::get('user_id'));
        $int = 0;
        $amount = 0;
        foreach ($bill_list as $item) {
            $amount = $amount + $item->{'amount'};
            $bill_list[$int]->amount = $this->moneyFormatIndia($item->{'amount'}, 2);
            $int++;
        }
        $data['total_amount'] = $this->moneyFormatIndia($amount, 2);
        $data['list'] = $bill_list;
        $data['date'] = date('Y-m-d');
        $data['data'] = [];
        $data['menu'] = 3;
        return view('staff.paymentTransaction', $data);
    }
    public function paymentDetail($id)
    {
        $user_access = $this->model->getTableRow('user_access', 'user_id',  Session::get('user_id'));
        $user_access =  json_decode(json_encode($user_access), 1);
        $data['menu'] = 0;
        $data['title'] = 'Payment detail';
        $detail = $this->model->getBillDetail($id);
        $data['detail'] = $detail;
        $data['date'] = date('Y-m-d');
        $data['data'] = [];
        $payment_source = json_decode($user_access['payment_source']);
        if ($payment_source == null) {
            $data['paymentsource'] = $this->model->getTableList('paymentsource', 'admin_id', Session::get('admin_id'), 'paymentsource_id,name,balance');
        } else {
            $data['paymentsource'] = $this->model->getTableListInArray('paymentsource', 'paymentsource_id', $payment_source, 'paymentsource_id,name,balance');
        }
        $data['payment_modes'] = json_decode($user_access['payment_modes'], 1);
        return view('staff.paymentDetail', $data);
    }

    public function transactionDetail($id)
    {
        $transaction = $this->model->getTableRow('transaction', 'transaction_id',  $id);
        $reason = $this->model->getColumnValue('payment_transaction', 'request_id', $transaction->transaction_id, 'message', [],  'id');
        $data['reason'] = (isset($reason)) ? $reason : '';
        $employee = $this->model->getTableRow('employee', 'employee_id',  $transaction->employee_id);
        $data['menu'] = 3;
        $data['title'] = 'Payment detail';
        $data['transaction'] = $transaction;
        $data['employee'] = $employee;

        return view('staff.transactionDetail', $data);
    }

    public function paymentSend()
    {
        $user_access = $this->model->getTableRow('user_access', 'user_id',  Session::get('user_id'));
        $user_access =  json_decode(json_encode($user_access), 1);
        $data['menu'] = 4;
        $data['title'] = 'Payment transfer';
        $payment_source = json_decode($user_access['payment_source']);
        $company_access = json_decode($user_access['company_access']);
        $data['employees'] = $this->model->getTableList('employee', 'admin_id', Session::get('admin_id'), 'employee_id,name');
        if ($company_access == '') {
            $data['companies'] = $this->model->getTableList('company', 'admin_id', Session::get('admin_id'), 'company_id,name');
        } else {
            $data['companies'] = $this->model->getTableListInArray('company', 'company_id', $company_access, 'company_id,name');
        }
        if ($payment_source == null) {
            $data['paymentsource'] = $this->model->getTableList('paymentsource', 'admin_id', Session::get('admin_id'), 'paymentsource_id,name,balance');
        } else {
            $data['paymentsource'] = $this->model->getTableListInArray('paymentsource', 'paymentsource_id', $payment_source, 'paymentsource_id,name,balance');
        }

        $data['category'] = (Session::has('category')) ? Session::has('category') : '';
        $data['company_id'] = (Session::has('company_id')) ? Session::has('company_id') : '';
        $data['source_id'] = (Session::has('source_id')) ? Session::has('source_id') : '';
        $data['payment_mode'] = (Session::has('payment_mode')) ? Session::has('payment_mode') : '';
        $data['companies'] = json_decode(json_encode($data['companies']), 1);
        $data['paymentsource'] = json_decode(json_encode($data['paymentsource']), 1);
        $data['employees'] = json_decode(json_encode($data['employees']), 1);
        $data['payment_modes'] = json_decode($user_access['payment_modes'], 1);
        $data['date'] = date('Y-m-d');
        $data['data'] = [];
        return view('staff.paymentSend', $data);
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

    public function requestPaymentSave(Request $request)
    {
        $transaction_id = $this->requestsave($request, 1);
        $request->bill_id = $transaction_id;
        $this->paymentsave($request, 1);
        return redirect('/staff/payment/transactions')->withSuccess('Transaction has been save successfully');
    }

    public function paymentsave(Request $request, $return = 0)
    {
        $user_id = Session::get('user_id');
        $transaction = $this->model->getTableRow('transaction', 'transaction_id', $request->bill_id);
        $narrative = $transaction->narrative;
        if ($transaction->status != 0) {
            $this->setSuccess('Transaction Already paid');
            return redirect('/staff/payment/send')->withSuccess('Transaction Already paid');
        }

        $fee = 0;
        $success = true;
        $date = date('Y-m-d', strtotime($request->date));
        $employee = $this->model->getTableRow('employee', 'employee_id', $request->employee_id);
        $cashfree_source = array(2, 18, 19, 20, 21);
        $balance = $this->model->getColumnValue('paymentsource', 'paymentsource_id', $request->source_id, 'balance');
        if ($balance < $request->amount) {
            return redirect('/staff/payment/send')->withSuccess('Balance not available');
        }
        if (in_array($request->source_id, $cashfree_source)) {
            $mode = ($request->payment_mode == 'IMPS') ? 'imps' : 'neft';
            $fee = ($request->payment_mode == 'IMPS') ? 5.9 : 1.77;
            $transaction_id = $this->model->savePaymentTransaction($request->bill_id, $request->amount);


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
                $this->model->updatePaymentTransaction($transaction_id, $status, $referenceId, $utr, $json);

                if ($data['status'] != 'SUCCESS' && $data['status'] != 'PENDING') {
                    $success = false;
                    dd($data);
                }
            } else {
                dd($data);
            }
        }
        if ($success == true) {
            $code = 'SVK' . rand(10000000, 99999999);
            $this->model->updateTransaction(1, $request->bill_id, $request->amount, $request->payment_mode, $request->source_id, $date, $user_id, $code);

            $this->model->updateEmployeeBalance($request->amount, $request->employee_id);
            $this->model->updateBankBalance($request->amount + $fee, $request->source_id, 1);
            $this->model->savePaymentStatement($request->source_id, $request->bill_id, $date, $request->amount + $fee, 'Debit', 'Expense', "Paid to :" . $employee->name . ' for ' . $narrative, $user_id);
            if (strlen($employee->mobile) == 10) {

                $url = 'https://app.svktrv.in/transaction/detail/' . $code;
                $short_url = $this->random();
                $this->model->saveTable('short_url', ['short_url' => $short_url, 'long_url' => $url]);

                $this->sendWhatsapp($employee->mobile, $employee->name, date('d M Y'), $request->amount, $short_url);
            }
            if ($return == 0) {
                return redirect('/staff/payment/transactions')->withSuccess('Transaction has been save successfully');
            }
        }
    }

    function GuestTransactionDetail($id)
    {
        $transaction = $this->model->getTableRow('transaction', 'code',  $id);
        $reason = $this->model->getColumnValue('payment_transaction', 'request_id', $transaction->transaction_id, 'message', [],  'id');
        $data['reason'] = (isset($reason)) ? $reason : '';
        $employee = $this->model->getTableRow('employee', 'employee_id',  $transaction->employee_id);
        $data['menu'] = 3;
        $data['title'] = 'Payment detail';
        $data['transaction'] = $transaction;
        $data['employee'] = $employee;

        return view('guest.transactionDetail', $data);
    }


    function sendWhatsapp($mobile, $name, $date, $amount, $payment_link)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v19.0/350618571465341/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
	"messaging_product": "whatsapp",
	"to": "91' . $mobile . '",
	"type": "template",
	"template": {
		"name": "payment_success",
		"language": {
			"code": "en"
		},
	"components": [
		{
			"type": "body",
			"parameters": [
				{
					"type": "text",
					"text": "' . $name . '"
				},
				{
					"type": "text",
					"text": "' . $date . '"
				},
				{
					"type": "text",
					"text": "' . $amount . '"
				}
			]
		},
		{
			"type": "button",
			"sub_type": "url",
            "index": 0,
			"parameters": [
				{
					"type": "text",
					"text": "' . $payment_link . '"
				}
			]
		}

	]	}

}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . env('WHATSAPP_TOKEN'),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        Log::error('Whatsapp: ' . $response);
        $array = json_decode($response, 1);
        if (isset($array['messages'][0]['id'])) {
            $message = $this->getPaymentMessage($name, $date, $amount, 'https://app.svktrv.in/l/' . $payment_link);
            $this->model->saveWhatsapp($mobile, $name, 'Sent', $array['messages'][0]['message_status'], 'text', $message, $array['messages'][0]['id']);
        }

        return $response;
    }

    function getPaymentMessage($name, $date, $amount, $payment_link)
    {
        $text = "Payment success
    Hey " . $name . "

    Your payment has been successfully sent to your account. 💰 It may take approximately 30-40 minutes ⏰ for the payment to reflect in your account.

    Transaction Details:
    📅 Date: " . $date . "
    💼 Amount: " . $amount . "

    Please be patient while the payment processes.

    Thank you for your cooperation!

    Best regards,
    Siddhivinayak Travels House

    " . $payment_link;
        return $text;
    }

    function requestsave(Request $request, $get_transaction_id = 0)
    {
        $admin_id = Session::get('admin_id');
        $user_id = Session::get('user_id');
        Session::put('category', $request->category);
        Session::put('company_id', $request->company_id);
        $payment_mode = '';
        $this->model->saveBill($request->employee_id, $request->category, $request->company_id, 0, $request->amount, $request->remark, $request->date, $request->amount, $user_id, $admin_id);
        $this->model->updateEmployeeBalance($request->amount, $request->employee_id, 0);
        $transaction_id = $this->model->saveTransaction(0, $request->employee_id, $request->date, $request->amount, $payment_mode, $request->remark, 'NA', 0, 1, $user_id, $admin_id);
        if ($get_transaction_id == 1) {
            return $transaction_id;
        }
        return redirect()->back()->withSuccess('Payment request saved');
    }


    function random($length_of_string = 4)
    {
        // String of all alphanumeric character
        $str_result = '0123456789bcdfghjklmnpqrstvwxyz';
        // Shuffle the $str_result and returns substring
        // of specified length
        $exist = true;
        while ($exist == true) {
            $short = substr(
                str_shuffle($str_result),
                0,
                $length_of_string
            );
            $exist = $this->model->getTableRow('short_url', 'short_url', $short);
        }
        return $short;
    }
}
