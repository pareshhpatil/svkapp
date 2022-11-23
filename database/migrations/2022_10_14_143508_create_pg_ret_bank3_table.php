<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank3', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('TXNID', 64)->primary();
            $table->string('ORDERID', 10)->nullable();
            $table->string('BANKTXNID', 45)->nullable();
            $table->string('MID', 45)->nullable();
            $table->decimal('TXNAMOUNT', 11)->nullable()->default(0);
            $table->string('CURRENCY', 20)->nullable();
            $table->string('STATUS', 45)->nullable();
            $table->string('RESPCODE', 10)->nullable();
            $table->string('RESPMSG', 250)->nullable();
            $table->timestamp('TXNDATE')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->string('GATEWAYNAME', 45)->nullable();
            $table->string('BANKNAME', 100)->nullable();
            $table->string('PAYMENTMODE', 100)->nullable();
            $table->string('CHECKSUMHASH', 250)->nullable();
            $table->string('payment_method', 20)->nullable();
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
        Schema::dropIfExists('pg_ret_bank3');
    }
}
