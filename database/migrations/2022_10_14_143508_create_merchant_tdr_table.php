<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantTdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_tdr', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('merchant_id', 10)->index('merchant_idx_tdr');
            $table->tinyInteger('mode');
            $table->string('merchant_name', 100);
            $table->tinyInteger('is_active');
            $table->decimal('pg_rate', 3);
            $table->decimal('swipez_rate', 3);
            $table->decimal('fixed_fee', 11)->default(0);
            $table->decimal('threshold_amount', 11)->default(0);
            $table->date('valid_from')->default('2014-01-01');
            $table->date('valid_till')->default('2014-01-01');
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
        Schema::dropIfExists('merchant_tdr');
    }
}
