<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\Models\ITable;

class CreateBriqRolesAndPermissionsTable extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $setSchemaTable = ITable::BRIQ_ROLES_PERMISSIONS;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->setSchemaTable)) {
            return;
        }

        Schema::create($this->setSchemaTable, function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_id');
            $table->string('permission_slug');
            $table->index(['role_id'], 'role_id_index');
            $table->index(['permission_slug'], 'permission_slug_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->setSchemaTable);
    }
}
