<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank5Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank5', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('mmp_txn', 20)->primary();
            $table->string('mer_txn', 45)->nullable();
            $table->decimal('amt', 11)->nullable();
            $table->string('prod', 20)->nullable();
            $table->dateTime('date')->nullable();
            $table->string('bank_txn', 45)->nullable();
            $table->string('f_code', 20)->nullable();
            $table->string('clientcode', 20)->nullable();
            $table->string('bank_name', 45)->nullable();
            $table->string('auth_code', 20)->nullable();
            $table->string('ipg_txn_id', 20)->nullable();
            $table->string('merchant_id', 20)->nullable();
            $table->string('desc', 250)->nullable();
            $table->string('udf9', 45)->nullable();
            $table->string('discriminator', 20)->nullable();
            $table->string('surcharge', 45)->nullable();
            $table->string('CardNumber', 20)->nullable();
            $table->string('udf1', 100)->nullable();
            $table->string('udf2', 100)->nullable();
            $table->string('udf3', 100)->nullable();
            $table->string('udf4', 100)->nullable();
            $table->string('udf5', 100)->nullable();
            $table->string('udf6', 100)->nullable();
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
        Schema::dropIfExists('pg_ret_bank5');
    }
}
