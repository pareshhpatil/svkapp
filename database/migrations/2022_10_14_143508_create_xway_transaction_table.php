<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXwayTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xway_transaction', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('xway_transaction_id', 10)->primary();
            $table->char('merchant_id', 10)->index('merchant_id_idx');
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->boolean('xway_transaction_status')->default(false);
            $table->string('referrer_url', 200);
            $table->string('account_id', 20);
            $table->string('secure_hash');
            $table->string('return_url', 250);
            $table->string('reference_no', 50)->index('xway_trans_ref_no_idx');
            $table->decimal('amount', 11)->default(0);
            $table->decimal('absolute_cost', 11)->default(0);
            $table->decimal('surcharge_amount', 11)->nullable()->default(0);
            $table->decimal('discount', 11)->default(0);
            $table->string('currency', 10)->default('INR');
            $table->string('description', 500)->nullable();
            $table->string('customer_code', 45)->nullable();
            $table->string('name', 254)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('city', 32)->nullable();
            $table->string('state', 32)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 254)->nullable();
            $table->string('payment_id', 45)->nullable();
            $table->string('pg_ref_no1', 64)->nullable();
            $table->string('pg_ref_no2', 64)->nullable();
            $table->integer('pg_id')->nullable();
            $table->string('mdd', 45)->nullable();
            $table->string('udf1', 250)->nullable();
            $table->string('udf2', 250)->nullable();
            $table->string('udf3', 250)->nullable();
            $table->string('udf4', 250)->nullable();
            $table->string('udf5', 250)->nullable();
            $table->boolean('create_invoice_api')->default(false);
            $table->string('payment_mode', 45)->nullable();
            $table->string('narrative', 100)->nullable();
            $table->integer('coupon_id')->default(0);
            $table->string('payment_request_id', 10)->nullable();
            $table->integer('plan_id')->default(0);
            $table->boolean('type')->default(true);
            $table->boolean('is_settled')->default(false);
            $table->integer('webhook_id')->default(0);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
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
        Schema::dropIfExists('xway_transaction');
    }
}
