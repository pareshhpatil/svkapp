<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transaction', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('pay_transaction_id', 10)->primary();
            $table->char('payment_request_id', 10)->index('req_idx');
            $table->char('estimate_request_id', 10)->nullable();
            $table->integer('customer_id');
            $table->char('patron_user_id', 10);
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->char('merchant_user_id', 10);
            $table->decimal('amount', 11);
            $table->decimal('unit_price', 11)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('grand_total', 11)->default(0);
            $table->decimal('convenience_fee', 11)->default(0);
            $table->decimal('discount', 11)->default(0);
            $table->decimal('deduct_amount', 11)->default(0);
            $table->string('deduct_text', 100)->nullable();
            $table->integer('coupon_id')->default(0);
            $table->decimal('tax', 11)->default(0);
            $table->boolean('payment_request_type')->default(false);
            $table->tinyInteger('payment_transaction_status');
            $table->tinyInteger('bank_status')->nullable();
            $table->integer('pg_id');
            $table->integer('fee_id')->default(0);
            $table->string('pg_ref_no', 64)->nullable();
            $table->string('pg_ref_1', 64)->nullable();
            $table->string('pg_ref_2', 64)->nullable();
            $table->string('payment_mode', 45)->nullable();
            $table->string('narrative')->nullable();
            $table->boolean('late_payment')->default(false);
            $table->date('paid_on')->default('2014-01-01');
            $table->boolean('is_availed')->default(false);
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->decimal('commission', 11)->default(0);
            $table->boolean('is_partial_payment')->default(false);
            $table->string('short_url', 40)->nullable();
            $table->char('currency', 45)->default('INR');
            $table->boolean('is_settled')->default(false);
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
        Schema::dropIfExists('payment_transaction');
    }
}
