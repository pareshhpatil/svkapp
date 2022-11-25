<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisputeRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispute_request', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('merchant_id', 10);
            $table->string('transaction_id', 10);
            $table->decimal('transaction_amount', 11)->default(0);
            $table->decimal('amount_adjusted', 11)->default(0);
            $table->integer('pg_id')->default(0);
            $table->integer('settlement_id')->default(0);
            $table->boolean('dispute_status')->default(false)->comment('0 Pending1 Merchant won 2 Customer won');
            $table->boolean('dispute_adjusted')->default(false);
            $table->boolean('swipez_settled')->default(false);
            $table->date('dispute_date')->default('2014-01-01');
            $table->string('reason', 254);
            $table->boolean('stop_settlement')->default(false);
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
        Schema::dropIfExists('dispute_request');
    }
}
