<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank8Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank8', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('orderId', 40)->primary();
            $table->decimal('orderAmount', 11)->nullable();
            $table->string('referenceId', 10)->nullable();
            $table->string('txStatus', 20)->nullable();
            $table->string('paymentMode', 20)->nullable();
            $table->dateTime('txTime')->nullable();
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
        Schema::dropIfExists('pg_ret_bank8');
    }
}
