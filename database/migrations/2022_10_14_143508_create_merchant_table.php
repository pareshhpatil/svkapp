<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('merchant_id', 10)->primary();
            $table->string('user_id', 10)->index('IDX_MERCHANT_USER_ID');
            $table->integer('entity_type')->nullable();
            $table->integer('partner_id')->nullable()->default(1);
            $table->boolean('type')->default(true);
            $table->tinyInteger('merchant_type');
            $table->integer('merchant_plan')->default(1);
            $table->tinyInteger('merchant_status')->default(1);
            $table->string('courier_tracking_number', 40)->nullable();
            $table->string('group_id', 10);
            $table->integer('industry_type')->nullable();
            $table->string('company_name', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('merchant_website', 100)->nullable();
            $table->tinyInteger('merchant_domain')->default(1);
            $table->string('display_url', 45)->nullable()->index('IDX_DISPLAY_URL');
            $table->string('active_products', 45)->nullable();
            $table->boolean('is_legal_complete')->default(false);
            $table->boolean('disable_online_payment')->default(false);
            $table->integer('registration_campaign_id')->default(0);
            $table->date('package_expiry_date')->default('2014-01-01');
            $table->integer('service_id')->default(2);
            $table->integer('admin_customer_id')->nullable()->default(0);
            $table->string('admin_customer_code', 45)->nullable();
            $table->string('partner_merchant_id', 20)->nullable();
            $table->string('employee_count', 20)->nullable();
            $table->string('customer_count', 20)->nullable();
            $table->string('created_by', 10);
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant');
    }
}
