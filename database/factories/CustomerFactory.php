<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'customer_code' => uniqid(),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'mobile' => $faker->numerify('##########'),
        'address' => $faker->text(20),
        'city' => $faker->text(8),
        'state' => $faker->text(8),
        'zipcode' => $faker->numerify('######'),
        'customer_group' => '["{0}"]',
        'is_active' => 1,
        'email_comm_status' => 1,
        'sms_comm_status' => 1,
        'gst_number' => '18AABCU9603R1ZM',
    ];
});
