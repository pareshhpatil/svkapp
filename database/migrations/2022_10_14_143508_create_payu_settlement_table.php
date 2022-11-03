<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayuSettlementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payu_settlement', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('payment_id', 40)->index('payment_id_idx');
            $table->tinyInteger('status')->default(0);
            $table->decimal('amount', 11);
            $table->dateTime('added_on_date');
            $table->dateTime('succeeded_on_date');
            $table->string('transaction_id', 10)->index('transaction_idx');
            $table->string('customer_name', 250);
            $table->string('customer_email', 250);
            $table->string('customer_phone', 15);
            $table->string('payment_status', 100);
            $table->decimal('settlement_amount', 11);
            $table->dateTime('settlement_date');
            $table->decimal('payu_tdr_charge', 11);
            $table->decimal('service_tax', 11);
            $table->decimal('convenience_fee_charge', 11);
            $table->string('payment_mode', 20);
            $table->string('product_info', 250)->nullable();
            $table->string('payment_source', 100)->nullable();
            $table->string('payment_parts', 100)->nullable();
            $table->string('payee_info', 250)->nullable();
            $table->string('utr_number', 20)->nullable();
            $table->string('udf1', 45)->nullable();
            $table->string('udf2', 45)->nullable();
            $table->string('udf3', 45)->nullable();
            $table->string('udf4', 45)->nullable();
            $table->string('udf5', 45)->nullable();
            $table->string('error_message', 100)->nullable();
            $table->timestamp('created_date')->nullable()->default('2014-01-01 00:00:00');
            $table->timestamp('updated_date')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payu_settlement');
    }
}
