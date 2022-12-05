<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrepaidPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prepaid_plan', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('plan_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->string('source', 45)->nullable();
            $table->boolean('type')->default(true);
            $table->string('category', 100);
            $table->string('plan_name', 100);
            $table->string('speed', 45);
            $table->string('data', 45);
            $table->integer('duration')->default(1);
            $table->decimal('price', 11)->nullable();
            $table->string('tax1_text', 45)->nullable();
            $table->string('tax2_text', 45)->nullable();
            $table->decimal('tax1_percent', 5)->default(0);
            $table->decimal('tax2_percent', 5)->default(0);
            $table->integer('tax1_id')->default(0);
            $table->integer('tax2_id')->default(0);
            $table->boolean('is_active')->default(false);
            $table->string('created_by', 10);
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prepaid_plan');
    }
}
