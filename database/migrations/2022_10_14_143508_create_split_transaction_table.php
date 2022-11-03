<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSplitTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('split_transaction', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->integer('id', true);
            $table->char('transaction_id', 10)->index('transaction_idx');
            $table->boolean('type')->comment('1 Credit\n2 Debit');
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->integer('vendor_id')->default(0);
            $table->integer('franchise_id')->default(0);
            $table->integer('beneficiary_id')->default(0);
            $table->decimal('amount', 11)->default(0);
            $table->boolean('split_type')->default(false);
            $table->decimal('split_value', 11)->default(0);
            $table->integer('is_settled')->default(0);
            $table->dateTime('settled_on')->default('2014-01-01 00:00:00');
            $table->integer('settlement_id')->default(0);
            $table->char('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->char('last_update_by', 10);
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
        Schema::dropIfExists('split_transaction');
    }
}
