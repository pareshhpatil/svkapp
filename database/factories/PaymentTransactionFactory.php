<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PaymentTransaction;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(PaymentTransaction::class, function (Faker $faker) {
    $payment_transaction_id = DB::select("select generate_sequence('Pay_Trans') as value");
    $patron_id = DB::select("select generate_sequence('Patron_id') as value");
    return [
        'pay_transaction_id' => array_shift($payment_transaction_id)->value,
        'patron_user_id' => array_shift($patron_id)->value,
        'unit_price' => 0.00, 
        'quantity' => 1, 
        'grand_total' => 0.00, 
        'convenience_fee' => 0.00, 
        'discount' => 0.00, 
        'deduct_amount' => 0.00,
        'coupon_id' => 0, 
        'tax' => 0.00, 
        'payment_request_type' => 3, 
        'payment_transaction_status' => 1, 
        'bank_status' => 0,
        'pg_id' => 1, 
        'fee_id' => 0,
        'late_payment' => 0,
        'paid_on' => date('y-m-d'),
        'is_availed' => 0,
        'franchise_id' => 0,
        'vendor_id' => 0,
        'commission' => 0.00,
        'is_partial_payment' => 0,
    ];
});
