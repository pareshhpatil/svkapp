<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantOutgoingSmsDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_outgoing_sms_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->integer('promotion_id');
            $table->integer('job_id')->nullable();
            $table->integer('customer_id');
            $table->string('customer_code', 45);
            $table->string('customer_name', 100)->nullable();
            $table->boolean('swipez_status')->default(false);
            $table->string('gateway_status', 20)->nullable()->default('');
            $table->string('mobile', 12);
            $table->string('message_id', 45)->default('0');
            $table->timestamp('delivery_date')->default('2014-01-01 00:00:00');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('merchant_outgoing_sms_detail');
    }
}
