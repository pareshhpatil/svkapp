<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_transaction', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('package_transaction_id', 10)->primary();
            $table->char('user_id', 10);
            $table->char('merchant_id', 10)->nullable();
            $table->tinyInteger('payment_transaction_status');
            $table->integer('package_id')->default(0);
            $table->integer('custom_package_id')->default(0);
            $table->decimal('base_amount', 11)->default(0);
            $table->decimal('discount', 10)->default(0);
            $table->decimal('tax_amount', 11)->default(0);
            $table->decimal('amount', 10);
            $table->string('tax_text', 45)->nullable();
            $table->integer('coupon_id')->default(0);
            $table->string('narrative')->nullable();
            $table->integer('pg_id')->default(0);
            $table->tinyInteger('pg_type');
            $table->string('pg_ref_no', 40)->nullable();
            $table->string('pg_ref_1', 40)->nullable();
            $table->string('pg_ref_2', 40)->nullable();
            $table->string('payment_mode', 45)->nullable();
            $table->string('payment_info', 100)->nullable();
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
        Schema::dropIfExists('package_transaction');
    }
}
