<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMembershipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_membership', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';
            $table->charset = 'utf8';

            $table->integer('id', true);
            $table->char('transaction_id', 10)->nullable();
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->string('user_id', 10)->nullable();
            $table->integer('customer_id')->index('customer_idx');
            $table->integer('category_id')->nullable()->default(0);
            $table->integer('membership_id');
            $table->date('start_date')->default('2014-01-01');
            $table->date('end_date')->default('2014-01-01');
            $table->integer('days');
            $table->string('description', 500);
            $table->decimal('amount', 11)->default(0);
            $table->boolean('status')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 45);
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
        Schema::dropIfExists('customer_membership');
    }
}
