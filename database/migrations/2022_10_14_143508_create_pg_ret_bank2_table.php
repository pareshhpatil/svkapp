<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank2', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('payment_id', 40);
            $table->char('pay_transaction_id', 10);
            $table->string('transaction_id', 45);
            $table->char('is_flagged', 5);
            $table->char('response_code', 3);
            $table->string('response_message');
            $table->dateTime('date_created');
            $table->decimal('amount', 11);
            $table->string('payment_method', 10);
            $table->string('mode', 20);
            $table->string('billing_name');
            $table->string('billing_address');
            $table->string('billing_city', 32);
            $table->string('billing_state', 32);
            $table->string('billing_postal_code', 10);
            $table->string('billing_country', 3);
            $table->string('billing_phone', 20);
            $table->string('billing_email', 254);
            $table->string('delivery_name');
            $table->string('delivery_address');
            $table->string('delivery_city', 32);
            $table->string('delivery_state', 32);
            $table->string('delivery_postal_code', 10);
            $table->string('delivery_country', 3);
            $table->string('delivery_phone', 20);
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
        Schema::dropIfExists('pg_ret_bank2');
    }
}
