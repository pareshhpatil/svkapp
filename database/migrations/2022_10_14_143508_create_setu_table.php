<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setu', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('pg_id')->nullable();
            $table->text('pg_name')->nullable();
            $table->text('pg_type')->nullable();
            $table->integer('is_active')->nullable();
            $table->text('pg_val1')->nullable();
            $table->text('pg_val2')->nullable();
            $table->text('pg_val3')->nullable();
            $table->text('pg_val4')->nullable();
            $table->text('pg_val5')->nullable();
            $table->double('pg_val6')->nullable();
            $table->text('pg_val7')->nullable();
            $table->text('pg_val8')->nullable();
            $table->text('pg_val9')->nullable();
            $table->text('req_url')->nullable();
            $table->text('status_url')->nullable();
            $table->integer('nodal_settlement')->nullable();
            $table->integer('type')->nullable();
            $table->text('ret_tname')->nullable();
            $table->text('created_by')->nullable();
            $table->text('created_date')->nullable();
            $table->text('last_update_by')->nullable();
            $table->text('last_update_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setu');
    }
}
