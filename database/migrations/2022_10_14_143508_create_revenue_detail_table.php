<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenueDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenue_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('transaction_id', 10)->index('transaction_id');
            $table->string('merchant_id', 10);
            $table->string('merchant_name', 100);
            $table->integer('payu_settlement_id');
            $table->integer('swipez_settlement_id');
            $table->boolean('status')->default(false);
            $table->integer('merchant_tdr_id');
            $table->string('payment_mode', 45);
            $table->decimal('amount', 11);
            $table->decimal('pg_settlement_amount', 11);
            $table->decimal('pg_tdr_amount', 11);
            $table->decimal('pg_service_tax', 5);
            $table->decimal('swipez_tdr', 5);
            $table->decimal('swipez_amount', 11);
            $table->decimal('swipez_revenue', 11);
            $table->decimal('swipez_service_tax', 11);
            $table->integer('franchise_id')->default(0);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->timestamp('updated_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenue_detail');
    }
}
