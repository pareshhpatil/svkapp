<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('account_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->string('package_id', 4);
            $table->integer('custom_package_id')->default(0);
            $table->char('fee_transaction_id', 10);
            $table->decimal('amount_paid', 10);
            $table->integer('individual_invoice')->default(0);
            $table->integer('bulk_invoice')->default(0);
            $table->integer('total_invoices')->default(0);
            $table->integer('event_booking')->default(0);
            $table->integer('free_sms')->default(0);
            $table->boolean('coupon')->default(false);
            $table->boolean('supplier')->default(false);
            $table->integer('merchant_role')->default(0);
            $table->boolean('pg_integration')->default(false);
            $table->boolean('site_builder')->default(false);
            $table->boolean('brand_keyword')->default(false);
            $table->boolean('inventory')->default(true);
            $table->dateTime('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->integer('license_key_id')->default(0);
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
        Schema::dropIfExists('account');
    }
}
