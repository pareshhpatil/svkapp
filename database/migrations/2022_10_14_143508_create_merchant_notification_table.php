<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_notification', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('id');
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->integer('type');
            $table->string('label_class', 20)->nullable();
            $table->string('label_icon', 20)->nullable();
            $table->string('message', 250)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('link', 100)->nullable();
            $table->string('link_text', 45)->nullable();
            $table->boolean('is_shown')->nullable()->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('notification_sent')->default('2014-01-01');
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
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
        Schema::dropIfExists('merchant_notification');
    }
}
