<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_setting', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('merchant_id', 10)->primary();
            $table->integer('sms_gateway')->default(1);
            $table->boolean('sms_gateway_type')->default(true);
            $table->string('sms_name', 20)->nullable();
            $table->decimal('min_transaction', 11)->default(20);
            $table->decimal('max_transaction', 11)->default(100000);
            $table->integer('invoice_bulk_upload_limit')->default(0);
            $table->integer('customer_bulk_upload_limit')->nullable();
            $table->boolean('xway_enable')->default(false);
            $table->boolean('statement_enable')->default(false);
            $table->boolean('site_builder')->default(false);
            $table->boolean('booking_calendar')->default(false);
            $table->boolean('directpay_enable')->default(true);
            $table->boolean('ticket_enable')->default(false);
            $table->string('directpay_link', 45)->nullable();
            $table->boolean('is_reminder')->default(true);
            $table->boolean('is_promotions')->default(false);
            $table->boolean('show_ad')->default(false);
            $table->integer('promotion_id')->nullable();
            $table->boolean('has_franchise')->default(false);
            $table->boolean('has_webhook')->default(false);
            $table->boolean('has_qrcode')->default(false);
            $table->boolean('has_formbuilder')->default(false);
            $table->boolean('settlements_to_franchise')->default(false);
            $table->boolean('auto_approve')->default(false);
            $table->boolean('customer_auto_generate')->default(true);
            $table->boolean('expense_auto_generate')->default(false);
            $table->boolean('credit_note_auto_generate')->default(false);
            $table->boolean('debit_note_auto_generate')->default(false);
            $table->boolean('po_auto_generate')->default(false);
            $table->boolean('vendor_code_auto_generate')->default(false);
            $table->string('prefix', 10)->default('C');
            $table->string('from_email', 250)->nullable()->default('');
            $table->string('salt_key', 10)->nullable()->unique('salt_key_UNIQUE');
            $table->integer('profile_step')->default(1);
            $table->boolean('vendor_enable')->default(false);
            $table->boolean('disable_cashfree_doc')->default(true);
            $table->boolean('transfer_enable')->default(false);
            $table->dateTime('reminder_date')->default('2014-01-01 00:00:00');
            $table->boolean('customer_code_mandatory')->default(false);
            $table->string('customer_code_label', 45)->nullable();
            $table->boolean('customer_code_validation')->default(false);
            $table->boolean('password_validation')->default(false);
            $table->string('reset_password_source', 45)->nullable();
            $table->boolean('plan_invoice_send')->default(false);
            $table->boolean('plan_invoice_create')->default(false);
            $table->boolean('coupon_enable')->default(false);
            $table->string('plan_invoice_template', 10)->nullable();
            $table->boolean('coupon_enabled')->default(false);
            $table->boolean('loyalty_enable')->default(false);
            $table->integer('coupon_expiry_day')->default(30);
            $table->boolean('validate_coupon_transaction')->default(true);
            $table->boolean('gst_enable')->default(false);
            $table->boolean('cable_enable')->default(false);
            $table->boolean('online_payment')->default(false);
            $table->string('currency', 100)->nullable()->default('["INR"]');
            $table->string('crm_user_id', 50)->nullable();
            $table->string('crm_task_id', 10)->nullable();
            $table->boolean('stop_settlement')->default(false);
            $table->boolean('default_cust_column_renamed')->default(false);
            $table->boolean('request_demo')->default(false);
            $table->string('product_taxation', 20)->default('1');
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
        Schema::dropIfExists('merchant_setting');
    }
}
