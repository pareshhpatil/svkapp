<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagingPaymentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staging_payment_request', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('payment_request_id', 10)->primary();
            $table->integer('bulk_id')->index('IDX_BULK_ID');
            $table->integer('unit_available')->default(0);
            $table->char('converted_request_id', 10)->default('');
            $table->char('parent_request_id', 10)->default('');
            $table->char('user_id', 10);
            $table->string('merchant_id', 10)->nullable()->index('IDX_PAYMENT_REQUEST_USER_ID');
            $table->integer('customer_id');
            $table->boolean('payment_request_type')->default(true);
            $table->boolean('invoice_type')->default(true);
            $table->char('template_id', 10);
            $table->char('billing_cycle_id', 10);
            $table->integer('product_taxation_type')->default(1);
            $table->decimal('absolute_cost', 11);
            $table->decimal('basic_amount', 11);
            $table->decimal('tax_amount', 11);
            $table->decimal('invoice_total', 11);
            $table->decimal('swipez_total', 11);
            $table->decimal('convenience_fee', 11);
            $table->decimal('late_payment_fee', 11)->default(0);
            $table->decimal('grand_total', 11);
            $table->tinyInteger('payment_request_status');
            $table->decimal('previous_due', 11)->nullable()->default(0);
            $table->string('estimate_number', 45)->nullable();
            $table->string('invoice_number', 45)->nullable();
            $table->decimal('advance_received', 11)->default(0);
            $table->decimal('paid_amount', 11)->default(0);
            $table->dateTime('bill_date');
            $table->dateTime('due_date');
            $table->date('expiry_date')->nullable();
            $table->string('narrative');
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->boolean('notify_patron')->default(true);
            $table->boolean('notification_sent')->default(true);
            $table->integer('webhook_id')->default(0);
            $table->string('short_url', 45)->default('');
            $table->boolean('has_custom_reminder')->default(false);
            $table->integer('autocollect_plan_id')->default(0);
            $table->longText('plugin_value')->nullable();
            $table->char('gst_number', 15)->nullable();
            $table->integer('billing_profile_id')->default(0);
            $table->char('currency', 3)->default('INR');
            $table->string('einvoice_type', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('carry_forward_due')->default(false);
            $table->boolean('generate_estimate_invoice')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staging_payment_request');
    }
}
