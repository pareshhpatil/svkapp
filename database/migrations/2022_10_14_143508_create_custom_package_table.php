<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_package', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('package_id', true);
            $table->string('package_description', 500);
            $table->decimal('package_cost', 10);
            $table->decimal('invoice_cost', 11)->default(0);
            $table->string('booking_cost', 45)->nullable();
            $table->integer('invoice')->default(0);
            $table->integer('event_booking')->default(0);
            $table->integer('free_sms')->default(0);
            $table->string('transaction_id', 10)->nullable();
            $table->boolean('status')->nullable()->default(false);
            $table->date('start_date');
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
        Schema::dropIfExists('custom_package');
    }
}
