<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXwayMerchantDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xway_merchant_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('xway_merchant_detail_id', true);
            $table->string('merchant_id', 10)->index('IDX_MERCHANT_ID');
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->string('xway_security_key', 250)->nullable();
            $table->integer('pg_id')->nullable();
            $table->boolean('logging_status')->default(false);
            $table->string('referrer_url');
            $table->string('return_url');
            $table->boolean('notify_merchant')->default(false);
            $table->boolean('notify_patron')->default(false);
            $table->boolean('surcharge_enable')->default(false);
            $table->boolean('pg_surcharge_enabled');
            $table->string('currency', 250)->default('["INR"]');
            $table->longText('ga_tag')->nullable();
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
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
        Schema::dropIfExists('xway_merchant_detail');
    }
}
