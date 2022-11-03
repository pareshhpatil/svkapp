<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTransactionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_transaction_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('event_transaction_detail_id', true);
            $table->string('transaction_id', 10)->index('transaction_id_idx');
            $table->string('event_request_id', 10)->index('event_id');
            $table->integer('occurence_id')->nullable();
            $table->integer('package_id');
            $table->integer('customer_id')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_availed')->default(false);
            $table->string('name', 100)->nullable();
            $table->string('mobile', 13)->nullable();
            $table->integer('age')->nullable()->default(0);
            $table->decimal('amount', 9)->default(0);
            $table->integer('coupon_code')->default(0);
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
        Schema::dropIfExists('event_transaction_detail');
    }
}
