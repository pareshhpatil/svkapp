<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultIndustryMerchLandingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_industry_merch_landing', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('default_industry_id')->primary();
            $table->integer('industry_id')->index('IDX_INDUSTRY_ID');
            $table->string('default_image_name', 100);
            $table->string('default_image_path', 100);
            $table->longText('overview')->nullable();
            $table->longText('cancellation_policy')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->longText('about_us')->nullable();
            $table->string('office_location')->nullable();
            $table->string('contact_no', 13)->nullable();
            $table->string('email_id', 250)->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('booking_background', 250)->nullable();
            $table->string('booking_title', 250)->nullable();
            $table->boolean('booking_hide_menu')->default(false);
            $table->boolean('is_active')->default(false);
            $table->string('created_by', 10)->nullable();
            $table->string('last_updated_by', 10)->nullable();
            $table->timestamp('last_updated_date')->useCurrentOnUpdate()->useCurrent();
            $table->longText('banner_text')->nullable();
            $table->longText('banner_paragraph')->nullable();
            $table->longText('why_work_with_us_text')->nullable();
            $table->longText('pay_my_bill_text')->nullable();
            $table->longText('pay_my_bill_paragraph')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_industry_merch_landing');
    }
}
