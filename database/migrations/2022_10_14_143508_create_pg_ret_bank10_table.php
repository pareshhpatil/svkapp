<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank10Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank10', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('order_id', true);
            $table->string('payment_intent_id', 45)->nullable();
            $table->string('stripe_charge_id', 45)->nullable();
            $table->string('stripe_transaction_id', 45)->nullable();
            $table->string('stripe_customer_id', 45)->nullable();
            $table->string('payment_method', 45)->nullable();
            $table->decimal('amount', 11)->nullable();
            $table->string('receipt_url', 200)->nullable();
            $table->string('destination', 45)->nullable();
            $table->string('currency', 10)->nullable();
            $table->timestamp('created_date')->nullable();
            $table->string('status', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pg_ret_bank10');
    }
}
