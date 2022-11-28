<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLoyaltyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_loyalty_points', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->integer('customer_id')->default(0)->index('customer_idx');
            $table->char('user_id', 10)->nullable();
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->boolean('type')->default(true);
            $table->decimal('amount', 11)->default(0);
            $table->decimal('points', 11)->nullable()->default(0);
            $table->decimal('balance_points', 11)->nullable()->default(0);
            $table->boolean('status')->default(true);
            $table->string('narrative', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10)->nullable();
            $table->timestamp('created_date')->nullable()->default('2014-01-01 00:00:00');
            $table->string('updated_by', 10)->nullable();
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
        Schema::dropIfExists('customer_loyalty_points');
    }
}
