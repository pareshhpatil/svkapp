<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantConfigDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_config_data', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('merchant_id', 10)->index('IDX_MERCHANT_ID');
            $table->string('user_id', 10);
            $table->string('key', 40)->nullable();
            $table->string('value', 500);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_config_data');
    }
}
