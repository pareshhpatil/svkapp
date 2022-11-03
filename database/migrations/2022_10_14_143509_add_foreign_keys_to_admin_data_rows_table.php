<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAdminDataRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_data_rows', function (Blueprint $table) {
            $table->foreign(['data_type_id'], 'data_rows_data_type_id_foreign')->references(['id'])->on('admin_data_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_data_rows', function (Blueprint $table) {
            $table->dropForeign('data_rows_data_type_id_foreign');
        });
    }
}
