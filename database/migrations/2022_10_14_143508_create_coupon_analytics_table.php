<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_analytics', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->string('transaction_id', 10)->nullable();
            $table->string('request_type', 20)->nullable();
            $table->string('code', 45)->nullable();
            $table->string('customer_code', 45)->nullable();
            $table->string('customer_name', 100)->nullable();
            $table->timestamp('created_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_analytics');
    }
}
