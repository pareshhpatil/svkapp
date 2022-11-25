<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\XWayTransaction;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

$factory->define(XWayTransaction::class, function (Faker $faker) {
    $xway_transaction_id = DB::select("select generate_sequence('Xway_transaction_id') as value");
    return [
        'xway_transaction_id' => array_shift($xway_transaction_id)->value,
        'franchise_id' => 0, 
        'vendor_id' => 0, 
        'xway_transaction_status' => 1, 
        'referrer_url' => 'http://swipez.prod/patron/form/submit/'.Str::random(24), 
        'secure_hash' => '',
        'return_url' => 'http://swipez.prod/patron/form/success', 
        'reference_no' => '', 
        'surcharge_amount' => 0.00, 
        'discount' => 0.00, 
        'currency' => 'INR', 
        'description' => $faker->text(20), 
        'pg_id' => $faker->numerify('####'),
        'payment_mode' => 'CREDIT_CARD',
        'narrative' => 'Transaction Successful',
        'coupon_id' => 0,
        'plan_id' => 0,
        'type' => 3,
        'webhook_id' => 0,
    ];
});
