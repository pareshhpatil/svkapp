<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank4', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('payuMoneyId', 20)->primary();
            $table->string('mihpayid', 45)->nullable();
            $table->string('mode', 10)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('unmappedstatus', 20)->nullable();
            $table->string('key', 20)->nullable();
            $table->string('txnid', 20)->nullable();
            $table->decimal('amount', 11)->nullable();
            $table->decimal('discount', 11)->nullable();
            $table->decimal('net_amount_debit', 11)->nullable();
            $table->dateTime('addedon')->nullable();
            $table->string('productinfo', 250)->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('address1', 250)->nullable();
            $table->string('address2', 250)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('country', 45)->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('udf1', 100)->nullable();
            $table->string('udf2', 100)->nullable();
            $table->string('udf3', 100)->nullable();
            $table->string('udf4', 100)->nullable();
            $table->string('udf5', 100)->nullable();
            $table->string('udf6', 100)->nullable();
            $table->string('udf7', 100)->nullable();
            $table->string('udf8', 100)->nullable();
            $table->string('udf9', 100)->nullable();
            $table->string('udf10', 100)->nullable();
            $table->string('hash', 250)->nullable();
            $table->string('field1', 100)->nullable();
            $table->string('field2', 100)->nullable();
            $table->string('field5', 100)->nullable();
            $table->string('field6', 100)->nullable();
            $table->string('field7', 100)->nullable();
            $table->string('field8', 100)->nullable();
            $table->string('field9', 100)->nullable();
            $table->string('PG_TYPE', 20)->nullable();
            $table->string('encryptedPaymentId', 45)->nullable();
            $table->string('bank_ref_num', 45)->nullable();
            $table->string('bankcode', 10)->nullable();
            $table->string('error', 20)->nullable();
            $table->string('error_Message', 100)->nullable();
            $table->string('cardToken', 50)->nullable();
            $table->string('name_on_card', 50)->nullable();
            $table->string('cardnum', 20)->nullable();
            $table->string('cardhash', 100)->nullable();
            $table->string('card_merchant_param', 10)->nullable();
            $table->string('amount_split', 45)->nullable();
            $table->timestamp('created_date')->nullable();
            $table->string('created_by', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pg_ret_bank4');
    }
}
