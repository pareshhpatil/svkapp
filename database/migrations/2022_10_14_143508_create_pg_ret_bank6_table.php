<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank6Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank6', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('tpsl_txn_id', 20)->primary();
            $table->string('txn_status', 20)->nullable();
            $table->string('txn_msg', 45)->nullable();
            $table->string('txn_err_msg', 45)->nullable();
            $table->string('clnt_txn_ref', 20)->nullable();
            $table->string('tpsl_bank_cd', 20)->nullable();
            $table->decimal('txn_amt', 11)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->dateTime('tpsl_txn_time')->nullable();
            $table->string('bal_amt', 10)->nullable();
            $table->string('card_id', 20)->nullable();
            $table->string('alias_name', 45)->nullable();
            $table->string('BankTransactionID', 45)->nullable();
            $table->string('mandate_reg_no', 45)->nullable();
            $table->string('ipg_txn_id', 45)->nullable();
            $table->string('token', 45)->nullable();
            $table->string('hash', 45)->nullable();
            $table->string('merchant_id', 20)->nullable();
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
        Schema::dropIfExists('pg_ret_bank6');
    }
}
