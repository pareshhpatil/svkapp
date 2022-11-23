<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_response', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('offline_response_id', 10)->primary();
            $table->char('payment_request_id', 10)->index('IDX_PAY_REQ_ID');
            $table->char('estimate_request_id', 10)->nullable();
            $table->integer('payment_request_type')->default(0);
            $table->char('patron_user_id', 10)->nullable();
            $table->integer('customer_id');
            $table->string('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->char('merchant_user_id', 10);
            $table->tinyInteger('offline_response_type');
            $table->date('settlement_date');
            $table->string('bank_transaction_no', 20)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->decimal('amount', 11);
            $table->decimal('tax', 11)->nullable()->default(0);
            $table->decimal('discount', 11)->nullable()->default(0);
            $table->integer('coupon_id')->default(0);
            $table->decimal('deduct_amount', 11)->default(0);
            $table->string('deduct_text', 100)->nullable();
            $table->decimal('grand_total', 11)->nullable()->default(0);
            $table->integer('quantity')->default(1);
            $table->boolean('is_availed')->default(false);
            $table->string('cheque_no', 20)->nullable();
            $table->boolean('transaction_status')->default(true);
            $table->char('cheque_status', 10)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('cash_paid_to', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('narrative')->nullable();
            $table->boolean('notify_patron')->default(true);
            $table->integer('bulk_id')->default(0);
            $table->boolean('notification_sent')->default(true);
            $table->string('short_url', 40)->nullable();
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->decimal('commission', 11)->default(0);
            $table->boolean('is_partial_payment')->default(false);
            $table->boolean('post_paid_invoice')->default(false);
            $table->char('currency', 3)->default('INR');
            $table->integer('is_active')->default(1);
            $table->string('cod_status', 20)->nullable();
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
        Schema::dropIfExists('offline_response');
    }
}
