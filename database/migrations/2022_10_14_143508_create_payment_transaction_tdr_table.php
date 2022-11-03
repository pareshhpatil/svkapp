<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionTdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transaction_tdr', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('tdr_id', true);
            $table->string('transaction_id', 10)->index('transaction_id');
            $table->string('payment_request_id', 10)->nullable()->index('request_id');
            $table->string('merchant_id', 10)->nullable()->index('merchant_id');
            $table->string('patron_id', 10)->nullable();
            $table->string('patron_name', 250)->nullable();
            $table->string('payment_id', 45)->nullable();
            $table->timestamp('auth_date')->default('2014-01-01 00:00:00');
            $table->timestamp('cap_date')->default('2014-01-01 00:00:00');
            $table->string('payment_method', 250)->nullable();
            $table->string('bank_reff', 250)->nullable();
            $table->decimal('captured', 9)->nullable()->default(0);
            $table->decimal('refunded', 9)->nullable()->default(0);
            $table->decimal('chargeback', 9)->nullable()->default(0);
            $table->decimal('tdr', 9)->nullable()->default(0);
            $table->decimal('service_tax', 9)->nullable()->default(0);
            $table->decimal('surcharge', 9)->nullable()->default(0);
            $table->decimal('surcharge_service_tax', 9)->nullable()->default(0);
            $table->decimal('emitdr', 9)->nullable()->default(0);
            $table->decimal('emi_service_tax', 9)->nullable()->default(0);
            $table->decimal('net_amount', 9)->nullable()->default(0);
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
        Schema::dropIfExists('payment_transaction_tdr');
    }
}
