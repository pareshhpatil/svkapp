<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerColumnMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_column_metadata', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->bigIncrements('column_id');
            $table->char('merchant_id', 10)->index('merchant_id_idx');
            $table->string('column_datatype', 45);
            $table->char('position', 1)->default('L');
            $table->string('column_name', 250);
            $table->string('default_column_value', 45)->nullable();
            $table->string('column_type', 45);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('created_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->string('last_update_by', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_column_metadata');
    }
}
