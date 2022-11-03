<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXwayFeeDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xway_fee_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('xway_fee_id', true);
            $table->string('merchant_id', 10);
            $table->integer('xway_id');
            $table->char('swipez_fee_type', 1);
            $table->decimal('swipez_fee_val', 5);
            $table->char('pg_fee_type', 1);
            $table->decimal('pg_fee_val', 5);
            $table->char('pg_tax_type', 5);
            $table->decimal('pg_tax_val', 5);
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
        Schema::dropIfExists('xway_fee_detail');
    }
}
