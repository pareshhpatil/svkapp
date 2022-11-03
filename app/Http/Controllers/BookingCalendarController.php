<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Encrypt;
use App\Jobs\MerchantSendMail;
use App\Model\ParentModel;
use App\Model\BookingCalendar;
use Illuminate\Support\Facades\Session;
use App\Libraries\Helpers;
use Yajra\Datatables\Datatables;
use Log;

class BookingCalendarController extends Controller
{

    public function __construct()
    {
        $this->bookingCalendarModel = new BookingCalendar();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * Getting started landing page
     */
    public function landing($payment_request_id = null)
    {
        $encryptrd_payment_request_id = $payment_request_id;
        $payment_request_id = Encrypt::decode($payment_request_id);
        $cancellation_type = $this->bookingCalendarModel->getCancellationType($payment_request_id)[0]->cancellation_type;
        if ($cancellation_type > 1) {
            $type = substr($payment_request_id, 0, 1);
            switch ($type) {
                case 'T':
                    $type = 'Online';
                    break;
                case 'H':
                    $type = 'Offline';
                    break;
                default:
                    $type = 'Xway';
                    break;
            }

            $data["slot_details"] = $this->bookingCalendarModel->getSlotDetails($payment_request_id);
            $data["count_slot_details"] = count($data["slot_details"]);
            $data["cancellation_policy"] = isset($data["slot_details"][0]->cancellation_policy) ? $data["slot_details"][0]->cancellation_policy : '';
            $data["tandc"] = isset($data["slot_details"][0]->tandc) ? $data["slot_details"][0]->tandc : '';
            $data["receipt_details"] = $this->bookingCalendarModel->getReceiptDetails($payment_request_id, $type)[0];
            $data["payment_request_id"] = $encryptrd_payment_request_id;
            if (count($data["slot_details"]) > 0) {
                foreach ($data["slot_details"] as $slot_data) {
                    $slot_data->total_amount =   ($slot_data->amount *  $slot_data->qty);
                }
                return view('/booking-calendar/landing',  $data);
            } else {

                return view('/booking-calendar/landingallcancelled',  $data);
            }
        } else {
            return view('/booking-calendar/landingnotallowed', []);
        }
    }

    public function landingconfirm($payment_request_id = null)
    {
        $encryptrd_payment_request_id = $payment_request_id;
        $payment_request_id = Encrypt::decode($payment_request_id);
        $type = substr($payment_request_id, 0, 1);
        switch ($type) {
            case 'T':
                $type = 'Online';
                break;
            case 'H':
                $type = 'Offline';
                break;
            default:
                $type = 'Xway';
                break;
        }


        $data["slot_details"] = $this->bookingCalendarModel->getSlotDetails($payment_request_id);
        foreach ($data["slot_details"] as $slot_data) {
            $slot_data->total_amount =   ($slot_data->amount *  $slot_data->qty);
        }
        $data["receipt_details"] = $this->bookingCalendarModel->getReceiptDetails($payment_request_id, $type)[0];
        $data["payment_request_id"] = $encryptrd_payment_request_id;

        return view('/booking-calendar/landingconfirm',  $data);
    }

    public function landingreciept(Request $request, $payment_request_id = null)
    {
        $encryptrd_payment_request_id = $payment_request_id;
        $payment_request_id = Encrypt::decode($payment_request_id);

        $cancellation_type = $this->bookingCalendarModel->getCancellationType($payment_request_id)[0]->cancellation_type;

        $type = substr($payment_request_id, 0, 1);
        switch ($type) {
            case 'T':
                $type = 'Online';
                break;
            case 'H':
                $type = 'Offline';
                break;
            default:
                $type = 'Xway';
                break;
        }

        $data["receipt_details"] = $this->bookingCalendarModel->getReceiptDetails($payment_request_id, $type)[0];
        $data["slot_details"] = $this->bookingCalendarModel->getSlotDetails($payment_request_id);

        foreach ($data["slot_details"] as $key => $slot_data) {
            $slot_data->total_amount =   ($slot_data->amount * $request->newCount[$key]);
            $slot_data->new_qty = $request->newCount[$key];
        }

        foreach ($data["slot_details"] as $cancel_data) {
            $quantity  = $cancel_data->qty;
            $newquantity  = $cancel_data->new_qty;
            $cancelled_qty =  $quantity -  $newquantity;
            $slot_id  = $cancel_data->slot_id;

            $this->bookingCalendarModel->updateSlotTransactionDetails($payment_request_id, $cancelled_qty, $slot_id);
            $this->bookingCalendarModel->updateSlotDetails($cancelled_qty, $slot_id);
        }
        $data["payment_request_id"] = $encryptrd_payment_request_id;
        $data["refundAmount"] =  $request->refundAmountFinal;
        $data["coupon_code"] = '';
        if ($cancellation_type == 3) {
            //send coupon 
            $coupon_code = strtoupper(uniqid(substr(sha1(time()), 0, 2)));
            $start_date = date('Y-m-d', strtotime($data["receipt_details"]->date));
            $end_date =  date('Y-m-d', strtotime($data["receipt_details"]->date . ' +120 day'));
            $data["coupon_code"] =  $coupon_code;
            $id = $this->bookingCalendarModel->createCoupon(
                $data["receipt_details"]->merchant_user_id,
                $data["slot_details"][0]->merchant_id,
                $coupon_code,
                $payment_request_id,
                $start_date,
                $end_date,
                '1',
                '1',
                '0',
                $request->refundAmountFinal
            );

            $mail_data = [];
            $mail_data["coupon_code"] = $coupon_code;
            $mail_data["receipt_details"] =  $data["receipt_details"];
            $mail_data["slot_details"] = $data["slot_details"];
            $mail_data["refundAmount"] =  $request->refundAmountFinal;
            MerchantSendMail::dispatch($data["receipt_details"]->patron_email,  $data["receipt_details"]->company_name, $mail_data, 'MAIL_BOOKING_CALENDAR')->onQueue(env('MERCHANT_BOOKING_CANCELLATION_QUEUE'));

            return view('/booking-calendar/landingreciept', $data);
        } else if ($cancellation_type == 2) {
            $invoice_model = new ParentModel();
            $det = $invoice_model->getTableRow('merchant_security_key', 'merchant_id', $data["slot_details"][0]->merchant_id);
            $response = $this->initiateRefund($det->access_key_id, $det->secret_access_key, $payment_request_id, $request->refundAmountFinal, 'Booking Cancelled by User');
            if ($response) {
                return view('/booking-calendar/landingreciept', $data);
            }
        } else {
            return view('/booking-calendar/landingnotallowed', []);
        }
    }

    public function initiateRefund($access_key_id, $secret_access_key, $transaction_id, $amount, $reason)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SWIPEZ_BASE_URL') . 'api/v1/merchant/payment/refund',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "access_key_id": "' . $access_key_id . '",
            "secret_access_key": "' . $secret_access_key . '",
            "transaction_id": "' . $transaction_id . '",
            "amount": "' . $amount . '",
            "reason":"' . $reason . '"
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/plain'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $array = json_decode($response, 1);
        if ($array['errmsg'] == '') {
            return true;
        } else {
            return false;
        }
    }

    public function cancellationlist(Request $request)
    {
        $dates = Helpers::setListDates();
        $data = Helpers::setBladeProperties('Booking Calendar Cancellations',  ['expense'], []);
        $data['cancel_status'] = isset($request->cancel_status) ? $request->cancel_status : 0;

        $data['datatablejs'] = 'table-no-export';
        $data['gst2ra'] = 'gst2ra';

        $data["cancellation_list"] = $this->bookingCalendarModel->getCancellationList($this->merchant_id, $dates['from_date'],  $dates['to_date'],  $data['cancel_status']);
        $data["cancellation_list_count"] = count($data["cancellation_list"]);

        return view('/booking-calendar/cancellation_list', $data);
    }

    public function cancellationlistData(Request $request)
    {
        $list = $this->bookingCalendarModel->getCancellationList($this->merchant_id, urldecode($request->from),  urldecode($request->to), $request->status);
        if (!empty($list)) {
            foreach ($list as $key => $row) {
                $list[$key]->encrypted_trans_id = Encrypt::encode($row->transaction_id);
            }
        }

        return Datatables::of($list)
            ->addColumn('action', function ($list) {
                return ' <div class="btn-group">
                                <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="/merchant/transaction/cancellations/refund/' . $list->encrypted_trans_id . '" target="_blank"><i class="fa fa-download"></i>Refund</a></span>
                                    </li>
                                    <li>
                                        <a href="/merchant/transaction/cancellations/denyrefund/' . $list->encrypted_trans_id . '" target="_blank"><i class="fa fa-undo"></i>Deny Refund</a>
                                    </li>
                                </ul>
                            </div>';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
}
