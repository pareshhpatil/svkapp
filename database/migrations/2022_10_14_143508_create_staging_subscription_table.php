<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagingSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staging_subscription', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('subscription_id', true);
            $table->string('payment_request_id', 10)->index('IDX_PAYMENT_REQ_ID');
            $table->string('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->integer('bulk_id');
            $table->boolean('mode')->default(true);
            $table->integer('repeat_every')->default(0);
            $table->integer('repeat_on')->nullable()->default(1);
            $table->date('start_date')->default('2014-01-01');
            $table->date('due_date')->default('2014-01-01');
            $table->integer('due_diff')->default(0);
            $table->boolean('carry_due')->default(false);
            $table->date('last_sent_date')->default('2014-01-01');
            $table->date('next_bill_date')->default('2014-01-01');
            $table->integer('end_mode')->default(1);
            $table->integer('occurrences')->nullable()->default(0);
            $table->date('end_date')->default('2014-01-01');
            $table->string('display_text', 250)->nullable();
            $table->date('billing_period_start_date')->nullable();
            $table->integer('billing_period_duration')->nullable();
            $table->string('billing_period_type', 45)->nullable();
            $table->integer('service_id')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_updated_by', 10);
            $table->timestamp('last_updated_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staging_subscription');
    }
}
