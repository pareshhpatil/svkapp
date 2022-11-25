<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank9Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank9', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('payment_id', 45)->primary();
            $table->string('entity', 45)->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('order_id', 45)->nullable();
            $table->decimal('amount', 11)->nullable();
            $table->string('card_id', 45)->nullable();
            $table->string('bank', 45)->nullable();
            $table->string('wallet', 45)->nullable();
            $table->string('vpa', 45)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('contact', 20)->nullable();
            $table->string('fee', 20)->nullable();
            $table->string('tax', 20)->nullable();
            $table->string('method', 20)->nullable();
            $table->string('error_description', 250)->nullable();
            $table->dateTime('txTime')->nullable();
            $table->string('error_code', 45)->nullable();
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
        Schema::dropIfExists('pg_ret_bank9');
    }
}
