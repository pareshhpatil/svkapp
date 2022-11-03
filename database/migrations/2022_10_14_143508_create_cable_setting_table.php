<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCableSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cable_setting', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('merchant_id', 10)->primary();
            $table->integer('ncf_qty')->default(0);
            $table->decimal('ncf_fee', 11)->default(0);
            $table->decimal('ncf_tax', 5)->default(0);
            $table->string('ncf_tax_name', 45)->nullable();
            $table->boolean('ncf_base_package')->default(false);
            $table->boolean('ncf_addon_package')->default(false);
            $table->boolean('ncf_alacarte_package')->default(false);
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
        Schema::dropIfExists('cable_setting');
    }
}
