<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutocollectPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autocollect_plans', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('plan_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->string('plan_name', 50);
            $table->string('description', 500)->default('');
            $table->string('type', 20);
            $table->integer('max_cycle')->nullable();
            $table->decimal('amount', 11)->nullable();
            $table->decimal('max_amount', 11)->nullable();
            $table->string('interval_type', 10)->nullable();
            $table->integer('intervals')->nullable();
            $table->integer('occurrence')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('status')->default(false);
            $table->boolean('pg_type')->default(false);
            $table->string('pg_ref', 20)->nullable();
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
        Schema::dropIfExists('autocollect_plans');
    }
}
