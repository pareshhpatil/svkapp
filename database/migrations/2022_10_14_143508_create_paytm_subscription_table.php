<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaytmSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paytm_subscription', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('transaction_id', 10);
            $table->integer('customer_id');
            $table->char('patron_user_id', 10);
            $table->char('merchant_id', 10);
            $table->decimal('amount', 11);
            $table->string('frequency_unit', 45);
            $table->integer('frequency')->default(1);
            $table->dateTime('expiry_date')->default('2014-01-01 00:00:00');
            $table->dateTime('start_date')->default('2014-01-01 00:00:00');
            $table->dateTime('last_paid_date')->default('2014-01-01 00:00:00');
            $table->decimal('max_amount', 11)->default(0);
            $table->integer('grace_days')->default(0);
            $table->boolean('subscription_status')->default(false);
            $table->string('paytm_subscription_id', 45)->nullable();
            $table->tinyInteger('pg_id');
            $table->integer('fee_id')->default(0);
            $table->string('pg_ref_no', 64)->nullable();
            $table->string('pg_ref_1', 64)->nullable();
            $table->string('pg_ref_2', 64)->nullable();
            $table->string('narrative')->nullable();
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
        Schema::dropIfExists('paytm_subscription');
    }
}
