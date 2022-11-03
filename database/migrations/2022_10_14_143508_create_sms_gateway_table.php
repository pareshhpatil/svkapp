<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsGatewayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_gateway', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->tinyInteger('sg_id')->primary();
            $table->string('sg_name', 20);
            $table->boolean('sg_type')->default(true);
            $table->boolean('is_active');
            $table->string('sg_val1')->nullable();
            $table->string('sg_val2')->nullable();
            $table->string('sg_val3')->nullable();
            $table->string('sg_val4')->nullable();
            $table->string('sg_val5')->nullable();
            $table->string('req_url')->nullable();
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
        Schema::dropIfExists('sms_gateway');
    }
}
