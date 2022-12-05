<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_slots', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';
            $table->charset = 'utf8';

            $table->integer('slot_id', true);
            $table->char('merchant_id', 10)->index('merchant_id_idx');
            $table->integer('package_id')->default(1);
            $table->string('slot_special_text', 700)->nullable();
            $table->integer('slot_special_mode');
            $table->date('slot_date');
            $table->time('slot_time_from');
            $table->time('slot_time_to');
            $table->string('slot_title', 100)->nullable();
            $table->string('slot_description', 500)->nullable();
            $table->double('slot_price')->default(0);
            $table->integer('is_primary')->default(0);
            $table->boolean('is_multiple')->default(false);
            $table->integer('min_seat')->default(0);
            $table->integer('max_seat')->default(0);
            $table->integer('total_seat')->default(0);
            $table->integer('available_seat')->default(0);
            $table->integer('slot_available')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('calendar_id');
            $table->integer('category_id')->default(0);
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
        Schema::dropIfExists('booking_slots');
    }
}
