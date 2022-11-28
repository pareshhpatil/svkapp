<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventOccurenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_occurence', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('occurence_id', true);
            $table->string('event_request_id', 10)->index('req_id');
            $table->date('start_date')->default('2014-01-01');
            $table->date('end_date')->default('2014-01-01');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
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
        Schema::dropIfExists('event_occurence');
    }
}
