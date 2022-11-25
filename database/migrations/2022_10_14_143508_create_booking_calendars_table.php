<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_calendars', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';
            $table->charset = 'utf8';

            $table->integer('calendar_id', true);
            $table->integer('category_id')->index('cat_id_idx');
            $table->char('merchant_id', 10)->index('merchant_id_idx');
            $table->string('calendar_title');
            $table->string('calendar_email', 700)->default('');
            $table->integer('calendar_order')->default(0);
            $table->integer('calendar_active')->default(1);
            $table->string('booking_unit', 45)->default('Court');
            $table->string('capture_details', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('logo', 250)->nullable();
            $table->string('notification_email', 500)->nullable();
            $table->string('notification_mobile', 100)->nullable();
            $table->integer('max_booking')->default(0);
            $table->string('confirmation_message', 1000)->nullable();
            $table->string('tandc', 500)->nullable();
            $table->string('cancellation_policy', 500)->nullable();
            $table->string('cancellation_type', 45)->default('0');
            $table->string('cancellation_days', 45)->default('0');
            $table->string('cancellation_hours', 45)->default('0');
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
        Schema::dropIfExists('booking_calendars');
    }
}
