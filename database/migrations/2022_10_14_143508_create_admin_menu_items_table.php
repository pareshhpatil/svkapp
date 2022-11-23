<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menu_items', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->increments('id');
            $table->unsignedInteger('menu_id')->nullable()->index('menu_items_menu_id_foreign');
            $table->string('title', 191);
            $table->string('url', 191);
            $table->string('target', 191)->default('_self');
            $table->string('icon_class', 191)->nullable();
            $table->string('color', 191)->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('order');
            $table->timestamps();
            $table->string('route', 191)->nullable();
            $table->text('parameters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menu_items');
    }
}
