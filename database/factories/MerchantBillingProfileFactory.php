<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MerchantBillingProfile;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(MerchantBillingProfile::class, function (Faker $faker) {
    return [
        // 'merchant_id' => 'M000013162', 
        'profile_name' => $faker->text(10), 
        'company_name' => $faker->text(10), 
        'gst_number' => '18AABCU9603R1ZM',
        'state' => $faker->text(8),
        'address' => $faker->text(20),
        'business_email' => $faker->unique()->safeEmail,
        'business_contact' => $faker->numerify('##########'),
        'country' => 'india', 
        'city' => $faker->text(7),
        'zipcode' => $faker->numerify('######'),
        'pan' => 'ABCD1234F', 
        'reg_address' => $faker->text(20), 
        'reg_city' => $faker->text(7), 
        'reg_state' => $faker->text(8), 
        'reg_zipcode' => $faker->numerify('######'),
        'is_default' => 1,
        'is_active' => 1,
        'reg_country' => 'india',
        // 'created_by'  => 'M000013162', 
        // 'last_update_by' => 'M000013162',
    ];
});
