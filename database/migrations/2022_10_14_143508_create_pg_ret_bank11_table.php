<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank11Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank11', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('billerBillID', 100)->nullable();
            $table->integer('amountPaid')->nullable();
            $table->string('currencyCode', 45)->nullable();
            $table->string('payerVpa', 80)->nullable();
            $table->string('platformBillID', 100)->nullable();
            $table->string('receiptId', 45)->nullable();
            $table->string('sourceAccount_ifsc', 100)->nullable();
            $table->string('sourceAccount_name', 100)->nullable();
            $table->string('sourceAccount_number', 100)->nullable();
            $table->string('transactionNote', 100)->nullable();
            $table->string('transactionId', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('timeStamp', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('trans_unique_id', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pg_ret_bank11');
    }
}
