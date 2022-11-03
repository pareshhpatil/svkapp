<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantSignupPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_signup_preferences', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('merchant_id', 10)->primary();
            $table->boolean('online_payment')->default(false);
            $table->boolean('create_invoice')->default(false);
            $table->boolean('create_customer')->default(false);
            $table->boolean('send_paymentlink')->default(false);
            $table->boolean('bulk_invoice')->default(false);
            $table->boolean('create_subscription')->default(false);
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
        Schema::dropIfExists('merchant_signup_preferences');
    }
}
