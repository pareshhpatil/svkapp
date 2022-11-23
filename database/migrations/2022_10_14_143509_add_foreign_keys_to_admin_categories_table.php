<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAdminCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_categories', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'categories_parent_id_foreign')->references(['id'])->on('admin_categories')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_categories', function (Blueprint $table) {
            $table->dropForeign('categories_parent_id_foreign');
        });
    }
}
