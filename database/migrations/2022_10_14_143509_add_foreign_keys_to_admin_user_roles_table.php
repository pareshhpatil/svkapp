<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAdminUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_user_roles', function (Blueprint $table) {
            $table->foreign(['user_id'], 'user_roles_user_id_foreign')->references(['id'])->on('admin_users')->onDelete('CASCADE');
            $table->foreign(['role_id'], 'user_roles_role_id_foreign')->references(['id'])->on('admin_roles')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_user_roles', function (Blueprint $table) {
            $table->dropForeign('user_roles_user_id_foreign');
            $table->dropForeign('user_roles_role_id_foreign');
        });
    }
}
