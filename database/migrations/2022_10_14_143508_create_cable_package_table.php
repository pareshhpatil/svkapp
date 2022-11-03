<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCablePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cable_package', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('package_id', true);
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->integer('sub_package_id')->default(0);
            $table->string('package_code', 45);
            $table->boolean('package_group');
            $table->string('package_name', 45);
            $table->decimal('package_cost', 11)->default(0);
            $table->decimal('gst', 5)->default(0);
            $table->decimal('total_cost', 11)->default(0);
            $table->boolean('is_default')->default(false);
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
        Schema::dropIfExists('cable_package');
    }
}
