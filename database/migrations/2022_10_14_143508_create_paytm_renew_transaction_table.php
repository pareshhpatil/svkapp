<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaytmRenewTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paytm_renew_transaction', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('TXNID', 45);
            $table->char('ORDERID', 10);
            $table->integer('customer_id');
            $table->char('merchant_id', 10);
            $table->decimal('TXNAMOUNT', 11);
            $table->string('SUBS_ID', 45)->nullable();
            $table->string('STATUS', 64)->nullable();
            $table->string('RESPCODE', 64)->nullable();
            $table->string('RESPMSG', 100)->nullable();
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
        Schema::dropIfExists('paytm_renew_transaction');
    }
}
