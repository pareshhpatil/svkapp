<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagingInvoiceTravelParticularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staging_invoice_travel_particular', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('id');
            $table->char('payment_request_id', 10)->index('payment_request_idx');
            $table->dateTime('booking_date')->nullable();
            $table->dateTime('journey_date')->nullable();
            $table->string('name', 500)->nullable();
            $table->string('vehicle_type', 45)->nullable();
            $table->string('from_station', 45)->nullable();
            $table->string('to_station', 45)->nullable();
            $table->string('unit_type', 45)->nullable();
            $table->decimal('amount', 11)->nullable()->default(0);
            $table->decimal('charge', 11)->nullable()->default(0);
            $table->decimal('units', 5)->nullable()->default(0);
            $table->string('sac_code', 45)->nullable();
            $table->decimal('rate', 11)->nullable()->default(0);
            $table->decimal('mrp', 11)->default(0);
            $table->string('product_expiry_date', 45)->nullable();
            $table->string('product_number', 45)->nullable();
            $table->decimal('discount_perc', 11)->default(0);
            $table->decimal('discount', 11)->nullable()->default(0);
            $table->decimal('gst', 5)->nullable()->default(0);
            $table->decimal('total', 11)->nullable()->default(0);
            $table->boolean('type')->default(true)->comment('type 1 Travel 2 Cancel 3 Hotel 4 Facility');
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('bulk_id')->default(0)->index('bulk_idx');
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
        Schema::dropIfExists('staging_invoice_travel_particular');
    }
}
