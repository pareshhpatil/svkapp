<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCaptureMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_capture_metadata', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->bigIncrements('column_id');
            $table->char('event_id', 10)->index('event_id_idx');
            $table->string('column_datatype', 45);
            $table->mediumInteger('column_position');
            $table->integer('sort_order')->default(0);
            $table->char('position', 1)->default('L');
            $table->string('section', 45)->nullable()->default('');
            $table->string('system_column_name', 45)->nullable();
            $table->string('column_name', 250);
            $table->longText('default_column_value')->nullable();
            $table->longText('description')->nullable();
            $table->string('column_type', 45)->nullable();
            $table->boolean('is_mandatory')->nullable()->default(false);
            $table->boolean('is_delete_allow')->default(true);
            $table->integer('function_id')->default(0);
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
        Schema::dropIfExists('event_capture_metadata');
    }
}
