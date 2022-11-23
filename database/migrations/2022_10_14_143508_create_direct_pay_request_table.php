<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectPayRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_pay_request', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->decimal('amount', 11)->default(0);
            $table->string('narrative', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('mobile', 12)->nullable();
            $table->string('country', 75)->nullable()->default('India');
            $table->string('customer_code', 45)->nullable();
            $table->string('short_link', 45)->nullable();
            $table->char('currency', 3)->default('INR');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('direct_pay_request');
    }
}
