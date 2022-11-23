<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Home
 *
 * @author Abhijeet
 */

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingCalendar extends Model
{

    public function getSlotDetails($transaction_id)
    {

        $retObj =  DB::select("SELECT booking_transaction_detail_id,b.slot_id, b.calendar_id,b.calendar_date,is_availed,count(b.slot_id) as qty,
        c.booking_unit,b.category_name,b.calendar_title,b.slot,b.amount,c.calendar_email, c.confirmation_message ,c.tandc ,
        c.cancellation_policy,
        bs.slot_title,
        bp.package_name, 
        bs.merchant_id
        FROM booking_transaction_detail b 
        JOIN booking_calendars c ON b.calendar_id=c.calendar_id
        JOIN booking_slots bs ON bs.slot_id = b.slot_id
        JOIN booking_packages bp ON bs.package_id = bp.package_id
        WHERE b.transaction_id='$transaction_id' 
        AND b.is_paid=1
        and b.is_cancelled = 0
        GROUP BY b.slot_id");

        return $retObj;
    }

    public function getCancellationType($transaction_id)
    {

        $retObj =  DB::select("SELECT distinct c.cancellation_type
        FROM booking_transaction_detail b 
        JOIN booking_calendars c ON b.calendar_id=c.calendar_id
        WHERE b.transaction_id='$transaction_id'");

        return $retObj;
    }

    public function getCancellationList($merchant_id, $from, $to, $status)
    {
        $retObj =  DB::select("SELECT b.calendar_date,count(b.slot_id) as cancel_qty,b.calendar_title,b.slot, transaction_id,
        concat(bs.slot_title,' - ' , bp.package_name) slot_name,
        sum(b.amount) cancel_amount
        FROM booking_transaction_detail b 
        JOIN booking_slots bs ON bs.slot_id = b.slot_id
        JOIN booking_packages bp ON bs.package_id = bp.package_id
        WHERE bs.merchant_id = '$merchant_id'
        and b.is_paid=1
        and b.is_cancelled = 1
        GROUP BY b.transaction_id");

        return $retObj;
    }

    public function getReceiptDetails($transaction_id, $type)
    {

        $retObj = DB::select("call `getPayment_receipt`('" . $transaction_id . "', '" . $type . "')");

        return $retObj;
    }

    public function createCoupon($user_id, $merchant_id, $coupon_code, $descreption, $start_date, $end_date, $limit, $is_fixed, $percent, $fixed_amount)
    {
        $id = DB::table('coupon')->insertGetId(
            [
                'user_id' => $user_id,
                'merchant_id' => $merchant_id,
                'coupon_code' =>  $coupon_code,
                'descreption' => $descreption,
                'type' =>  $is_fixed,
                'percent' => $percent,
                'fixed_amount' => $fixed_amount,
                'start_date' =>  $start_date,
                'end_date' => $end_date,
                'limit' =>  $limit,
                'available' => $limit,
                'created_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' => $user_id,
                'last_update_date' =>  date('Y-m-d H:i:s')
            ]
        );

        return $id;
    }

    public function updateSlotTransactionDetails($payment_request_id, $cancelled_qty, $slot_id)
    {
        $affected = 0;
        $retObj =  DB::select("SELECT booking_transaction_detail_id
        FROM booking_transaction_detail
        WHERE slot_id = '$slot_id'
        AND transaction_id = '$payment_request_id'
        AND is_cancelled = 0
        LIMIT $cancelled_qty");

        foreach ($retObj as $data) {
            $affected = DB::update(
                'UPDATE booking_transaction_detail set is_cancelled = 1 where booking_transaction_detail_id  = ?',
                [$data->booking_transaction_detail_id]
            );
        }

        return $affected;
    }

    public function updateSlotDetails($cancelled_qty, $slot_id)
    {
        $affected = 0;
        $retObj =  DB::select("SELECT *
        FROM booking_slots
        WHERE slot_id = '$slot_id'");

        foreach ($retObj as $data) {
            if ($data->is_multiple == 1) {
                $qty = $data->available_seat;
                if ($data->available_seat < $data->total_seat) {
                    $new_qty =  $qty + $cancelled_qty;

                    $affected = DB::update(
                        'UPDATE booking_slots set available_seat = ? where slot_id  = ?',
                        [$new_qty, $slot_id]
                    );
                }
            }
        }

        return $affected;
    }
}
