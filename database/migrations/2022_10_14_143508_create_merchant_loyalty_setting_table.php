<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantLoyaltySettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_loyalty_setting', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('merchant_id', 10)->default('')->primary();
            $table->string('title', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('points_nomenclature', 20)->nullable()->default('Points');
            $table->decimal('earning_logic_rs', 11)->nullable()->default(0);
            $table->decimal('earning_logic_points', 11)->nullable()->default(0);
            $table->decimal('redeeming_logic_points', 11)->nullable()->default(0);
            $table->decimal('redeeming_logic_rs', 11)->nullable()->default(0);
            $table->decimal('redemption_threshold', 11)->nullable()->default(0);
            $table->integer('expiry')->nullable()->default(30);
            $table->boolean('communication_customer_earning')->nullable()->default(true);
            $table->boolean('communication_customer_redeem')->nullable()->default(true);
            $table->boolean('communication_merchant_earning')->nullable()->default(true);
            $table->boolean('communication_merchant_redeem')->nullable()->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_date')->nullable()->default('2014-01-01 00:00:00');
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_loyalty_setting');
    }
}
