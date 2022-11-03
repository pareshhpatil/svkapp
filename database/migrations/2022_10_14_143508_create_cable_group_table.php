<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCableGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cable_group', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('group_id', true);
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->string('group_name', 45);
            $table->boolean('group_type')->default(false);
            $table->tinyInteger('min_package_selection')->default(0);
            $table->tinyInteger('max_package_selection')->default(0);
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
        Schema::dropIfExists('cable_group');
    }
}
