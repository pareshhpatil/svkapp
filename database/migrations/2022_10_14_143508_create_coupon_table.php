<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('coupon_id', true);
            $table->char('user_id', 10);
            $table->char('merchant_id', 10)->nullable()->index('merchant_id_idx');
            $table->string('coupon_code', 50)->index('coupon_code_idx');
            $table->string('descreption', 500)->nullable();
            $table->boolean('type')->default(false);
            $table->decimal('percent', 11)->default(0);
            $table->decimal('fixed_amount', 11)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('limit')->default(0);
            $table->integer('available')->default(0);
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
        Schema::dropIfExists('coupon');
    }
}
