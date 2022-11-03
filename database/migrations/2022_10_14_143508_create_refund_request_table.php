<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_request', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('refund_id', 45)->nullable();
            $table->string('merchant_id', 10);
            $table->string('transaction_id', 10);
            $table->decimal('transaction_amount', 11)->default(0);
            $table->decimal('refund_amount', 11)->default(0);
            $table->decimal('amount_adjusted', 11)->default(0);
            $table->integer('pg_id')->default(0);
            $table->integer('settlement_id')->default(0);
            $table->integer('refund_status')->default(0);
            $table->boolean('refund_adjusted')->default(false);
            $table->boolean('swipez_settled')->default(false);
            $table->boolean('settlement_type')->default(false);
            $table->dateTime('refund_date')->default('2014-01-01 00:00:00');
            $table->string('reason', 254);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->string('created_by', 10);
            $table->string('last_update_by', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_request');
    }
}
