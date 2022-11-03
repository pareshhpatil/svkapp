<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgRetBank12Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_ret_bank12', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('txn_id', 10)->primary();
            $table->string('easepayid', 10)->nullable();
            $table->decimal('amount', 11)->nullable();
            $table->decimal('cash_back_percentage', 10)->nullable();
            $table->decimal('deduction_percentage', 10)->nullable();
            $table->string('card_type', 20)->nullable();
            $table->string('mode', 10)->nullable();
            $table->string('name', 45)->nullable();
            $table->string('email', 45)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('pg_type', 20)->nullable();
            $table->timestamp('addedon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pg_ret_bank12');
    }
}
