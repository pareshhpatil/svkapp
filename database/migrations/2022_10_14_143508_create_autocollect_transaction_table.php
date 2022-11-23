<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutocollectTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autocollect_transaction', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('transaction_id', true);
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->integer('subscription_id')->nullable();
            $table->integer('plan_id')->nullable();
            $table->char('payment_transaction_id', 10)->nullable();
            $table->decimal('amount', 11)->nullable();
            $table->string('description', 500)->default('');
            $table->string('status', 20);
            $table->boolean('is_active')->default(true);
            $table->string('pg_ref', 20)->nullable();
            $table->string('pg_ref1', 20)->nullable();
            $table->string('pg_ref2', 20)->nullable();
            $table->string('pg_ref3', 20)->nullable();
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
        Schema::dropIfExists('autocollect_transaction');
    }
}
