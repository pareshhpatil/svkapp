<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantOutgoingSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_outgoing_sms', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('promotion_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->integer('template_id');
            $table->string('template_name', 45);
            $table->string('sms_text', 170);
            $table->string('promotion_name', 45);
            $table->boolean('swipez_status')->default(false);
            $table->integer('total_records')->default(0);
            $table->string('job_ids', 100)->nullable();
            $table->string('error_json', 100)->nullable();
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
        Schema::dropIfExists('merchant_outgoing_sms');
    }
}
