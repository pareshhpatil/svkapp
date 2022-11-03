<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettlementDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->decimal('settlement_amount', 11)->default(0);
            $table->decimal('requested_settlement_amount', 11)->default(0);
            $table->decimal('total_capture', 11)->nullable();
            $table->decimal('total_tdr', 11)->nullable();
            $table->decimal('total_service_tax', 11)->nullable();
            $table->string('bank_reference', 45)->nullable();
            $table->dateTime('settlement_date')->nullable()->default('2014-01-01 00:00:00');
            $table->tinyInteger('status');
            $table->tinyInteger('type')->default(1)->comment('1 Swipez\n2 Cashfree\n3 Atom');
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->string('merchant_name', 100)->nullable();
            $table->string('narrative', 100)->nullable();
            $table->string('pg_key', 45)->nullable();
            $table->string('currency', 10)->nullable()->default('INR');
            $table->integer('cashfree_transfer_id')->default(0);
            $table->string('cashfree_settlement_id', 25)->default('0');
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
        Schema::dropIfExists('settlement_detail');
    }
}
