<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PaymentRequest;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

$factory->define(PaymentRequest::class, function (Faker $faker) {
    $pay_req_id = DB::select("select generate_sequence('Pay_Req_Id') as value");
    $amount = $faker->numerify('####');
    $absolute_cost = ($amount*105)/100;
    return [
        'payment_request_id' => array_shift($pay_req_id)->value,
        'payment_request_type' => 3,
        'invoice_type' => 1,
        'template_id' => 'E000006220', 
        'billing_cycle_id' => 'B000004571',
        'absolute_cost' => $absolute_cost, 
        'basic_amount' => $amount, 
        'tax_amount' => ($amount*5)/100,
        'invoice_total' => $absolute_cost, 
        'swipez_total' => $absolute_cost, 
        'convenience_fee' => 0.00, 
        'late_payment_fee' => 80, 
        'grand_total' => $absolute_cost, 
        'previous_due' => 0.00, 
        'advance_received' => 0.00, 
        'paid_amount' => 0.00, 
        'payment_request_status' => 2, 
        'bill_date' => \Carbon\Carbon::now(), 
        'due_date' => \Carbon\Carbon::now(), 
        'expiry_date' => \Carbon\Carbon::now(), 
        'narrative' => 'Swipez', 
        'franchise_id' => 0, 
        'vendor_id' => 0,
        'is_active' => 1,
        'bulk_id' => 0,
        'unit_available' => 0,
        'notify_patron' => 1,
        'notification_sent' => 1,
        'webhook_id' => 0,
        'has_custom_reminder' => 0,
        'autocollect_plan_id' => 0,
        'billing_profile_id' => 0,
    ];
    
});