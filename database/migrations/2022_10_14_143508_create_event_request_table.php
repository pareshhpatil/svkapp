<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_request', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('event_request_id', 10)->primary();
            $table->char('user_id', 10)->nullable()->default('')->index('user_id_idx');
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->char('template_id', 10);
            $table->string('event_name', 250);
            $table->integer('event_type')->default(1);
            $table->string('venue', 250);
            $table->longText('description');
            $table->string('title', 200)->nullable();
            $table->string('short_description', 300)->nullable();
            $table->string('stop_booking_time', 20)->nullable();
            $table->integer('duration')->default(1);
            $table->integer('occurence')->default(1);
            $table->date('event_from_date')->default('2014-01-01');
            $table->date('event_to_date')->default('2014-01-01');
            $table->string('tax_text', 100)->nullable();
            $table->decimal('tax', 11)->default(0);
            $table->boolean('capture_attendee_details')->default(true);
            $table->boolean('mobile_capture')->default(false);
            $table->boolean('age_capture')->default(false);
            $table->integer('coupon_code')->nullable()->default(0);
            $table->string('short_url', 100)->nullable();
            $table->boolean('custom_capture_detail')->default(false);
            $table->string('custom_capture_title', 250)->nullable();
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->boolean('has_season_package')->default(false);
            $table->string('artist_label', 45)->nullable()->default('Artists');
            $table->string('artist', 250)->nullable();
            $table->longText('tnc')->nullable();
            $table->longText('cancellation_policy')->nullable();
            $table->longText('payee_capture')->nullable();
            $table->longText('attendees_capture')->nullable();
            $table->string('unit_type', 20)->default('Seats');
            $table->longText('global_tag')->nullable();
            $table->longText('landing_tag')->nullable();
            $table->longText('success_tag')->nullable();
            $table->string('currency', 100)->default('["INR"]');
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
        Schema::dropIfExists('event_request');
    }
}
