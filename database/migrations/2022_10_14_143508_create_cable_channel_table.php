<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCableChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cable_channel', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('channel_id', true);
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->string('channel_name', 45);
            $table->string('channel_code', 45);
            $table->string('genre', 45);
            $table->string('language', 45);
            $table->decimal('cost', 11);
            $table->decimal('gst', 5)->default(0);
            $table->decimal('total_cost', 11)->default(0);
            $table->string('logo', 45)->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('updated_by', 10);
            $table->timestamp('updated_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cable_channel');
    }
}
