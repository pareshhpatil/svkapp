<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('pg_id', true);
            $table->string('pg_name', 45);
            $table->boolean('pg_type')->default(true);
            $table->boolean('is_active');
            $table->string('pg_val1')->nullable();
            $table->string('pg_val2')->nullable();
            $table->string('pg_val3')->nullable();
            $table->string('pg_val4')->nullable();
            $table->string('pg_val5')->nullable();
            $table->string('pg_val6')->nullable();
            $table->string('pg_val7')->nullable();
            $table->string('pg_val8', 45)->nullable();
            $table->string('pg_val9', 60)->nullable();
            $table->string('req_url')->nullable();
            $table->string('status_url')->nullable();
            $table->boolean('nodal_settlement')->default(false);
            $table->boolean('type')->default(true);
            $table->string('ret_tname', 20);
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
        Schema::dropIfExists('payment_gateway');
    }
}
