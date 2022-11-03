<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwipezServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swipez_services', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('service_id', true);
            $table->string('title', 500);
            $table->longText('description');
            $table->string('icon', 100)->nullable();
            $table->boolean('merge_menu')->default(false);
            $table->integer('seq')->nullable()->default(0);
            $table->boolean('display')->default(true);
            $table->boolean('share_button')->default(false);
            $table->string('dashboard_link', 100)->nullable();
            $table->string('menus', 500)->nullable();
            $table->string('share_link', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
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
        Schema::dropIfExists('swipez_services');
    }
}
