<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDataChangeDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_data_change_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('change_detail_id', true);
            $table->integer('change_id')->index('change_id');
            $table->integer('customer_id')->default(0)->index('customer_id_idx');
            $table->boolean('column_type')->nullable()->default(false);
            $table->integer('column_value_id');
            $table->string('current_value', 500)->nullable();
            $table->string('changed_value', 500)->nullable();
            $table->boolean('status')->nullable()->default(false);
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
        Schema::dropIfExists('customer_data_change_detail');
    }
}
