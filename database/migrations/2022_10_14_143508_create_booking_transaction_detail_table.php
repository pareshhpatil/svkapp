<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTransactionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_transaction_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('booking_transaction_detail_id', true);
            $table->char('transaction_id', 10)->index('transaction_id_idx');
            $table->integer('calendar_id')->index('calendar_id_idx');
            $table->date('calendar_date')->nullable();
            $table->string('category_name', 100)->nullable();
            $table->string('calendar_title', 100)->nullable();
            $table->integer('slot_id')->index('slot_id_idx');
            $table->boolean('is_availed')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->integer('is_cancelled')->default(0);
            $table->string('slot', 250)->nullable();
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
        Schema::dropIfExists('booking_transaction_detail');
    }
}
