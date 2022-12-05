<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('mobile', 15)->nullable();
            $table->string('email_id', 250)->nullable();
            $table->string('otp', 500)->nullable();
            $table->integer('customer_id')->nullable()->default(0);
            $table->string('merchant_id', 10)->nullable();
            $table->char('user_id', 10)->nullable();
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
        Schema::dropIfExists('otp');
    }
}
