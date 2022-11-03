<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutocollectSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autocollect_subscriptions', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('subscription_id', true);
            $table->integer('plan_id')->nullable();
            $table->char('payment_request_id', 10)->nullable();
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->integer('customer_id')->nullable();
            $table->char('invoice_subscription_id', 10)->nullable();
            $table->string('customer_name', 100);
            $table->string('email_id', 250);
            $table->string('mobile', 15);
            $table->char('payment_transaction_id', 10)->nullable();
            $table->decimal('auth_amount', 11)->default(1);
            $table->dateTime('expiry_date')->default('2014-01-01 00:00:00');
            $table->dateTime('next_payment_date')->default('2014-01-01 00:00:00');
            $table->string('return_url', 250);
            $table->decimal('amount', 11)->nullable();
            $table->string('description', 500)->default('');
            $table->boolean('status')->default(false);
            $table->date('start_date')->default('2014-01-01');
            $table->boolean('is_active')->default(true);
            $table->string('pg_ref', 20)->nullable();
            $table->string('auth_link', 100)->nullable();
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
        Schema::dropIfExists('autocollect_subscriptions');
    }
}
