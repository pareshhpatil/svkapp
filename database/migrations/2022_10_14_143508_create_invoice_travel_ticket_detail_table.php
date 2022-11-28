<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTravelTicketDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_travel_ticket_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('id');
            $table->char('payment_request_id', 10)->index('payment_request_idx');
            $table->date('booking_date')->nullable();
            $table->date('journey_date')->nullable();
            $table->string('name', 500)->nullable();
            $table->string('vehicle_type', 45)->nullable();
            $table->string('from_station', 45)->nullable();
            $table->string('to_station', 45)->nullable();
            $table->decimal('amount', 11)->nullable()->default(0);
            $table->decimal('charge', 11)->nullable()->default(0);
            $table->decimal('total', 11)->nullable()->default(0);
            $table->boolean('type')->default(true);
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
        Schema::dropIfExists('invoice_travel_ticket_detail');
    }
}
