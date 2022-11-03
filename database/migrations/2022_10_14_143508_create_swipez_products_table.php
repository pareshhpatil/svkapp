<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwipezProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swipez_products', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('product_id', true);
            $table->string('title', 500);
            $table->longText('description');
            $table->string('icon', 100)->nullable();
            $table->boolean('share_button')->default(false);
            $table->string('dashboard_link', 100)->nullable();
            $table->string('service_ids', 45)->nullable();
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
        Schema::dropIfExists('swipez_products');
    }
}
