<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantBillingProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_billing_profile', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('merchant_id', 10)->index('IDX_PROFILE_MERCHANT_ID');
            $table->string('profile_name', 45)->nullable();
            $table->string('company_name', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->char('gst_number', 15)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('business_email', 250)->nullable();
            $table->string('business_contact', 45)->nullable();
            $table->string('country', 45)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->char('pan', 10)->nullable();
            $table->char('tan', 10)->nullable();
            $table->char('cin_no', 21)->nullable();
            $table->string('sac_code', 20)->nullable();
            $table->string('business_category', 45)->nullable();
            $table->string('reg_address', 250)->nullable();
            $table->string('reg_state', 45)->nullable();
            $table->string('reg_city', 45)->nullable();
            $table->string('reg_zipcode', 10)->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer('gsp_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('reg_country', 45)->nullable();
            $table->string('currency', 100)->nullable();
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->integer('invoice_seq_id')->nullable()->default(0);
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->char('gstin', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_billing_profile');
    }
}
