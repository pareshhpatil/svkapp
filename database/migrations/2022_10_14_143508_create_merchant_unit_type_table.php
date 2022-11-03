<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantUnitTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_unit_type', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('name', 45);
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10)->nullable();
            $table->timestamp('created_date')->nullable();
            $table->string('last_update_by', 10)->nullable();
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_unit_type');
    }
}
