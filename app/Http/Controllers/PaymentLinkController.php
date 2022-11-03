<?php

namespace App\Http\Controllers;
use App\Libraries\Helpers;
use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PaymentLinkController extends Controller
{
    public function __construct() {
        View::share('hide_smartlook_for_patron', false);
    }

    public function view($payment_request_id=null)
    {   
        if(!empty($payment_request_id)) {
            $data = $this->getPaymentRequestDetails($payment_request_id);

            if($data==false) {
                Session::flash('errorTitle','Invalid URL');
                Session::flash('errorMessage','The requested url is invalid.');
                return redirect('/error');
            }
            //payment_request_status array
            $payment_request_status = [1,2,3];            
            
            //show pay now button & report link button
            if((!in_array($data['requestDetails']->payment_request_status, $payment_request_status))) {
                $data['show_pay_now_btn'] = 1;
                $data['show_report_link_btn'] = 1;
                $data['show_view_receipt_button'] = 0;
            } else {
                //show view receipt button
                if($data['requestDetails']->payment_request_status == 1) { //online payment
                    //find payment transaction id for the link view receipt
                    $getPaymentReceiptDetails = DB::table('payment_transaction')
                                ->select('payment_transaction.pay_transaction_id',)
                                ->where('payment_request_id', $data['requestDetails']->payment_request_id)
                                ->where('payment_transaction_status', 1)
                                ->first();
                    if(!empty($getPaymentReceiptDetails)) {
                        $payTransactionId = Encrypt::encode($getPaymentReceiptDetails->pay_transaction_id);
                        $data['pay_transaction_id'] = $payTransactionId;
                        $data['show_view_receipt_button'] = 1;
                        $data['show_report_link_btn'] = 0;
                        $data['show_pay_now_btn'] = 0;
                    } else {
                        $data['show_view_receipt_button'] = 0;
                        $data['show_report_link_btn'] = 0;
                        $data['show_pay_now_btn'] = 0;
                    }
                } else if($data['requestDetails']->payment_request_status == 2) { //offline payment
                    $getPaymentReceiptDetails = DB::table('offline_response')
                                ->select('offline_response.offline_response_id')
                                ->where('payment_request_id', $data['requestDetails']->payment_request_id)
                                ->where('transaction_status', 1)
                                ->first();
                    if(!empty($getPaymentReceiptDetails)) {
                        $payTransactionId = Encrypt::encode($getPaymentReceiptDetails->offline_response_id);
                        $data['pay_transaction_id'] = $payTransactionId;
                        $data['show_view_receipt_button'] = 1;
                        $data['show_report_link_btn'] = 0;
                        $data['show_pay_now_btn'] = 0;
                    } else {
                        $data['show_view_receipt_button'] = 0;
                        $data['show_report_link_btn'] = 0;
                        $data['show_pay_now_btn'] = 0;
                    }
                } else {
                    $data['show_view_receipt_button'] = 0;
                    $data['show_report_link_btn'] = 0;
                    $data['show_pay_now_btn'] = 0;
                }
            }
        }
        $data['title'] = 'Payment request';
        return view('app/patron/paymentlink/view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payeeinfo($payment_request_id=null)
    {
        if(!empty($payment_request_id)) {
            $data = $this->getPaymentRequestDetails($payment_request_id);
            //check payment is done or not
            if($data['requestDetails']->payment_request_status == 1 || $data['requestDetails']->payment_request_status == 2){
                return $this->view($payment_request_id);
            }
            
            //check merchant_fee_details for the customer
            $findMerchantFeeDetails = DB::table('merchant_fee_detail')
            ->select('merchant_fee_detail.fee_detail_id')
            ->where('merchant_id',$data['requestDetails']->merchant_id)
            ->where('is_active',1)
            ->first();

            if(empty($findMerchantFeeDetails)) {
                $data['show_payeeinfo_form'] = 0;
            } else {
                $data['show_payeeinfo_form'] = 1;
            }
        }
        $data['title'] = 'Payee info';
        return view('app/patron/paymentlink/payeeinfo',$data);
    }

    public function paymentReceipt($pay_transaction_id=null) {
        $data['title'] = 'Payment confirmation';
        if(!empty($pay_transaction_id)) { 
            $data['pay_transaction_id'] = $pay_transaction_id;
            $payTransactionId = Encrypt::decode($pay_transaction_id);

            if (substr($payTransactionId, 0, 1) == 'T') {
                $getPaymentReceiptDetails = DB::table('payment_transaction')
                                ->join('merchant', 'merchant.merchant_id', '=', 'payment_transaction.merchant_id')
                                ->join('customer', 'customer.customer_id', '=', 'payment_transaction.customer_id')
                                ->join('user','user.user_id','=','payment_transaction.merchant_user_id')
                                ->select('payment_transaction.pay_transaction_id','payment_transaction.payment_request_id','payment_transaction.narrative','payment_transaction.amount','payment_transaction.payment_transaction_status','payment_transaction.created_date','merchant.company_name','user.email_id as merchant_email','user.mobile_no as merchant_mobile','customer.mobile','customer.email', DB::raw("CONCAT(customer.first_name,' ',customer.last_name) as full_name"))
                                ->where('pay_transaction_id', $payTransactionId)
                                ->first();
                if(!empty($getPaymentReceiptDetails)) {
                    $getPaymentReceiptDetails->paymentType='T';
                    $getPaymentReceiptDetails->payment_request_id = Encrypt::encode($getPaymentReceiptDetails->payment_request_id);
                } else {
                    Session::flash('errorTitle','Invalid URL');
                    Session::flash('errorMessage','The requested url is invalid.');
                    return redirect('/error');
                }
                
            } else if (substr($payTransactionId, 0, 1) == 'H') {
                $getPaymentReceiptDetails = DB::table('offline_response')
                                ->join('merchant', 'merchant.merchant_id', '=', 'offline_response.merchant_id')
                                ->join('customer', 'customer.customer_id', '=', 'offline_response.customer_id')
                                ->join('user','user.user_id','=','offline_response.merchant_user_id')
                                ->select('offline_response.offline_response_id','offline_response.payment_request_id','offline_response.narrative','offline_response.amount','offline_response.transaction_status','offline_response.created_date','offline_response.is_active','merchant.company_name','user.email_id as merchant_email','user.mobile_no as merchant_mobile','customer.mobile','customer.email', DB::raw("CONCAT(customer.first_name,' ',customer.last_name) as full_name"))
                                ->where('offline_response_id', $payTransactionId)
                                ->first();
                if(!empty($getPaymentReceiptDetails)) {
                    $getPaymentReceiptDetails->paymentType='H';
                    $getPaymentReceiptDetails->payment_transaction_status = $getPaymentReceiptDetails->transaction_status;
                } else {
                    Session::flash('errorTitle','Invalid URL');
                    Session::flash('errorMessage','The requested url is invalid.');
                    return redirect('/error');
                }
            }
            $data['paymentReceiptDetails'] = $getPaymentReceiptDetails;
        }
        return view('app/patron/paymentlink/paymentReceipt',$data);
    }

    public function reportLink($payment_request_id=null) {
        if(!empty($payment_request_id)) {
            $data = $this->getPaymentRequestDetails($payment_request_id);
            if($data==false) {
                Session::flash('errorTitle','Invalid URL');
                Session::flash('errorMessage','The requested url is invalid.');
                return redirect('/error');
            }
        }
        $data['title'] = 'Report Merchant';
        //find reson from config table except config key 6 
        $getUnsubscribeReasonTypes = DB::table('config')
                                    ->where('config_type', 'unsubscribe_type')
                                    ->where('config_key','!=',6)
                                    ->pluck('config_value', 'config_key')
                                    ->toArray();
        $data['reason'] = $getUnsubscribeReasonTypes;
        return view('app/patron/paymentlink/reportLink',$data);
    }

    public function reportUnsubscribe(Request $request){
        if($request){
            $payment_request_id = $request->payment_request_id;
            $saveUnsubscribe['payment_request_id'] = Encrypt::decode($payment_request_id);
            $saveUnsubscribe['merchant_id'] = $request->merchant_id;
            $saveUnsubscribe['full_name'] = $request->full_name;
            $saveUnsubscribe['email'] = $request->email;
            $saveUnsubscribe['mobile'] = $request->mobile;
            $saveUnsubscribe['reason_type'] = $request->reason;
            $saveUnsubscribe['created_date'] = Carbon::now();

            //find reason for reason_type
            if(!empty($request->reason)) {
                $getReason = DB::table('config')->select('config_value')->where('config_type', 'unsubscribe_type')->where('config_key', $request->reason)->first();
                $saveUnsubscribe['reason'] =  $getReason->config_value;
                
                $SavedUnsubscribe = DB::insert('insert into unsubscribe (email,mobile,full_name,payment_request_id,merchant_id,reason_Type,reason,created_date) values (?,?,?,?,?,?,?,?)', [$saveUnsubscribe['email'],$saveUnsubscribe['mobile'],$saveUnsubscribe['full_name'],$saveUnsubscribe['payment_request_id'],$saveUnsubscribe['merchant_id'],$saveUnsubscribe['reason_type'],$saveUnsubscribe['reason'],$saveUnsubscribe['created_date']]);
                if ($SavedUnsubscribe) {
                    return $this->reportLinkStatus($payment_request_id, 1);
                }
            } else {
                return $this->reportLinkStatus($payment_request_id, 0);
            }
        }
        return $this->reportLinkStatus($payment_request_id, 0);
    }


    public function reportLinkStatus($payment_request_id=null,$status=0) {
        if($status==1 && !empty($payment_request_id)) {
            $data = $this->getPaymentRequestDetails($payment_request_id);
        }
        $data['status'] = $status;
        $data['title'] = 'Report received';
        return view('app/patron/paymentlink/reportStatus',$data);
    }

    public function getPaymentRequestDetails($payment_request_id=null) {
        if(!empty($payment_request_id)) {
            $data['payment_request_id'] = $payment_request_id;
            $paymentRequestId = Encrypt::decode($payment_request_id);
            $data['requestDetails'] = DB::table('payment_request')
                                ->join('merchant', 'merchant.merchant_id', '=', 'payment_request.merchant_id')
                                ->join('customer', 'customer.customer_id', '=', 'payment_request.customer_id')
                                ->select('payment_request.payment_request_id','payment_request.absolute_cost','payment_request.due_date','payment_request.narrative','payment_request.merchant_id','payment_request.payment_request_status','merchant.company_name','customer.customer_id','customer.address','customer.state','customer.city','customer.zipcode','customer.email','customer.mobile', DB::raw("CONCAT(customer.first_name,' ',customer.last_name) as full_name"))
                                ->where('payment_request_id', $paymentRequestId)
                                ->first();
            
            if($data['requestDetails']!=null) {
                return $data;
            } else {
                return false;
            }
        }
    }

}
