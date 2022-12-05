<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMerchantDetailView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `merchant_detail` AS select 1 AS `merchant_id`,1 AS `user_id`,1 AS `profile_id`,1 AS `is_default`,1 AS `is_legal_complete`,1 AS `entity_type`,1 AS `industry_type`,1 AS `type`,1 AS `merchant_type`,1 AS `merchant_plan`,1 AS `group_id`,1 AS `company_name`,1 AS `display_name`,1 AS `merchant_website`,1 AS `merchant_domain`,1 AS `display_url`,1 AS `gst_number`,1 AS `cin_no`,1 AS `pan`,1 AS `tan`,1 AS `logo`,1 AS `address`,1 AS `city`,1 AS `zipcode`,1 AS `state`,1 AS `business_email`,1 AS `business_contact`,1 AS `email_id`,1 AS `first_name`,1 AS `last_name`,1 AS `name`,1 AS `mobile_no`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS `merchant_detail`");
    }
}
