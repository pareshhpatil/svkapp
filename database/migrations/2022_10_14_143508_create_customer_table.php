<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('customer_id', true);
            $table->string('merchant_id', 10)->index('merchant_id');
            $table->string('user_id', 10)->nullable();
            $table->string('customer_code', 45);
            $table->string('first_name', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('last_name', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('email', 250)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('mobile', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('address')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('address2', 250)->default('');
            $table->string('city', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('country', 75)->nullable()->default('India');
            $table->string('zipcode', 20)->nullable();
            $table->string('customer_group', 45)->default('["{0}"]');
            $table->char('customer_merchant_id', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('customer_status')->default(false);
            $table->boolean('payment_status')->default(false);
            $table->boolean('email_comm_status')->default(true);
            $table->boolean('sms_comm_status')->default(true);
            $table->string('qr_image_path', 100)->nullable();
            $table->string('password', 20)->nullable();
            $table->string('company_name', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('type', 20)->nullable();
            $table->decimal('balance', 11)->nullable()->default(0);
            $table->integer('bulk_id')->default(0);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();

            $table->index(['merchant_id', 'customer_code'], 'cust_code_merchant_id');
            $table->index(['merchant_id', 'customer_id'], 'cust_merchant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
