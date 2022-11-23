<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantFeeDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_fee_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('fee_detail_id', true);
            $table->string('merchant_id', 10)->index('merchant_id');
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->nullable()->default(0);
            $table->integer('pg_id');
            $table->char('swipez_fee_type', 1);
            $table->decimal('swipez_fee_val', 5);
            $table->char('pg_fee_type', 1);
            $table->decimal('pg_fee_val', 5);
            $table->char('pg_tax_type', 5);
            $table->decimal('pg_tax_val', 5);
            $table->boolean('surcharge_enabled');
            $table->boolean('pg_surcharge_enabled')->default(false);
            $table->boolean('enable_tnc')->default(false);
            $table->string('currency', 250)->default('["INR"]');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('merchant_fee_detail');
    }
}
