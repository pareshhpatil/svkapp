<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('notification_id');
            $table->string('user_id', 10)->index('userid');
            $table->boolean('notification_type');
            $table->string('link', 100)->nullable();
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->string('message1');
            $table->string('message2')->nullable()->default('');
            $table->boolean('is_dismissed')->default(false);
            $table->boolean('is_shown')->nullable()->default(false);
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
        Schema::dropIfExists('notification');
    }
}
