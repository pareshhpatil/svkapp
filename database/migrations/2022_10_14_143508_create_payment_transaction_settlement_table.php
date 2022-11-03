<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionSettlementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transaction_settlement', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('settlement_id', 20)->nullable()->index('settlement_id');
            $table->string('payment_id', 45)->nullable();
            $table->string('transaction_id', 10)->nullable()->index('transaction_id');
            $table->string('payment_request_id', 10)->nullable()->index('request_id');
            $table->string('merchant_id', 10)->nullable();
            $table->string('patron_id', 10)->nullable();
            $table->decimal('captured', 11)->nullable();
            $table->decimal('tdr', 11)->nullable();
            $table->decimal('service_tax', 11)->nullable();
            $table->string('bank_reff', 20)->nullable();
            $table->dateTime('transaction_date')->nullable()->default('2014-01-01 00:00:00');
            $table->dateTime('settlement_date')->nullable()->default('2014-01-01 00:00:00');
            $table->char('currency', 3)->nullable()->default('INR');
            $table->timestamp('created_date')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transaction_settlement');
    }
}
